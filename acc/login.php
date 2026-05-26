<?php
session_start();

$error = "";
$login = "";

if (isset($_COOKIE["last_user"])) {
    $login = $_COOKIE["last_user"];
}

$usersFile = __DIR__ . "/../data/users.php";



if (!file_exists($usersFile)) {
    die("users.php nuk u gjet te data folder.");
}

require_once $usersFile;

if (!isset($users) || !is_array($users)) {
    die("Lista e users nuk u ngarkua.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $login = trim($_POST["login"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if ($login === "" || $password === "") {
        $error = "Ju lutem plotesoni te gjitha fushat.";
    } else {
        $loggedUser = null;

        foreach ($users as $user) {
            $userName = $user["username"] ?? "";
            $userEmail = $user["email"] ?? "";
            $userPassword = $user["password"] ?? "";

            $passwordIsValid = password_verify($password, $userPassword) || $password === $userPassword;

            if (($login === $userName || $login === $userEmail) && $passwordIsValid) {
                $loggedUser = $user;
                break;
            }
        }

        if ($loggedUser !== null) {
            $_SESSION["user_id"] = $loggedUser["id"];
            $_SESSION["username"] = $loggedUser["username"];
            $_SESSION["email"] = $loggedUser["email"];
            $_SESSION["role"] = $loggedUser["role"];

            setcookie("last_user", $loggedUser["username"], time() + (86400 * 7), "/");

            if ($loggedUser["role"] === "admin") {
                header("Location: ../acc/admin-panel.php");
                exit;
            }

            header("Location: ../frontend/index.php");
            exit;
        } else {
            $error = "Username/email ose password gabim.";
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
    <link rel="stylesheet" href="../frontend/scss/registration.css">
</head>

<body>
    <div class="wrapper">
        <h2>Login</h2>

        <?php if (isset($_COOKIE["last_user"])) : ?>
        <p style="margin-top: 18px;">
            Welcome back, <?php echo htmlspecialchars($_COOKIE["last_user"]); ?>.
        </p>
        <?php endif; ?>

        <?php if ($error !== "") : ?>
        <div class="errorMessage">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <form method="post" action="login.php">
            <div class="input-box1">
                <input type="text" name="login" placeholder="Enter your username or email"
                    value="<?php echo htmlspecialchars($login); ?>" required>
            </div>

            <div class="input-box2">
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="input-box button">
                <input type="submit" name="submit" value="Login Now">
            </div>
        </form>
    </div>
</body>

</html>