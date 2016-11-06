<?php
//assuming that the HTML form will be submitted using the POST method, with the following fields:
/*
* 'submit' for the submit button
* 'email' for the user's email address
* 'password' for the user's password
*/
//assuming that the mySQL table has the following columns:
/*
* a column for id (index number) with name specified in settings
* a column for emails with name specified in settings
* a column for passwords with name specified in settings
* a column for the confirmation hash generated with name specified in settings
* a column for the verification status of user with name specified in settings
*/
//note, any additional fields can be easily programmed in using basic PHP and mySQL.


if(!$_POST['submit'])
  die(""); //this script should run only if user has submitted the form.

include('settings.php');
require $PHPMailerAutoloadPath; //importing PHPMailer package.

$link =  mysqli_connect($databaseIP,$databaseUsername,$databasePassword,$databaseUsername);
if(mysqli_connect_error())  {
  die ("Failed to connect to database");
}


$email = $_POST['email'];
$password = md5(md5($email).mysqli_real_escape_string($link, $_POST['password'])); //hashed password with email ID as salt
$confirmation = getToken(32); //generating 32 bit confirmation hash
$status = "unverified";
//new fields can be added here, and appended to the insert query.

$query = "INSERT INTO `".$tableName."` (`".$emailColumnName."`,`".$passwordColumnName."`,`".$confirmationHashColumnName."`,`".$verificationStatusColumnName."`) VALUES('".$email."','".$password."','".$confirmation."','".$status."')";
if(mysqli_query($link,$query)){
    // Succesfully inserted into database.

    //constructing email.
    $id = mysqli_insert_id($link);
    $confirmationLink = $siteURL."/PHPEmailVerification/verify.php?id=".$id."&hash=".$confirmation."";

    //change the HTML code of the mailer here. Be sure to include the confirmation link!
    $body = "<html>
              <body>

                <p>Hi,</p>
                <p>Thank you for signing up with us!</p>
                <p> Click <a href=\"".$confirmationLink."\">here</a> to confirm your account.</p>

              </body>
            </html>";
  //This will display if the mail client cannot display HTML.
    $altBody = "Copy and paste the following link into your browser window to activate your account: ".$confirmationLink."";

    //sending mail:
    sendConfirmationMail($email, $body, $altBody);
}
else {
  echo "Something went wrong. Please try again later.";
}





function getToken($length)
{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet) - 1;
    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max)];
    }
    return $token;
}

function crypto_rand_secure($min, $max)
{
    $range = $max - $min;
    if ($range < 1) return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd >= $range);
    return $min + $rnd;
}

function sendConfirmationMail($toEmail, $emailBody, $emailAltBody)
{
  include('settings.php');

  $mail = new PHPMailer;
  $mail->isSMTP();
  $mail->Host = $mailHost;
  $mail->SMTPAuth = true;
  $mail->Username = $mailUsername;
  $mail->Password = $mailPassword;
  $mail->SMTPSecure = $mailEncryptionType;
  $mail->Port = $mailPortNumber;

  $mail->setFrom($fromEmailID, $fromName);
  $mail->addAddress($toEmail);

  if($replyToEmailID)
  $mail->addReplyTo($replyToEmailID);

  if($BCCEmailID)
  $mail->addBCC($BCCEmailID);



  $mail->isHTML(true);

  $mail->Subject = $subject;
  $mail->Body    = $emailBody;
  $mail->AltBody = $emailAltBody;

  if(!$mail->send()) {
      echo 'Message could not be sent.';
      echo 'Mailer Error: ' . $mail->ErrorInfo;
  } else {
      echo 'Message has been sent. Please check your inbox and spam folder.';
  }
}


?>
