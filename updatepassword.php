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

if (isset($_GET['token']) && isset($_GET['id'])){
    //Get the user wanting to reset his password
    $user = $userDataSet->getUser(htmlentities($_GET['id'], ENT_QUOTES));
    $view->name = $user->getName();
    $view->id = $user->getUserid();
    $token = $user->getToken();

    //If the tokens match then let the user reset his password
    if (password_verify($_GET['token'], $token)){
        //Set the user
        $view->user = $user;
        //Let the user set his new password
        require_once ('Views/updatepassword.phtml');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    //There is values in both password and confirmPassword
    if (!empty($_POST['password']) AND !empty($_POST['confirmPassword'])){

        //Both passwords match
        if($_POST['password'] == $_POST['confirmPassword'])
        {
            //The users id
            $id = htmlentities($_POST['userId'], ENT_QUOTES);
            $confirmedPassword = htmlentities($_POST['confirmPassword'], ENT_QUOTES);
            //Update the users password
            $userDataSet->updateUserPassword($id, $confirmedPassword);
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

if ($_SERVER['REQUEST_METHOD'] === 'GET' AND !isset($_GET['token'])){
    require_once ('Views/forgotpassword.phtml');
}