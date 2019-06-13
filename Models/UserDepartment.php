<?php
/**
 * Created by PhpStorm.
 *
 */
class UserDepartment {

    protected $_useremail, $_departmentid;

    public function __construct($array) {
        $this->_useremail = $array['useremail'];
        $this->_departmentid = $array['departmentid'];
    }

    /**
     * @return mixed
     */
    public function getUserEmail()
    {
        return $this->_useremail;
    }

    /**
     * @return mixed
     */
    public function getDepartmentid()
    {
        return $this->_departmentid;
    }


}
