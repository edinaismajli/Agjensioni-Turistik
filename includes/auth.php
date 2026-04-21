<?php

require_once 'session.php';
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

function aEshteKycur()
{
    if (isset($_SESSION['user_id'])) {
        return true;
    } else {
        return false;
    }
}

function merPerdoruesinAktual()
{
    if (aEshteKycur()) {
        if ($_SESSION['role'] == 'admin') {
            $user = new Admin($_SESSION['user_id'], $_SESSION['username'], $_SESSION['email'], $_SESSION['role']);
        } else {
            $user = new User($_SESSION['user_id'], $_SESSION['username'], $_SESSION['email'], $_SESSION['role']);
        }

        return $user;
    } else {
        return false;
    }
}

function kontrolloLogin()
{
    if (!aEshteKycur()) {
        header('Location: login.php');
        exit;
    }
}

function kontrolloRolin($role)
{
    kontrolloLogin();

    if ($_SESSION['role'] != $role) {
        header('Location: index.php');
        exit;
    }
}

?>
