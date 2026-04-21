<?php

require_once '../includes/session.php';
require_once '../classes/User.php';
require_once '../classes/Admin.php';
require_once '../data/users.php';

function gjejPerdoruesin($login, $password)
{
    global $users;

    foreach ($users as $user) {
        if (($user['username'] == $login || $user['email'] == $login) && $user['password'] == $password) {
            return $user;
        }
    }

    return false;
}

function ruajSession($user)
{
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

    setcookie('last_user', $user['username'], time() + 86400 * 7, '/');
}

?>
