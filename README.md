# Instructions for use
1. Download the folder 'PHPEmailVerification'
2. In the 'settings.php' file, fill in all of the required fields as per the instructions in the comments.
3. If you alread have a copy of PHPMailer installed, you can fill in the path to the 'PHPMailerAutoload.php' file in 'settings.php' file and remove the phpmailer folder. Otherwise, you can leave that field blank, and the library will use the copy of PHPMailer that comes with it.
4. After filling in the settings and saving changes, place the 'PHPEmailVerification' folder in the root directory of your website.
5. On your website's sign up page(this page should be an HTML form using the POST method, with at least the email and password fields), just include the 'signup.php' file using PHP's include function.
6. And thats it! Your website now has a fully functional user management system, complete with email verification.
7. OPTIONAL: You can add more fields by simply adding them in your sign-up form, and inserting the POST requests as indicated in the comments in the 'signup.php' file. The only thing you have to modify is the SQL insert query.
8. OPTIONAL: You can also modify the HTML mailer in the 'signup.php' file, just be sure to include the email activation link.
