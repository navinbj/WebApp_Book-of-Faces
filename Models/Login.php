<?php

/**
 * Created by PhpStorm.
 * User: danbro
 * Date: 11/01/17
 * Time: 11:55
 */


require_once("User.php");
require_once("Database.php");
class Login
{
    protected $_dbHandler, $_dbInstance;

    /**
     * Login constructor.
     */
    public function __construct()
    {
        //get the db object created in static class
        $this->_dbInstance = Database::getInstance();
        //use database object to return the dbhandler
        $this->_dbHandler = $this->_dbInstance->getdbConnection();
    }

    /**
     * Logs a user into the system.
     * @param string $email The users email address.
     * @return user
     */
    public function login($email)
    {
        // Sets the SQL query to be executed.
        $sql = "SELECT * FROM users WHERE email = '$email'";

        // Prepares the statement to be executed.
        $statement = $this->_dbHandler->prepare($sql);
        // Executes the statement/SQL query.
        $statement->execute();
        // Gets the results for the SQL query.
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        $user = new User($result);
        return $user;
    }
}