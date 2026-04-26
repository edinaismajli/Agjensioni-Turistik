<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home - Travel Agency Website</title>
   <link rel="icon" type="image/x-icon" href="images/favicon.png">
   <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
   <link rel="stylesheet" href="scss/styles.css">

</head>
<body>

<?php

//realizimi i pjeses se MENU-se duke implementuar vargjet asociative shumedimensioanle ne PHP

$navItems = [
    ["name" => "Home", "link" => "index.php"],
    ["name" => "Package", "link" => "package.php"],
    ["name" => "Book", "link" => "book.html"],
    ["name" => "About", "link" => "about.html"],
    ["name" => "Logout", "link" => "../acc/logout.php", "class" => "logout"]
];

?>

<section class="header">
   <a href="index.php" class="logo">travel.</a>

   <nav class="navbar">
      <?php foreach($navItems as $item): ?>
         <a 
            href="<?php echo $item['link']; ?>" 
            class="<?php echo isset($item['class']) ? $item['class'] : ''; ?>">
            <?php echo $item['name']; ?>
         </a>
      <?php endforeach; ?>
   </nav>

   <div id="menu-btn" class="fas fa-bars"></div>
</section>


<?php

//paraqitja e pjeses se sliderit duke e trajtuar si varg shumedimensional asociativ ne PHP

$slides = [
    [
        "image" => "images/home-slide2.jpg",
        "title" => "travel arround the world",
        "text" => "explore, discover, travel",
        "link" => "package.php"
    ],
    [
        "image" => "images/home-slide1.jpg",
        "title" => "discover the new places",
        "text" => "explore, discover, travel",
        "link" => "package.php"
    ],
    [
        "image" => "images/home-slide3.jpg",
        "title" => "make your tour worthwhile",
        "text" => "explore, discover, travel",
        "link" => "package.php"
    ]
];

?>
<section class="home">

   <div class="swiper home-slider">

      <div class="swiper-wrapper">

         <?php foreach($slides as $slide): ?>
            <div class="swiper-slide slide" style="background:url(<?php echo $slide['image']; ?>) no-repeat">
               <div class="content">
                  <span><?php echo $slide['text']; ?></span>
                  <h3><?php echo $slide['title']; ?></h3>
                  <a href="<?php echo $slide['link']; ?>" class="btn">discover more</a>
               </div>
            </div>
         <?php endforeach; ?>
         
      </div>

      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>

   </div>

</section>

<?php

//pjesa e implementimit te sherbimeve duke krijuar nje klase Service ne PHP dhe duke e trajtuar si objekt te klases Service

require_once "../classes/Services.php";

$services = [
    new Service("images/icon-1.png", "adventure"),
    new Service("images/icon-2.png", "tour guide"),
    new Service("images/icon-3.png", "trekking"),
    new Service("images/icon-4.png", "camp fire"),
    new Service("images/icon-5.png", "off road"),
    new Service("images/icon-6.png", "camping")
];
?>

<section class="services">

   <h1 class="heading-title"> our services </h1>

   <div class="box-container">

      <?php
      foreach($services as $service) {
          echo $service->render();
      }
      ?>

   </div>

</section>

<section class="home-about">

   <div class="image">
      <img src="images/about-img.jpg" alt="">
   </div>

   <div class="content">
      <h3>about us</h3>
      <p>Welcome to our vibrant world of travel and exploration! At Travel. , we are passionate about crafting unforgettable journeys that unveil the beauty of diverse destinations. With a commitment to excellence, we invite you to embark on a personalized adventure, where each moment is a story waiting to be discovered.</p>
      <a href="about.html" class="btn">read more</a>
   </div>

</section>

<?php

//perdorimi i funksionit usort ne PHP per te renditur paketat turistike bazuar ne cmim, duke 

$packages = [
    [
        "country" => "India",
        "price" => 949,
        "image" => "images/Arizona.jpg",
        "desc" => "Journey with us, where every moment becomes an unforgettable adventure."
    ],
    [
        "country" => "Switzerland",
        "price" => 799,
        "image" => "images/NewYork.avif",
        "desc" => "Journey with us, where every moment becomes an unforgettable adventure."
    ],
    [
        "country" => "Latvia",
        "price" => 699,
        "image" => "images/egjipt.jpg",
        "desc" => "Journey with us, where every moment becomes an unforgettable adventure."
    ]
];

$order = $_GET['sort'] ?? 'asc';

usort($packages, function($a, $b) use ($order) {
    return $order === 'asc'
        ? $a['price'] <=> $b['price']
        : $b['price'] <=> $a['price'];
});

?>


<section class="home-packages">

   <h1 class="heading-title"> our packages </h1>

   <div style="text-align:center; margin:20px;">
   <a href="?sort=asc" class="btn">Lowest Price</a>
   <a href="?sort=desc" class="btn">Highest Price</a>
</div>

   <div class="box-container">

      <?php foreach($packages as $pkg): ?>
         <div class="box">
            <div class="image">
               <img src="<?php echo $pkg['image']; ?>" alt="">
            </div>
            <div class="content">
               <h3>
                  <?php echo $pkg['country']; ?>
                  <b> <?php echo $pkg['price']; ?>$</b>
               </h3>
               <p><?php echo $pkg['desc']; ?></p>
               <a href="book.php" class="btn">book now</a>
            </div>
         </div>
      <?php endforeach; ?>

   </div>

   <div class="load-more">
      <a href="package.php" class="btn">load more</a>
   </div>

</section>





<section class="home-offer">
   <div class="content">
      <h3>upto 50% off</h3>
      <a href="book.html" class="btn">book now</a>
   </div>
</section>




<?php

//realizimi i pjeses se footer-it duke e trajtuar si varg shumedimensional asociativ ne PHP

$footer = [
    "quick_links" => [
        ["name" => "Home", "link" => "index.php"],
        ["name" => "Package", "link" => "package.php"],
        ["name" => "Book", "link" => "book.php"],
        ["name" => "About", "link" => "about.php"]
    ],

    "contact" => [
        ["icon" => "fas fa-phone", "text" => "0092-301-9583959"],
        ["icon" => "fas fa-phone", "text" => "0092-301-5273527"],
        ["icon" => "fas fa-envelope", "text" => "support@travelagency.com"],
        ["icon" => "fas fa-map", "text" => "Islamabad, Pakistan - 46000"]
    ],

    "social" => [
        ["icon" => "fab fa-facebook-f", "name" => "facebook"],
        ["icon" => "fab fa-twitter", "name" => "twitter"],
        ["icon" => "fab fa-instagram", "name" => "instagram"],
        ["icon" => "fab fa-linkedin", "name" => "linkedin"]
    ]
];

?>

<section class="footer">

   <div class="box-container">

      <div class="box">
         <h3>quick links</h3>
         <?php foreach($footer['quick_links'] as $link): ?>
            <a href="<?php echo $link['link']; ?>">
               <i class="fas fa-angle-right"></i> <?php echo $link['name']; ?>
            </a>
         <?php endforeach; ?>
      </div>

      <div class="box">
         <h3>contact info</h3>
         <?php foreach($footer['contact'] as $item): ?>
            <a href="#">
               <i class="<?php echo $item['icon']; ?>"></i> <?php echo $item['text']; ?>
            </a>
         <?php endforeach; ?>
      </div>

      <div class="box">
         <h3>follow us</h3>
         <?php foreach($footer['social'] as $social): ?>
            <a href="#">
               <i class="<?php echo $social['icon']; ?>"></i> <?php echo $social['name']; ?>
            </a>
         <?php endforeach; ?>
      </div>

   </div>

   <div class="credit">
      created by <span>Kashif Abbas Kazmi & Muhammad Sarim</span> | all rights reserved!
   </div>

</section>

<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
<script src="js/script.js"></script>

</body>
</html>