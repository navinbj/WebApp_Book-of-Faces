<?php

require_once ('Models/UserDataSet.php');
require_once ('Models/DepartmentDataSet.php');

session_start();

$view = new stdClass();
$userDataSet = new UserDataSet();
$view->user = $_SESSION['user'];

if ($view->user->getFirstTimeLogin() == 'false') {
    header('Location: index.php');
}

$view->err = "";

//If the save password button is pressed
if (isset($_POST['updatePassword'])){
    //There is values in both password and confirmPassword
    if (!empty($_POST['password']) AND !empty($_POST['confirmPassword'])){

        //Both passwords match
        if($_POST['password'] == $_POST['confirmPassword'])
        {
            $user = $_SESSION['user'];
            $id = $user->getUserid();
            //If the user exists in the database
            //Update the users password
            $_SESSION['firstTimeLogin'] = 'false';
            $userDataSet->setUserPassword($id, $_POST['confirmPassword']);
            header('Location: index.php');
        }
        else
        {
            //Passwords do not match
            $view->err = ' <p class="error"> Passwords do not match, please try again. </p>';
        }
    }
}





require_once('Views/setpassword.phtml');