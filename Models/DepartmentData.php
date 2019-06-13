<?php
/**
 * Created by PhpStorm.
 * User: stc392
 * Date: 11/01/17
 * Time: 14:38
 *
 * The class DepartmentData stores all the information about a particular department retrieved from the database.
 */
class DepartmentData {

    protected $_id, $_name;

    public function __construct($dbRow) {
        $this->_id = $dbRow['id'];
        $this->_name = $dbRow['name'];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_name;
    }
}