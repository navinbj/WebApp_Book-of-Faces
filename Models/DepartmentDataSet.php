<?php
/**
 * Created by PhpStorm.
 * User: stc392
 * Date: 11/01/17
 * Time: 14:38
 */
require_once('Database.php');
require_once('DepartmentData.php');

class DepartmentDataSet
{
    protected $_dbHandle, $_dbInstance;

    /**
     * DepartmentDataSet constructor.
     */
    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    /**
     * Gets all of the departments from the database.
     * @return array All of the departments from the database.
     */
    public function getDepartments() {
        $selectQuery = "SELECT * FROM department";

        $statement = $this->_dbHandle->prepare($selectQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];  //an array to hold the book information in a row.
        while ($row = $statement->fetch()) {
            $dataSet[] = new DepartmentData($row);
        }
        return $dataSet;
    }

    /**
     * The function for displaying all the users from database who work in the department specified in a parameter.
     * @param $dName
     * @return array
     */
    public function displayUsersFromDepartment($departmentName) {
        $selectQuery = "SELECT * FROM users WHERE departmentId = (SELECT departmentID FROM department WHERE name = '$departmentName'))";

        $statement = $this->_dbHandle->prepare($selectQuery); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement

        $dataSet = [];  //an array to hold the book information in a row.
        while ($row = $statement->fetch()) {
            $dataSet[] = new DepartmentData($row);
        }
        return $dataSet;
    }
}