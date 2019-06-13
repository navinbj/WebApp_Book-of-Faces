<?php
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

/**
 * Display all users according to department
 */
if (isset($_GET['departmentId'])) {
    $departmentId = htmlentities($_GET['departmentId'], ENT_QUOTES);
    $userDataSet->setSqlQuery("SELECT * FROM user_department, users WHERE user_department.useremail = users.email AND departmentid = '$departmentId'");
}

$view->users = "";
if (isset($_REQUEST['search']))
{
    $character = $_REQUEST['search'];
    $view->users = $userDataSet->searchUser($character);
}
elseif (empty($_REQUEST['search']))
{
    $view->users = $userDataSet->getAllUsers();
}


/**
 * Admin delete option
 */
if (isset($_POST['deleteUser']))
{
    $id = $_POST['deleteUser'];
    $userDataSet->removeUser($id);
    header('Refresh: 0');
}


require_once('Views/index.phtml');
