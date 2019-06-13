<?php
/**
 * Created by PhpStorm.
 * User: Arslaan
 * Date: 19/01/17
 * Time: 11:15
 */

require_once ('Models/Operations.php');
require_once ('Models/Login.php');
require_once ('Models/UserDataSet.php');

// Creates a new stdClass object for the view.
$view = new stdClass();
// Creates a new operations object to validate the data.
$operations = new Operations();

$userDataSet = new UserDataSet();
$login = new Login();

$view->erremail = "";
$view->linkSent = "";
// Checks if the login button has been pressed.
if (isset($_POST['forgotPassword']))
{
    // Declares email as the email entered in the forgot password form.
    $email = $_POST['typedEmail'];

    // Checks if email not empty
    if (!empty($email))
    {
        // Validates the data for security reasons.
        $email = $operations->validate($email);

        /*If email format is valid enter if statement*/
        if (filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $email = strtolower($email);
            //find position of the '@' sign
            $eSignPosition = strpos($email, '@');
            //strip out email domain
            $emailDomain = substr($email, $eSignPosition);
            if ($emailDomain === "@competa.com")
            {
                //check if email is in database
                //return a number 1 or 0
                //1 = email in database
                //0 = email not in database
                $result = $userDataSet->checkEmail($email);
                $user = $login->login($email);

                //If a user was returned and he exists in the database
                if ($result === 1)
                {
                    $userDataSet->forgotPassword($user);
                    $view->linkSent = "<div class='alert alert-success'> * Please check your email for a reset password link!" . "</div>";
                    header('refresh:2; login.php');
                }
                else
                {
                    $view->erremail = "<br><span class='text-danger errorMessage'> * Not a Competa staff?" . "</span>";
                }
            }
            else
            {
                $view->erremail = "<br><span class='text-danger errorMessage'> * Please enter a '@competa.com' email." . "</span>";
            }
        }
        else
        {
            $view->erremail = "<br><span class='text-danger errorMessage'> * Not a valid email address." . "</span>";

        }
    }
    else
    {
        // Sets the email error message to reflect that the user has not entered an email address.
        $view->erremail = "<br><span class='text-danger errorMessage'> * Please enter your email address." . "</span>";
    }
    require_once ('Views/forgotpassword.phtml');
}

if (isset($_GET['token']) AND isset($_GET['id'])){

    //Get the user wanting to reset his password
    $user = $userDataSet->getUser($_GET['id']);


    //If the tokens match then let the user reset his password
    if (password_verify($_GET['token'], $user->getToken())){
        //Set the user
        $view->user = $user;

        //Let the user set his new password
        require_once ('Views/setpassword.phtml');
    }else{
        require_once ('Views/forgotpassword.phtml');
    }
}

//If the Update password button is pressed
if (isset($_POST['updatePassword'])){
    //There is values in both password and confirmPassword
    if (!empty($_POST['password']) AND !empty($_POST['confirmPassword'])){

        //Both passwords match
        if($_POST['password'] == $_POST['confirmPassword'])
        {
            $confirmPassword = $_POST['confirmPassword'];
            $confirmPassword = $operations->validate($confirmPassword);
            //The users id
            $id = $_POST['userId'];
            //Update the users password
            $userDataSet->updateUserPassword($id, $confirmPassword);
            $view->err = '<p class="error"> Password Updated! </p>';
            header('refresh:2; index.php');
        }
        else
        {
            //Passwords do not match
            $view->err = ' <p class="error"> Passwords do not match, please try again. </p>';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    require_once ('Views/forgotpassword.phtml');
}
