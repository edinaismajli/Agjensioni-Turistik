<?php

session_start();
require_once 'db.php';
require_once '../classes/Services.php';

function sanitize($data){
   return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function getUserNameColumn($pdo) {
   $columns = $pdo->query("SHOW COLUMNS FROM users")->fetchAll(PDO::FETCH_COLUMN);

   if (in_array('username', $columns)) {
      return 'username';
   }

   if (in_array('name', $columns)) {
      return 'name';
   }

   return 'email';
}

$requestsFolder = __DIR__ . '/kerkesat';
$fileMessage = '';
$fileText = '';
$requestUser = null;

if (!is_dir($requestsFolder)) {
   mkdir($requestsFolder, 0777, true);
}

$pdo->exec("
   CREATE TABLE IF NOT EXISTS travel_requests (
      id INT AUTO_INCREMENT PRIMARY KEY,
      request_text TEXT NOT NULL,
      source_file VARCHAR(120) NOT NULL,
      user_id INT NULL,
      username VARCHAR(80) NOT NULL DEFAULT 'Guest',
      user_email VARCHAR(120) NOT NULL DEFAULT 'guest@local',
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

foreach ([
   "ALTER TABLE travel_requests ADD COLUMN user_id INT NULL",
   "ALTER TABLE travel_requests ADD COLUMN username VARCHAR(80) NOT NULL DEFAULT 'Guest'",
   "ALTER TABLE travel_requests ADD COLUMN user_email VARCHAR(120) NOT NULL DEFAULT 'guest@local'"
] as $alterSql) {
   try {
      $pdo->exec($alterSql);
   } catch (PDOException $e) {
      // Column already exists.
   }
}

if (isset($_SESSION['user_id'])) {
   $userNameColumn = getUserNameColumn($pdo);
   $stmt = $pdo->prepare("SELECT id, $userNameColumn AS username, email FROM users WHERE id = ? LIMIT 1");
   $stmt->execute([$_SESSION['user_id']]);
   $requestUser = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file_form'])) {
   $fileText = trim($_POST['file_text'] ?? '');
   $isAjaxRequest = isset($_POST['ajax_request']);

   if ($fileText === '') {
      $fileMessage = 'Shkruaj një kërkesë para se ta ruash.';
   } elseif (!$requestUser) {
      $fileMessage = 'Duhet të kyçesh si user para se të dërgosh kërkesë.';
   } else {
      $fileName = 'kerkesa_' . date('Ymd_His') . '_' . bin2hex(random_bytes(3)) . '.txt';
      $filePath = $requestsFolder . '/' . $fileName;
      $displayFilePath = 'kerkesat/' . $fileName;
      $fileContent = "Data: " . date('d.m.Y H:i') . PHP_EOL;
      $fileContent .= "User ID: " . $requestUser['id'] . PHP_EOL;
      $fileContent .= "Username: " . $requestUser['username'] . PHP_EOL;
      $fileContent .= "Email: " . $requestUser['email'] . PHP_EOL;
      $fileContent .= "Kërkesa: " . $fileText . PHP_EOL;

      file_put_contents($filePath, $fileContent);

      $stmt = $pdo->prepare("
         INSERT INTO travel_requests (request_text, source_file, user_id, username, user_email)
         VALUES (?, ?, ?, ?, ?)
      ");
      $stmt->execute([
         $fileText,
         $displayFilePath,
         $requestUser['id'],
         $requestUser['username'],
         $requestUser['email']
      ]);
      $fileMessage = 'Kërkesa u ruajt me sukses.';
   }

   if ($isAjaxRequest) {
      header('Content-Type: application/json');
      echo json_encode([
         'success' => $fileText !== '' && $requestUser,
         'message' => $fileMessage
      ]);
      exit;
   }
}

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
   <link rel="stylesheet" href="scss/styles.css?v=5">

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
            <p>Welcome to our vibrant world of travel and exploration! At Travel. , we are passionate about crafting
                unforgettable journeys that unveil the beauty of diverse destinations. With a commitment to excellence,
                we invite you to embark on a personalized adventure, where each moment is a story waiting to be
                discovered.</p>
            <a href="about.php" class="btn">read more</a>
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

                        <button class="btn book-btn" data-id="<?= sanitize($pkg['id']); ?>" type="button">
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


    <section class="home-offer">
        <div class="content">
            <h3>upto 50% off</h3>
            <a href="book1.php" class="btn">book now</a>
        </div>
    </section>


    <section class="travel-requests" id="file-tools">
        <div class="content">
            <span class="request-label">Shërbim për klientët</span>
            <h3>Kërkesa speciale për udhëtim</h3>
            <p>Shëno kërkesa të klientëve për hotel, transport, ushqim ose destinacion.</p>

            <p class="request-message" id="request-message" <?php if ($fileMessage === ''): ?>style="display:none;"
                <?php endif; ?>>
                <?= sanitize($fileMessage); ?>
            </p>

            <form action="index.php#file-tools" method="post" class="request-form" id="request-form">
                <input type="hidden" name="file_form" value="1">

                <label for="file_text">Detajet e kërkesës</label>
                <textarea id="file_text" name="file_text" rows="5"
                    placeholder="p.sh. Klienti kërkon dhomë me pamje nga deti dhe transport nga aeroporti..."><?= sanitize($fileText); ?></textarea>

                <button type="submit" class="btn">Ruaj kërkesën</button>
            </form>
        </div>
    </section>






    <!-- pjesa e implementimit te footer-it -->
    <?php include 'footer.php'; ?>



    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
    <script src="js/script.js"></script>

    <script>
    document.querySelectorAll('.book-btn').forEach(button => {

        button.addEventListener('click', function() {

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
                        window.location.href = 'book1.php';
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
        button.addEventListener('click', function() {
            const order = this.dataset.order;

            fetch('ajax/sortPackages.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
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

    const requestForm = document.getElementById('request-form');
    const requestMessage = document.getElementById('request-message');

    if (requestForm && requestMessage) {
        requestForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(requestForm);
            formData.append('ajax_request', '1');

            fetch('index.php#file-tools', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    requestMessage.textContent = data.message;
                    requestMessage.style.display = 'block';

                    if (data.success) {
                        requestForm.reset();
                    }
                })
                .catch(() => {
                    requestMessage.textContent = 'Kërkesa nuk u ruajt. Provo përsëri.';
                    requestMessage.style.display = 'block';
                });
        });
    }
    </script>

</body>

</html>
