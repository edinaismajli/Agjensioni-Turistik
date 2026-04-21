<?php

require_once 'includes/auth.php';

$error = '';
$login = '';
$password = '';

if (isset($_COOKIE['last_user'])) {
    $login = $_COOKIE['last_user'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $login = trim($_POST['login']);
    }

    if (isset($_POST['password'])) {
        $password = trim($_POST['password']);
    }

    $usernameRegex = '/^[A-Za-z0-9_]{3,20}$/';
    $emailRegex = '/^[a-zA-Z0-9 _\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/';
    $passwordRegex = '/^[A-Za-z0-9]{5,20}$/';

    if (!preg_match($usernameRegex, $login) && !preg_match($emailRegex, $login)) {
        $error = 'Shkruaj username ose email valid.';
    } elseif (!preg_match($passwordRegex, $password)) {
        $error = 'Password duhet te kete 5-20 karaktere.';
    } else {
        $user = gjejPerdoruesin($login, $password);

        if ($user == false) {
            $error = 'Username/email ose password gabim.';
        } else {
            ruajSession($user);

            if ($user['role'] == 'admin') {
                header('Location: admin-panel.php');
                exit;
            } else {
                header('Location: index.php');
                exit;
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="scss/registration.css">
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>

        <?php
        if (isset($_COOKIE['last_user'])) {
            echo "<p style='margin-top: 18px;'>Welcome back, " . $_COOKIE['last_user'] . ".</p>";
        }

        if ($error != '') {
            echo "<div class='errorMessage'>" . $error . "</div>";
        }
        ?>

        <form method="post" action="login.php">
            <div class="input-box1">
                <input type="text" name="login" placeholder="Enter your username or email" value="<?php echo $login; ?>" required>
            </div>

            <div class="input-box2">
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="input-box button">
                <input type="submit" value="Login Now">
            </div>
        </form>
    </div>
</body>
</html>
