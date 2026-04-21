<?php

require_once 'includes/auth.php';

$error = '';
$login = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $login = trim($_POST['login']);
    }

    if (isset($_POST['password'])) {
        $password = trim($_POST['password']);
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
