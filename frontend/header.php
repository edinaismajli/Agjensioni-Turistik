<?php

$navItems = [
    ["name" => "Home", "link" => "index.php"],
    ["name" => "Package", "link" => "package.php"],
    ["name" => "Book", "link" => "book.php"],
    ["name" => "About", "link" => "about.php"],
    ["name" => "Logout", "link" => "../acc/logout.php", "class" => "logout"]
];

?>

<section class="header">

   <a href="index.php" class="logo">travel.</a>

   <nav class="navbar">

      <?php foreach($navItems as $item): ?>

         <a 
            href="<?= htmlspecialchars($item['link']); ?>" 
            class="<?= isset($item['class']) ? htmlspecialchars($item['class']) : ''; ?>">

            <?= htmlspecialchars($item['name']); ?>

         </a>

      <?php endforeach; ?>

   </nav>

   <div id="menu-btn" class="fas fa-bars"></div>

</section>