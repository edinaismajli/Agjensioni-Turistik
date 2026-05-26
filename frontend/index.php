<?php

session_start();
require_once 'db.php';
require_once '../classes/Services.php';

function sanitize($data){
   return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

$filePath = __DIR__ . '/kerkesa_udhetareve.txt';
$fileMessage = '';
$fileText = '';
$databaseRequests = [];

if (!file_exists($filePath)) {
   file_put_contents($filePath, '');
}

$pdo->exec("
   CREATE TABLE IF NOT EXISTS travel_requests (
      id INT AUTO_INCREMENT PRIMARY KEY,
      request_text TEXT NOT NULL,
      source_file VARCHAR(120) NOT NULL,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file_form'])) {
   $fileText = trim($_POST['file_text'] ?? '');
   $fileAction = $_POST['file_action'] ?? 'append';

   if ($fileAction === 'clear') {
      file_put_contents($filePath, '');
      $pdo->exec("DELETE FROM travel_requests");
      $fileMessage = 'Lista e kërkesave u pastrua me sukses.';
   } elseif ($fileText === '') {
      $fileMessage = 'Shkruaj një kërkesë para se ta ruash.';
   } elseif ($fileAction === 'overwrite') {
      file_put_contents($filePath, $fileText . PHP_EOL);
      $pdo->exec("DELETE FROM travel_requests");
      $stmt = $pdo->prepare("INSERT INTO travel_requests (request_text, source_file) VALUES (?, ?)");
      $stmt->execute([$fileText, basename($filePath)]);
      $fileMessage = 'Lista e kërkesave u përditësua me sukses.';
   } else {
      $file = fopen($filePath, 'a');
      fwrite($file, date('d.m.Y H:i') . ' - ' . $fileText . PHP_EOL);
      fclose($file);
      $stmt = $pdo->prepare("INSERT INTO travel_requests (request_text, source_file) VALUES (?, ?)");
      $stmt->execute([$fileText, basename($filePath)]);
      $fileMessage = 'Kërkesa u ruajt me sukses.';
   }
}

$fileContent = file_get_contents($filePath);
$stmt = $pdo->query("SELECT request_text, source_file, created_at FROM travel_requests ORDER BY id DESC");
$databaseRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>



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
   <link rel="stylesheet" href="scss/styles.css?v=4">

</head>
<body>

<!-- pjesa e implementimit te navbar-it -->
 <?php include 'header.php'; ?>

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
                  <span><?= sanitize($slide['text']); ?></span>
                  <h3><?= sanitize($slide['title']); ?></h3>
                  <a href="<?= sanitize($slide['link']); ?>" class="btn">discover more</a>
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
    [   "id" => 1,
        "country" => "India",
        "price" => 949,
        "image" => "images/Arizona.jpg",
        "desc" => "Journey with us, where every moment becomes an unforgettable adventure."
    ],
    [   "id" => 2,
        "country" => "Switzerland",
        "price" => 799,
        "image" => "images/NewYork.avif",
        "desc" => "Journey with us, where every moment becomes an unforgettable adventure."
    ],
    [
        "id" => 3,
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
   <button class="btn sort-btn" data-order="asc">Lowest Price</button>
   <button class="btn sort-btn" data-order="desc">Highest Price</button>
</div>

   <div class="box-container">

      <?php foreach($packages as $index => $pkg): ?>
       <div class="box" id="package-<?= $index; ?>">

           <div class="image">
               <img src="<?= sanitize($pkg['image']); ?>" alt="Package Image">
            </div>
            <div class="content">
               <h3>
                  <?= sanitize($pkg['country']); ?>
                  <b>$<?= sanitize($pkg['price']); ?></b>
               </h3>
                <p>
                  <?= sanitize($pkg['desc']); ?>
               </p>

              <div class="button-group">

   <button
   class="btn book-btn"
   data-id="<?= sanitize($pkg['id']); ?>"
   type="button">
   Book Now
</button>

</div>

            </div>
         </div>
      <?php endforeach; ?>

   </div>

   <div class="load-more">
      <a href="package.php" class="btn">load more</a>
   </div>


</section>


<section class="travel-requests" id="file-tools">
   <div class="image">
      <img src="images/about-img.jpg" alt="">
   </div>

   <div class="content">
      <span class="request-label">Shërbim për klientët</span>
      <h3>Kërkesa speciale për udhëtim</h3>
      <p>Shëno kërkesa të klientëve për hotel, transport, ushqim ose destinacion.</p>

      <?php if ($fileMessage !== ''): ?>
         <p class="request-message"><?= sanitize($fileMessage); ?></p>
      <?php endif; ?>

      <form action="index.php#file-tools" method="post" class="request-form">
         <input type="hidden" name="file_form" value="1">

         <label for="file_text">Detajet e kërkesës</label>
         <textarea id="file_text" name="file_text" rows="5" placeholder="p.sh. Klienti kërkon dhomë me pamje nga deti dhe transport nga aeroporti..."><?= sanitize($fileText); ?></textarea>

         <label for="file_action">Veprimi me listën</label>
         <select id="file_action" name="file_action">
            <option value="append">Shto kërkesë të re</option>
            <option value="overwrite">Përditëso krejt listën</option>
            <option value="clear">Pastro listën</option>
         </select>

         <button type="submit" class="btn">Ruaj kërkesën</button>
      </form>

      <div class="saved-requests">
         <h4>Kërkesat e ruajtura në databazë</h4>
         <?php if (empty($databaseRequests)): ?>
            <p class="empty-requests">Ende nuk ka kërkesa të ruajtura.</p>
         <?php else: ?>
            <?php foreach ($databaseRequests as $request): ?>
               <div class="database-request">
                  <p><?= sanitize($request['request_text']); ?></p>
                  <span>
                     <?= sanitize(date('d.m.Y H:i', strtotime($request['created_at']))); ?>
                     nga <?= sanitize($request['source_file']); ?>
                  </span>
               </div>
            <?php endforeach; ?>
         <?php endif; ?>
      </div>
   </div>
</section>



<section class="home-offer">
   <div class="content">
      <h3>upto 50% off</h3>
      <a href="book.html" class="btn">book now</a>
   </div>
</section>



<!-- pjesa e implementimit te footer-it -->
 <?php include 'footer.php'; ?>



<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
<script src="js/script.js"></script>

<script>

document.querySelectorAll('.book-btn').forEach(button => {

   button.addEventListener('click', function () {

      const packageId = this.dataset.id;

      fetch('ajax/bookPackage.php', {
         method: 'POST',
         headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
         },
         body: 'package_id=' + encodeURIComponent(packageId)
      })
      .then(response => response.text())
      .then(data => {

          if (data.trim() === 'success') {
             window.location.href = 'book.html';
         } else {
            alert('Booking failed');
         }
      })
      .catch(error => {
         console.error(error);
      });
   });
   });

   document.querySelectorAll('.sort-btn').forEach(button => {
  button.addEventListener('click', function () {
    const order = this.dataset.order;

    fetch('ajax/sortPackages.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'order=' + order
    })
    .then(res => res.text())
    .then(html => {
      // rifresko vetëm container-in e paketave
      document.querySelector('.home-packages .box-container').innerHTML = html;
    })
    .catch(err => console.error(err));
  });
});

</script>

</body>
</html>
