<?php
require_once(__DIR__ . "/db.php"); 

$error = "";
$success = "";

$username = "";
$email = "";

if (isset($_POST['submit'])) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    $usernameRegex = '/^[A-Za-z0-9_]{3,20}$/';
    $emailRegex = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    $passwordRegex = '/^[A-Za-z0-9]{5,20}$/';

    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = "Ju lutem plotesoni te gjitha fushat.";
    } elseif (!preg_match($usernameRegex, $username)) {
        $error = "Username duhet te kete 3-20 karaktere.";
    } elseif (!preg_match($emailRegex, $email)) {
        $error = "Shkruaj email valid.";
    } elseif (!preg_match($passwordRegex, $password)) {
        $error = "Password duhet te kete 5-20 karaktere.";
    } elseif ($password !== $confirmPassword) {
        $error = "Password nuk perputhen.";
    } else {
        try {
$checkSql = "SELECT id FROM users WHERE username = :username OR email = :email";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->execute([
                ':username' => $username,
                ':email' => $email
            ]);

            if ($checkStmt->rowCount() > 0) {
                $error = "Ky username ose email ekziston.";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $role = "user";

                $sql = "INSERT INTO users (username, email, password, role) 
                        VALUES (:username, :email, :password, :role)";
                $stmt = $pdo->prepare($sql);

                if ($stmt->execute([
                    ':username' => $username,
                    ':email' => $email,
                    ':password' => $hashedPassword,
                    ':role' => $role
                ])) {
                    $success = "Llogaria u krijua me sukses.";
                    $username = "";
                    $email = "";
                } else {
                    $error = "Gabim gjate regjistrimit.";
                }
            }
        } catch (PDOException $e) {
            $error = "Gabim ne databaze: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <title>Sign Up</title>
    <link rel="stylesheet" href="../frontend/scss/registration.css">
</head>
<body>

<div class="wrapper">
    <h2>Sign Up</h2>

    <?php if ($error !== "") : ?>
        <div class="errorMessage" style="color:red;font-size:12px;margin-bottom:20px;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($success !== "") : ?>
        <div style="color:green;font-size:12px;margin-bottom:20px;">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="signup.php">
        <div class="input-box1">
            <input type="text" name="username" placeholder="Enter your username"
                   value="<?php echo htmlspecialchars($username); ?>" required>
        </div>

        <div class="input-box1">
            <input type="email" name="email" placeholder="Enter your email"
                   value="<?php echo htmlspecialchars($email); ?>" required>
        </div>

        <div class="input-box2">
            <input type="password" name="password" placeholder="Enter your password" required>
        </div>

        <div class="input-box2">
            <input type="password" name="confirm_password" placeholder="Confirm your password" required>
        </div>

        <div class="input-box button">
            <input type="submit" name="submit" value="Sign Up Now">
        </div>

        <div class="text">
            <h3>
                Already have an account?
                <a href="login.php">Login</a>
            </h3>
        </div>
    </form>
</div>

</body>
</html>
