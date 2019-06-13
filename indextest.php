<?php
//session_start();
require_once('login_check.php');

// Checks if the user has clicked on logout and redirects them to the login page if they have.
if (isset($_GET['logout'])) {
    // Destroys the session
    session_destroy();
    // Redirects the user to the login page.
    header('Location: login.php');
}

require_once ('Models/UserDataSet.php');
require_once ('Models/DepartmentDataSet.php');

$view = new stdClass();
$userDataSet = new UserDataSet();

$departmentDataSet = new DepartmentDataSet();
$view->departments = $departmentDataSet->getDepartments();
$view->err = "";



/* Danbro */
$view->users = "";
if (isset($_REQUEST['search']))
{
    $character = $_REQUEST['search'];
    $view->users = $userDataSet->searchUser($character);
}
else
{
    $view->users = $userDataSet->getAllUsers();
}
require_once('Views/testindex.phtml');


///**
// *Display all users
// */
//if (!isset($_REQUEST['search']) || empty($_REQUEST['search']))
//{
//    $view->users = $userDataSet->getAllUsers();
//    require_once('Views/testindex.phtml');
//}
//echo $_REQUEST['search'];
///**
// * if search bar is focused on first time
// */
//if (isset($_REQUEST['focus']))
//{
//    $view->users = $userDataSet->getAllUsers();
//}
