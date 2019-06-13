<?php
session_start();
/**
 * Created by PhpStorm.
 * User: dylan
 * Date: 11/01/17
 * Time: 11:31
 */
require_once("Models/Login.php");
require_once ('Models/Operations.php');

// Creates a new stdClass object for the view.
$view = new stdClass();
// Creates a new login object to log in the user.
$login = new Login();
// Creates a new operations object to validate the data.
$operations = new Operations();

// Declares the email error message as empty.
$view->erremail = "";
// Declares the password error message as empty.
$view->errpassword = "";

// Checks if the login button has been pressed.
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Declares email as the email entered in the login form.
    $email = $_POST['loginEmail'];
    // Declares password as the password entered in the login form.
    $password = $_POST['loginPassword'];

    // Checks if the user has entered an empty email.
     if (empty($email))
     {
         // Sets the email error message to reflect that the user has not entered an email address.
        $view->erremail = "<br><span class='text-danger errorMessage'> * Please enter your email address." . "</span>";
     }
     // If the user has entered an email address.
    else
    {
        // Validates the data for security reasons.
        $email = $operations->validate($email);
    }
    //Check if the user has not entered a password.
    if (empty($password))
    {
        // Sets the password error message to reflect that the user has not entered a password.
        $view->errpassword = "<br><span class='text-danger errorMessage'> * Please enter your password." . "</span>";
    }
    // If the user has entered a password.
    else
    {
        $password = $operations->validate($password);
    }

    // If the user has entered an email and password:
    if (!empty($email)  && !empty($password))
    {
        // Attempts to login the user.
        $view->user = $login->login($email);
        // Checks if a user has been returned from the login attempt.
        if (sizeof($view->user) === 1)
        {
            // Takes the user from the view.
            $user = $view->user;
            // Declares databasePassword as the password from the user object.
            $databasepassword = $user->getPassword();
            // Checks if the password entered matches the password stored for the user.
            if (password_verify($password, $databasepassword))
            {
                // Stores the user object in the session.
                $_SESSION['user'] = $user;
 		        $_SESSION['role'] = $user->getRole();
                $_SESSION['firstTimeLogin'] = $user->getFirstTimeLogin();

                //Check to see if it is the users first time logging in
                if ($user->getFirstTimeLogin()=='true'){
                    //If so take them to set password form
                    header('Location: setpassword.php');
                }else{
                    //Else Redirect the user to the index page.
                    header('Location: index.php');
                }
            }
            // If the password entered doesn't match the password stored for the user.
            else
            {
                // Sets the email error to reflect that the
                $view->errpassword = "<br><span class='text-danger errorMessage'> Your Password is Incorrect" . "</span>";
            }
        }
        // If a user has not been returned.
        else
        {
            // Sets the email error to reflect that the member does not exist.
            $view->erremail = "<br><span class='text-danger errorMessage'>You are not a Member" . "</span>";
        }
    }
}
require_once ('Views/login.phtml');
