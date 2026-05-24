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