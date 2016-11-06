<?php
//this script verifies the email id and updates the user's status in the user table to confirmed
if(!($_GET['id']||$_get['hash'])){
  die("Invalid approach.");
}

include('settings.php');
$link = mysqli_connect($databaseIP, $databaseUsername, $databasePassword, $databaseUsername);
if(mysqli_connect_error()){
    die("There was an error connecting to the database.");
}

$id = $_GET['id'];
$hash = $_GET['hash'];

$query = "SELECT `".$confirmationHashColumnName."`,`".$verificationStatusColumnName."` FROM `".$tableName."` WHERE `id`=".$id;

if($result = mysqli_query($link,$query)){
    if($row = mysqli_fetch_array($result)){
        if($hash == $row[$confirmationHashColumnName]){
            if($row[$verificationStatusColumnName] == "unverified"){ //if account is activated
                $query = "UPDATE `".$tableName."` SET `".$verificationStatusColumnName."` = 'confirmed' WHERE `id`=".$id."";
                if(mysqli_query($link,$query)){
                    //display success message here.
                    die("Your account has been activated. You may log in now.");
                }
                else {
                    die("There was a problem activating your account. Please try again later."); //update qeury didn't work
                }
            }
            else {
                //if account has already been activated
                echo ("Your account has already been activated. You may login.");
            }
        }
        else {
            die("Invalid approach. Please sign up or use the link that was sent to your email."); //hash didn't match
        }
    }
    else {
        die("Invalid approach. Please sign up or use the link that was sent to your email."); //no such id
    }
}
else {

    die ("There was a problem. Please try again later."); //select query failed.
}

?>
