<?php
session_start();
require_once(__DIR__ . "/../frontend/db.php");

$error = "";
$login = "";
$loginType = $_POST["login_type"] ?? "user";

if (isset($_COOKIE["last_user"])) {
    $login = $_COOKIE["last_user"];
}

$staticAdmin = [
    "id" => 1,
    "username" => "admin",
    "email" => "admin@gmail.com",
    "password" => '$2y$10$LNBJnySgrnhJ0w1eHGyup.jzb68fiDyQf/Sc8OHizb..azGEo6Hby',
    "role" => "admin"
];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    $login = trim($_POST["login"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $loginType = $_POST["login_type"] ?? "user";

    if ($login === "" || $password === "") {
        $error = "Ju lutem plotesoni te gjitha fushat.";
    } elseif ($loginType === "admin") {
        $isStaticAdmin = (
            ($login === $staticAdmin["username"] || $login === $staticAdmin["email"]) &&
            password_verify($password, $staticAdmin["password"])
        );

        if ($isStaticAdmin) {
            $_SESSION["user_id"] = $staticAdmin["id"];
            $_SESSION["username"] = $staticAdmin["username"];
            $_SESSION["email"] = $staticAdmin["email"];
            $_SESSION["role"] = $staticAdmin["role"];

            setcookie("last_user", $staticAdmin["username"], time() + (86400 * 7), "/");

            header("Location: admin-panel.php");
            exit;
        } else {
            $error = "Te dhenat e adminit nuk jane te sakta.";
        }
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, email, password, role FROM users WHERE username = ? OR email = ? LIMIT 1");
            $stmt->execute([$login, $login]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && $user["role"] === "user" && password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["role"] = $user["role"];

                setcookie("last_user", $user["username"], time() + (86400 * 7), "/");

                header("Location: ../frontend/index.php");
                exit;
            }

            $error = "Username/email ose password gabim.";
        } catch (PDOException $e) {
            $error = "Gabim gjate login.";
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
    <div class="wrapper <?php echo $loginType === "admin" ? "admin-access" : ""; ?>">
        <h2><?php echo $loginType === "admin" ? "Admin Login" : "Login"; ?></h2>

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
            <div class="access-switch">
                <label>
                    <input type="radio" name="login_type" value="user" <?php echo $loginType === "user" ? "checked" : ""; ?>>
                    <span>User</span>
                </label>

                <label>
                    <input type="radio" name="login_type" value="admin" <?php echo $loginType === "admin" ? "checked" : ""; ?>>
                    <span>Admin</span>
                </label>
            </div>

            <?php if ($loginType === "admin") : ?>
            <p class="admin-note">Qasje e veçantë vetëm për administratorin.</p>
            <?php endif; ?>

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
