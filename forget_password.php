<?php

require_once ('Models/UserDataSet.php');
require_once ('Models/Operations.php');
require_once("Models/Login.php");
require_once ('Models/Mail.php');
require_once ('Models/Database.php');

$userDataSet = new UserDataSet();
$operations = new Operations();
$login = new Login();

// show form


// Checks if the login button has been pressed.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['Email'];

    // Checks if the user has entered an empty email.
    if (empty($email)) {
        // Sets the email error message to reflect that the user has not entered an email address.
        $view->erremail = "<br><span class='text-danger errorMessage'> * Please enter your email address." . "</span>";
    } // If the user has entered an email address.
    else {
        // Validates the data for security reasons.
        $email = $operations->validate($email);
    }

    $users = $login->login($email);

    if (empty($user)){
        $view->erremail = "<br><span class='text-danger errorMessage'> * No account with this email address found" . "</span>";
    }
    else{

        $dbInstance = Database::getInstance();
        $dbHandle = $this->_dbInstance->getdbConnection();

        // set first time to true
        $sql = "Update users set firstTimeLogin='True' where email=$email";
        $statement = $dbHandle->prepare($sql); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement


        // generate random password.
        $tempPassword = uniqid();
        // Hash the random password.
        $databasePassword = password_hash($tempPassword, PASSWORD_DEFAULT);


        // email random pass
        $emailMessage = "
        <html>
            <body>
                <h3>Password Reset </h3>
                <hr>
                <br>
                <p>We have set a temporary</p>
                <br>
                <p style='font-weight: bold'>Your email address: " . $email ."</p>
                <p style='font-weight: bold'>Your temporary password: ". $tempPassword ."</p>
                <br>
                <p style='font-size: 12px; color: red'>* Note that when you first log in, you will be required to change this temporary password.</p>
            </body>
        </html>
        ";

        $mailer = new Mail($email, 'Your new Book of Faces Account', $emailMessage);
        $mailer->sendMail();


    }



}


// if email send reset password and set first time login to true


require_once('Views/forget_password.phtml');