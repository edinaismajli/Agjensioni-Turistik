<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../acc/login.php');
    exit;
}

require_once '../includes/DB.php';

$error = '';

try {
    $stmt = $pdo->prepare("
        SELECT 
            packages.id,
            packages.destination_id,
            packages.title,
            packages.description,
            packages.duration_days,
            packages.price,
            packages.image,
            destinations.name AS destination_name,
            destinations.country
        FROM packages
        INNER JOIN destinations ON packages.destination_id = destinations.id
        ORDER BY packages.id DESC
    ");
    $stmt->execute();
    $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $packages = [];
    $error = "Nuk mund te ngarkohen paketat.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package</title>

    <link rel="icon" type="image/x-icon" href="images/favicon.png">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="scss/styles.css">
</head>
<body>

<section class="header">
    <a href="index.php" class="logo">travel.</a>

    <nav class="navbar">
        <a href="index.php">Home</a>
        <a href="package.php">Package</a>
        <a href="book.html">Book</a>
        <a href="about.html">About</a>
    </nav>

    <div id="menu-btn" class="fas fa-bars"></div>
</section>

<div class="heading" style="background:url(images/header-bg-2.png) no-repeat">
    <h1>packages</h1>
</div>

<section class="packages">

    <h1 class="heading-title">top destinations</h1>

    <div class="box-container">
        <?php foreach($packages as $p) { ?>
            <div class="box">
                <div class="image">
                    <img src="<?php echo $p->getImage(); ?>" alt="">
                </div>

                <div class="content">
                    <h3><?php echo $p->getName(); ?></h3>
                    <p><?php echo $p->getDescription(); ?></p>

                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] == "admin") { ?>
                        <a href="#" class="btn">Edit</a>
                    <?php } ?>

                    <a href="book.html" class="btn">book now</a>
                </div>
            </div>
        <?php } ?>
    </div>

</section>

<section class="footer">
    <div class="box-container">

        <div class="box">
            <h3>quick links</h3>
            <a href="index.php"><i class="fas fa-angle-right"></i> Home</a>
            <a href="package.php"><i class="fas fa-angle-right"></i> Package</a>
            <a href="book.html"><i class="fas fa-angle-right"></i> Book</a>
            <a href="about.html"><i class="fas fa-angle-right"></i> About</a>
        </div>

        <div class="box">
            <h3>contact info</h3>
            <a href="#"><i class="fas fa-phone"></i> 0092-301-9583959</a>
            <a href="#"><i class="fas fa-envelope"></i> support@travelagency.com</a>
        </div>

    </div>
</section>

<script src="js/script.js"></script>

</body>
</html>