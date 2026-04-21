<?php

class User
{
    private $id;
    private $username;
    private $email;
    private $role;

    function __construct($id, $username, $email, $role)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->role = $role;
    }

    function getId()
    {
        return $this->id;
    }

    function getUsername()
    {
        return $this->username;
    }

    function setUsername($username)
    {
        $this->username = $username;
    }

    function getEmail()
    {
        return $this->email;
    }

    function setEmail($email)
    {
        $this->email = $email;
    }

    function getRole()
    {
        return $this->role;
    }
}

?>
