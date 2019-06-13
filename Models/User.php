<?php

/**
 * Created by PhpStorm.
 * User: danbro
 * Date: 11/01/17
 * Time: 12:34
 */
class User
{
    protected $_userid, $_name, $_email, $_password, $_age, $_jobTitle;
    Protected $_role, $_q1, $_q2, $_q3, $_q4, $_q5, $_token, $_image, $_firstTimeLogin;

    /**
     * User constructor.
     * @param array $userArray The row returned from the SQL query containing the values for the fields.
     */
    public function __construct($userArray)
    {
        $this->_userid = $userArray['id'];
        $this->_name = $userArray['name'];
        $this->_email = $userArray['email'];
        $this->_password = $userArray['password'];
        $this->_age = $userArray['age'];
        $this->_jobTitle = $userArray['jobTitle'];
        $this->_role = $userArray['role'];
        $this->_q1 = $userArray['q1'];
        $this->_q2 = $userArray['q2'];
        $this->_q3 = $userArray['q3'];
        $this->_q4 = $userArray['q4'];
        $this->_q5 = $userArray['q5'];
        $this->_token = $userArray['token'];
        $this->_image = $userArray['image'];
        $this->_firstTimeLogin = $userArray['firstTimeLogin'];
    }

    /**
     * Gets the users password.
     * @return string The users password.
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * Gets the users ID.
     * @return string The users ID.
     */
    public function getUserid()
    {
        return $this->_userid;
    }

    /**
     * Gets the users name.
     * @return string The users name.
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Gets the users email address.
     * @return string The users email address.
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * Gets the users age.
     * @return int The users age.
     */
    public function getAge()
    {
        return $this->_age;
    }

    /**
     * Gets the users job title.
     * @return string The users job title.
     */
    public function getJobTitle()
    {
        return $this->_jobTitle;
    }

    /**
     * Gets the users role i.e. Admin, Staff Member etc.
     * @return string The users role.
     */
    public function getRole()
    {
        return $this->_role;
    }

    /**
     * Gets the users answer to the first question.
     * @return string The users answer to the first question.
     */
    public function getQ1()
    {
        return $this->_q1;
    }

    /**
     * Gets the users answer to the second question.
     * @return string The users answer to the second question.
     */
    public function getQ2()
    {
        return $this->_q2;
    }

    /**
     * Gets the users answer to the third question.
     * @return string The users answer to the third question.
     */
    public function getQ3()
    {
        return $this->_q3;
    }

    /**
     * Gets the users answer to the fourth question.
     * @return string The users answer to the fourth question.
     */
    public function getQ4()
    {
        return $this->_q4;
    }

    /**
     * Gets the users answer to the fifth question.
     * @return string The users answer to the fifth question.
     */
    public function getQ5()
    {
        return $this->_q5;
    }

    /**
     * Gets the users password reset token.
     * @return string The users password reset token.
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * Gets the path to image of the user.
     * @return string The path to the image of the user.
     */
    public function getImage()
    {
        return $this->_image;
    }

    /**
     * Gets the answer as to whether this is the users first time logging in
     * @return bool The users first time logging in.
     */
    public function getFirstTimeLogin()
    {
        return $this->_firstTimeLogin;
    }
}
