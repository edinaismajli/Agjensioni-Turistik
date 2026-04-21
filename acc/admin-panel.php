<?php

require_once '../includes/auth.php';

kontrolloRolin('admin');

$admin = merPerdoruesinAktual();

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
