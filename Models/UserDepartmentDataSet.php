<?php
/**
 * Created by PhpStorm.
 */
require_once('Database.php');
require_once('UserDepartment.php');

class UserDepartmentDataSet
{
    protected $_dbHandle, $_dbInstance;


    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbHandle = $this->_dbInstance->getdbConnection();
    }

    /**
     * Gets all of the departments from the database.
     * @return array All of the user_department from the database.
     */
    public function getUserDepartments($email)
    {
        $sql = "SELECT * FROM user_department where useremail = '$email'";
        $statement = $this->_dbHandle->prepare($sql);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        $user_departments = array();
        foreach ($result as $k=>$value)
        {
            array_push($user_departments, new UserDepartment($value));
        }

        return $user_departments;

    }

}
