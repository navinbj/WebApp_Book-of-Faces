<?php

/**
 * Created by PhpStorm.
 * User: dylan
 * Date: 07/01/17
 * Time: 17:53
 */
class Mail
{
    private $to;
    private $subject;
    private $message;
    private $headers;

    /**
     * Creates a mail object used to send email
     * Mail constructor.
     * @param string $to The email address of who the email should be sent to.
     * @param string $subject The subject of the email to send.
     * @param string $message The HTML message to send.
     */
    public function __construct($to, $subject, $message) {
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
        $this->headers = [];
        $this->headers[] = 'MIME-Version: 1.0';
        $this->headers[] = 'Content-type: text/html; charset=iso-8859-1';
    }

    /**
     * Returns the recipient of the email.
     * @return string The recipient of the email.
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Returns the subject of the email.
     * @return string The subject of the email.
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Returns the message for the email.
     * @return string The message for the email.
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the recipient of the email.
     * @param string $to The recipient of the email.
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * Set the subject of the email.
     * @param string $subject The subject of the email.
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * Set the message for the email.
     * @param string $message The message to be sent.
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }


    /**
     * Sends an email using the to, subject and message provided.
     */
    public function sendMail() {
        mail($this->to, $this->subject, $this->message, implode("\r\n", $this->headers));
    }



}