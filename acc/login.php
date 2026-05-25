<?php

session_start();
require_once("db.php");

$error = "";
$login = "";
$password = "";

if (isset($_COOKIE['last_user'])) {
    $login = $_COOKIE['last_user'];
}

if (isset($_POST['submit'])) {
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);

    if (empty($login) || empty($password)) {
        $error = "Ju lutem plotesoni te gjitha fushat.";
    } else {
        $usernameRegex = '/^[A-Za-z0-9_]{3,20}$/';
        $emailRegex = '/^[a-zA-Z0-9 _\-\.]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/';
        $passwordRegex = '/^[A-Za-z0-9]{5,20}$/';

        if (!preg_match($usernameRegex, $login) && !preg_match($emailRegex, $login)) {
            $error = "Shkruaj username ose email valid.";
        } elseif (!preg_match($passwordRegex, $password)) {
            $error = "Password duhet te kete 5-20 karaktere.";
        } else {
        $sql = "SELECT id, username, email, role, password FROM users WHERE username = ? OR email = ?";
        $stmt = mysqli_prepare($con, $sql);

        if (!$stmt) {
            $error = "Gabim ne prepare statement: " . mysqli_error($con);
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $login, $login);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_bind_result($stmt, $id, $usernameDb, $email, $role, $hashedPassword);

                if (mysqli_stmt_fetch($stmt)) {
                    if (password_verify($password, $hashedPassword)) {
                        $_SESSION['user_id'] = $id;
                        $_SESSION['username'] = $usernameDb;
                        $_SESSION['email'] = $email;
                        $_SESSION['role'] = $role;

                        setcookie('last_user', $usernameDb, time() + 86400 * 7, '/');

                        mysqli_stmt_close($stmt);
                        mysqli_close($con);

                        if ($role == 'admin') {
                            header("Location: admin-panel.php");
                            exit;
                        } else {
                            header("Location: ../frontend/index.php");
                            exit;
                        }
                    } else {
                        $error = "Username/email ose password gabim.";
                    }
                } else {
                    $error = "Username/email ose password gabim.";
                    }
                } else {
                    $error = "Gabim gjate login: " . mysqli_stmt_error($stmt);
                }

                mysqli_stmt_close($stmt);
            }
        }
    }

    mysqli_close($con);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../frontend/scss/registration.css">
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>

        <?php
        if (isset($_COOKIE['last_user'])) {
            echo "<p style='margin-top: 18px;'>Welcome back, " . htmlspecialchars($_COOKIE['last_user']) . ".</p>";
        }

        if ($error != "") {
            echo "<div class='errorMessage'>" . htmlspecialchars($error) . "</div>";
        }
        ?>

        <form method="post" action="login.php">
            <div class="input-box1">
                <input type="text" name="login" placeholder="Enter your username or email" value="<?php echo htmlspecialchars($login); ?>" required>
            </div>

            <div class="input-box2">
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="input-box button">
                <input type="submit" name="submit" value="Login Now">
            </div>

            <div class="text">
                <h3>Don't have an account? <a href="signup.php">Sign Up</a></h3>
            </div>
        </form>
    </div>
</body>
</html>
