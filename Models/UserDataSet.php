<?php

require_once ('User.php');
require_once ('Database.php');
require_once ('Mail.php');

class UserDataSet
{
    protected $_dbHandle, $_dbInstance;
    private $sqlQuery;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
        $this->sqlQuery = "SELECT * FROM users";
    }

    /**
     * @param string $sqlQuery
     */
    public function setSqlQuery($sqlQuery)
    {
        $this->sqlQuery = $sqlQuery;
    }

    public function getAllUsers(){
        $statement = $this->_dbHandle->prepare($this->sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
        $dataSet = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $dataSet[] = new User($row);
        }

        return $dataSet;
    }


    /**
     * Written by Daniel Brown
     * @param adding user to the database
     */
    public function addUser($name, $age, $email, $job, $q1, $q2, $q3, $q4, $q5, $fileInDatabase, $departments)
    {
        // generate random password.
        $tempPassword = uniqid();
        // Hash the random password.
        $databasePassword = password_hash($tempPassword, PASSWORD_DEFAULT);

        $sqlQuery = "INSERT INTO users 
        (name, age, email, jobTitle, q1, q2, q3, q4, q5, password, image, firstTimeLogin) 
        VALUES ('$name', '$age', '$email', '$job', '$q1', '$q2', '$q3', '$q4', '$q5', '$databasePassword', '$fileInDatabase', 'true')";
        $this->_dbHandle->exec($sqlQuery);

        //Add departments
        $this->addDepartments($email, $departments);

        $emailMessage = "
        <html>
            <body>
                <h3>Your new Book of Faces account!</h3>
                <hr>
                <br>
                <p>Welcome ". $name .", as a Competa employee you have been granted access to a new Book of Faces
                account. Book of Faces is an application where you can view all of the current staff working at
                Competa. We hope that you will find great use from this application!</p>
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


 /**
     * Written by Arslaan Qadus
     * Editing user details
     * @param name,age,jotTitle, q1,q2,q3,q4,q5
     */
    public function editUserDetails($id, $name, $age, $jobTitle, $q1, $q2, $q3, $q4, $q5, $image, $email, $departments){
        $this->setSqlQuery("UPDATE users SET `name` = '$name', age = $age, jobTitle = '$jobTitle', q1 = '$q1', q2 = '$q2', q3 = '$q3', q4 = '$q4', q5 = '$q5', token = '', image = '$image' WHERE id = '$id'");
        $statement = $this->_dbHandle->prepare($this->sqlQuery); // prepare a PDO statement
        $result = $statement->execute(); // execute the PDO statement

        //Delete all entry of user email row from database with department
        //Delete first
        $this->deleteUserDepartments($email);
        //Add second
        $this->addDepartments($email, $departments);
        return $result;
    }


 //Arslaans function
    public function getUser($id){
        $this->setSqlQuery("SELECT * FROM users WHERE id = $id");;
        $statement = $this->_dbHandle->prepare($this->sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
        $row = new User($statement->fetch(PDO::FETCH_ASSOC));
        return $row;
    }
    //Arslaans function

    public function getUserByEmail($email){
        $this->setSqlQuery("SELECT * FROM users WHERE email = '$email'");
        $statement = $this->_dbHandle->prepare($this->sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
        $user = new User($statement->fetch(PDO::FETCH_ASSOC));
        return $user;
    }


    //Arslaans function
    public function setUserPassword($id, $password){
        $hashedPass = password_hash($password, PASSWORD_DEFAULT);
        $this->setSqlQuery("UPDATE users SET password='$hashedPass', firstTimeLogin='false' WHERE id='$id'");
        $statement = $this->_dbHandle->prepare($this->sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
    }
    //Arslaans function


//Arslaans function
    public function updateUserPassword($id, $password){
        $hashedPass = password_hash($password, PASSWORD_DEFAULT);
        $this->setSqlQuery("UPDATE users SET password='$hashedPass', token='', firstTimeLogin='false' WHERE id='$id'");
        $statement = $this->_dbHandle->prepare($this->sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
    }
    //Arslaans function

    public function checkEmail($email)
    {

        $sql = "SELECT * FROM users WHERE email = '$email'";

        // Prepares the statement to be executed.
        $statement = $this->_dbHandle->prepare($sql);
        // Executes the statement/SQL query.
        $statement->execute();
        // Gets the results for the SQL query.
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        return sizeof($result);
    }

    public function removeUser($id){
        $this->setSqlQuery("DELETE FROM users WHERE id='$id'");
        $statement = $this->_dbHandle->prepare($this->sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
    }

   /**
     * Search users on database
     * @param $characters
     * @return array
     */
    public function searchUser($characters)
    {
        $sqlQuery = "select * from users where name like '%$characters%'";

        $statement = $this->_dbHandle->prepare($sqlQuery);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $users = array();
        foreach ($result as $k=>$value)
        {
            array_push($users, new User($value));
        }
        return $users;
    }

/**
     * Generates a link for when a user has forgotten his password
     * @param string $email The users email address.
     * @return user
     */
    public function forgotPassword($user)
    {
        //The users id
        $id = $user->getUserid();
        //Generate random token
        $token = uniqid();
        // Hash the token
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
        //Set the hashed token for the user in the database
        $this->setSqlQuery("UPDATE users SET token='$hashedToken' WHERE id='$id'");
        $statement = $this->_dbHandle->prepare($this->sqlQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
        //Create the reset password link
        $resetPasswordLink = 'http://'.$_SERVER['HTTP_HOST'].'/updatepassword.php?token='.$token.'&id='.$user->getUserid();

        $emailMessage = "
        <html>
            <body>
                <h3>Forgot your password!</h3>
                <hr>
                <br>
                <p>Hi ". $user->getName() ."!, press the link below to reset your password!</p>
                <br>
                <p style='font-weight: bold'><a href='$resetPasswordLink'>Reset Password!</a></p>
                
                <br>
                <p style='font-size: 12px; color: red'>* If you haven't forgot your password then please click <a href=''>here</a></p>
            </body>
        </html>
        ";

        $mailer = new Mail($user->getEmail(), 'Forgot your password!', $emailMessage);
        $mailer->sendMail();
    }

 //add user email and departments to database
    //Daniel
    private function addDepartments($email, $departments)
    {
        foreach ($departments as $departmentid)
        {
           $sqlQuery = "INSERT INTO user_department (useremail, departmentid) VALUES ('$email', $departmentid)";
           $this->_dbHandle->exec($sqlQuery);
        }
    }
    //add user email and departments to database
    //Daniel

    /**
     * Delete user departments
     * @param $email
     */
    private function deleteUserDepartments($email)
    {
       $sqlQuery = "DELETE FROM user_department WHERE useremail = '$email'";
        $this->_dbHandle->exec($sqlQuery);
    }
}
