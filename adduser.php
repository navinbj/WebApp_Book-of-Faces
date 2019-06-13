<?php
require_once('login_check.php');

require_once ('Models/UserDataSet.php');
require_once ('Models/DepartmentDataSet.php');
require_once ('Models/Operations.php');

$view = new stdClass();
$userDataSet = new UserDataSet();
$operation = new Operations();

$departmentDataSet = new DepartmentDataSet();
$view->departments = $departmentDataSet->getDepartments();

$view->errname = "";
$view->errage = "";
$view->errjob = "";
$view->erremail = "";
$view->errdepartment = "";
$view->errq1 = "";
$view->errq2 = "";
$view->errq3 = "";
$view->errq4 = "";
$view->errq5 = "";

/*Get all data posted by user*/
if (isset($_POST['adduser']))
{
    $name = $_POST['name'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $job = $_POST['job'];
    //check when you get dylans code
    //returns an array
    $departments = $_POST['departments'];
    $q1 = $_POST['q1'];
    $q2 = $_POST['q2'];
    $q3 = $_POST['q3'];
    $q4 = $_POST['q4'];
    $q5 = $_POST['q5'];


    if (!empty($name))
    {
        $name = $operation->validate($name);
    }
    else
    {
        $view->errname = "<br><span class='text-danger errorMessage'> * Please enter your name." . "</span>";
    }

    if (!empty($age))
    {
        $age = $operation->validate($age);
    }
    else
    {
            $view->errage = "<br><span class='text-danger errorMessage'> * Please enter your age." . "</span>";
    }

    $emailBoolean = false;
    if (!empty($email))
    {
        $email = $operation->validate($email);
        // Can perform some proper mail validation

        //check if email is in database
        //return an number 1 or 0
        //1 = email in database
        //0 = email not in database
        $result = $userDataSet->checkEmail($email);

        if ($result === 0)
        {
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
                    $emailBoolean = true;
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
            $view->erremail = "<br><span class='text-danger errorMessage'> * Email already in use." . "</span>";
        }

    }
    else
    {
        $view->erremail = "<br><span class='text-danger errorMessage'> * Please enter your email." . "</span>";
    }

    if (!empty($job))
    {
        $job = $operation->validate($job);
    }
    else
    {
        $view->errjob = "<br><span class='text-danger errorMessage'> * Please enter your job title." . "</span>";
    }

    if (!empty($q1))
    {
        $q1 = $operation->validate($q1);
    }

    if (!empty($q2))
    {
        $q2 = $operation->validate($q2);
    }

    if (!empty($q3))
    {
        $q3 = $operation->validate($q3);
    }

    if (!empty($q4))
    {
        $q4 = $operation->validate($q4);
    }

    if (!empty($q5))
    {
        $q5 = $operation->validate($q5);
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
        // Checks if the file already exists.
        if (file_exists($filePath . $fileType)) {
            // Displays an error message.
            $view->errUpload = "<br><span class='text-danger errorMessage'> File already exists" . "</span>";
            // Sets correct info to false so that the file is not uploaded.
            $isCorrectInfo = false;
        }
        // Checks if the correct info is true before uploading the file.
        if ($isCorrectInfo == true) {
            // Attempts to upload the file.
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $filePath . '.' . $fileType)) {

            } else {
                // Displays an error message.
                $view->errUpload = "<br><span class='text-danger errorMessage'> Your image could not be uploaded." . "</span>";
            }
        }
    }

    // Checks if the user has not selected an image to upload.
    if (strlen($_FILES['fileToUpload']['tmp_name']) == 0) {
        // The file name to be added to the database if the admin has not selected one.
        $fileInDatabase = '/images/default_avatar.jpg';
    }
    else {
        // The file name is the one added by the user.
        $fileInDatabase = '/'. $filePath . '.' . $fileType;
    }

//    var_dump(strlen($_FILES['fileToUpload']['tmp_name']));

    //check if department is empty
    //then set boolean to false
    //and don't add to database
    if (empty($departments))
    {
        $view->errdepartment = "<br><span class='text-danger errorMessage'> * Please pick at least one department." . "</span>";
    }

    /*Once values are posted/ send to database*/
    if (!empty($name) && !empty($age) && $emailBoolean && !empty($job) && $isCorrectInfo == true && !empty($departments))
    {
        echo "we made it";
        $userDataSet->addUser($name, $age, $email, $job, $q1, $q2, $q3, $q4, $q5, $fileInDatabase, $departments);
    }

}

// Checks if a user has been added successfully.
if (isset($_POST['adduser']) && $isCorrectInfo == true && !empty($name) && !empty($age) && $emailBoolean && !empty($job) && !empty($departments)) {
    // Displays a confirmation message.
    echo '<script>window.alert("User added successfully!")</script>';
    header('Location: index.php');

}

if ($_SESSION['role'] == 'admin') {
    require_once('Views/adduser.phtml');
}
else {
    echo '<p class="text-danger pageError">Your account does not have access to this page!</p>';
    header('refresh: 2 ; index.php');
}