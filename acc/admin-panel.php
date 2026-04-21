<?php

require_once '../includes/session.php';
require_once '../classes/User.php';
require_once '../classes/Admin.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SESSION['role'] != 'admin') {
    header('Location: ../frontend/index.php');
    exit;
}

$admin = new Admin($_SESSION['user_id'], $_SESSION['username'], $_SESSION['email'], $_SESSION['role']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel</h1>

    <p>Welcome, <?php echo $admin->getUsername(); ?></p>

    <a href="logout.php">Logout</a>
</body>
</html>
