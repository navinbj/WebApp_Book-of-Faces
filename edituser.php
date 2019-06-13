<?php
session_start();

require_once ('Models/DepartmentDataSet.php');
require_once ('Models/UserDataSet.php');
require_once ('Models/UserDepartmentDataSet.php');

$image = "";

if ($_SESSION['role'] === 'user')
{
    header("Location: index.php");
}

$view = new stdClass();
$userDataSet = new UserDataSet();


$departmentDataSet = new DepartmentDataSet();
$view->departments = $departmentDataSet->getDepartments();

//create object for user_departments
$userDepartments = new UserDepartmentDataSet();

$view->err = "";
$view->errname = "";
$view->errage = "";
$view->errjob = "";
$view->errdepartment = "";
$view->errq1 = "";
$view->errq2 = "";
$view->errq3 = "";
$view->errq4 = "";
$view->errq5 = "";
$view->errUpload = "";

setcookie("current_page", $_SERVER['REQUEST_URI']);
if (isset($_COOKIE['current_page']))
{
    if (isset($_POST['editUserButton'])){
        $user = $userDataSet->getUser($_POST['editUserButton']);

        $view->id = $user->getUserid();
        $view->name = $user->getName();
        $view->age = $user->getAge();
        $view->jobTitle = $user->getJobTitle();
        $view->q1 = $user->getQ1();
        $view->q2 = $user->getQ2();
        $view->q3 = $user->getQ3();
        $view->q4 = $user->getQ4();
        $view->q5 = $user->getQ5();
        $view->image = $user->getImage();
        $_SESSION['image'] = $user->getImage();

        $email = $user->getEmail();

        //return an array of departments user is part of
        $view->userDepartments = $userDepartments->getUserDepartments($email);
    }
}
else
{
    header('Location: index.php');
}

//If the edit button is pressed
if (isset($_POST['editUser'])){
    

    $id = htmlentities($_POST['id'], ENT_QUOTES);
    $name = htmlentities($_POST['name'], ENT_QUOTES);
    $age = htmlentities($_POST['age'], ENT_QUOTES);
    $jobTitle = htmlentities($_POST['jobTitle'], ENT_QUOTES);

    $q1 = htmlentities($_POST['q1'], ENT_QUOTES);
    $q2 = htmlentities($_POST['q2'], ENT_QUOTES);
    $q3 = htmlentities($_POST['q3'], ENT_QUOTES);
    $q4 = htmlentities($_POST['q4'], ENT_QUOTES);
    $q5 = htmlentities($_POST['q5'], ENT_QUOTES);

    $view->name = $_POST['name'];
    $view-> age = $_POST['age'];
    $view->jobTitle = $_POST['jobTitle'];
    $view->q1 = $_POST['q1'];
    $view->q2 = $_POST['q2'];
    $view->q3 = $_POST['q3'];
    $view->q4 = $_POST['q4'];
    $view->q5 = $_POST['q5'];
    $view->image = $_SESSION['image'];
    $user_departments = array();
    foreach ($_POST['departments'] as $k=>$value)
    {
        array_push($user_departments, new UserDepartment($value));
    }
    $view->userDepartments = $user_departments;

    //get a user by its id
    $user = $userDataSet->getUser($id);

    //get the user email to delete all required fields
    $email = $user->getEmail();

    //return an array of departments available
    $departments = $_POST['departments'];



    $correctDetails = true;

    if (empty($name)) {
        $view->errname = "<br><span class='text-danger errorMessage'> Please enter a name" . "</span>";
        $correctDetails = false;
    }
    if (empty($age)) {
        $view->errage = "<br><span class='text-danger errorMessage'> Please enter an age" . "</span>";
        $correctDetails = false;
    }
    if (empty($jobTitle)) {
        $view->errjob = "<br><span class='text-danger errorMessage'> Please enter a job title" . "</span>";
        $correctDetails = false;
    }

    // Sets the filePath as the persons name followed by a random string to reduce the chance of duplicate file names.
    $filePath = 'images/profiles/' . str_replace(' ', '',$_POST['name']) . '_' . uniqid();
    // Gets the extension of the file uploaded.
    $fileType = pathinfo($_FILES['fileToUpload']['name'],PATHINFO_EXTENSION);

    $view->errUpload = "";

    // Sets the correct info to true by default.
    $isCorrectInfo = true;

    // Checks of the user has selected an image to upload.
    if (strlen($_FILES['fileToUpload']['tmp_name']) > 0) {
        // Checks if the file is an image.
        if (getimagesize($_FILES['fileToUpload']['tmp_name']) == false) {
            // Display error message.
            $view->errUpload = "<br><span class='text-danger errorMessage'> File is not an image" . "</span>";
            // Sets correct info to false so that the file is not uploaded.
            $isCorrectInfo = false;
        }
        // Checks if the correct info is true before uploading the file.
        if ($isCorrectInfo == true) {
            // Attempts to upload the file.
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $filePath . '.' . $fileType)) {
                unlink($_SESSION['image']);
            } else {
                // Displays an error message.
                $view->errUpload = "<br><span class='text-danger errorMessage'> Your image could not be uploaded." . "</span>";
            }
        }
    }

    // Checks if the user has not selected an image to upload.
    if (strlen($_FILES['fileToUpload']['tmp_name']) == 0) {
        // The file name to be added to the database if the admin has not selected one.
        $fileInDatabase = $_SESSION['image'];
    }
    else {
        // The file name is the one added by the user.
        $fileInDatabase = '/'. $filePath . '.' . $fileType;
    }

    /**
     * if departments is empty
     * set correctDetails to false
     */
    if (empty($departments))
    {
        $correctDetails = false;
        $view->errdepartment = "<br><span class='text-danger errorMessage'> Please select at least one department" . "</span>";
    }

    if ($isCorrectInfo == true && $correctDetails == true) {
        $result = $userDataSet->editUserDetails($id, $name, $age, $jobTitle, $q1, $q2, $q3, $q4, $q5, $fileInDatabase, $email, $departments);
        header('location:index.php');
    }
}
require_once('Views/edituser.phtml');