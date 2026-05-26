<?php

$navItems = [
    ["name" => "Home", "link" => "index.php"],
    ["name" => "Package", "link" => "package.php"],
    ["name" => "Book", "link" => "book1.php"],
    ["name" => "About", "link" => "about.php"]
];

if (isset($_SESSION['user_id'])) {
    $navItems[] = ["name" => "Logout", "link" => "../acc/logout.php", "class" => "logout"];
} else {
    $navItems[] = ["name" => "Login", "link" => "../acc/login.php"];
    $navItems[] = ["name" => "Sign Up", "link" => "../acc/signup.php"];
}

?>

<section class="header">

    <a href="index.php" class="logo">travel.</a>

    <nav class="navbar">

        <?php foreach($navItems as $item): ?>

        <a href="<?= htmlspecialchars($item['link']); ?>"
            class="<?= isset($item['class']) ? htmlspecialchars($item['class']) : ''; ?>">

            <?= htmlspecialchars($item['name']); ?>

        </a>

        <?php endforeach; ?>

    </nav>

    <div id="menu-btn" class="fas fa-bars"></div>

</section>
