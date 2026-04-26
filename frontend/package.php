<?php
session_start();

// kontroll login (pika 2)
if (!isset($_SESSION['user_id'])) {
    header('Location: ../acc/login.php');
    exit;
}

// përfshi klasën (pika 4 - OOP)
include 'classes/package.php';

// DATA (pika 3 - arrays)
$packages = [
    new Package("India", "Explore the rich culture of India", "../images/img-1.jpg"),
    new Package("Switzerland", "Beautiful mountains and lakes", "../images/img-2.jpg"),
    new Package("Latvia", "Hidden gem in Europe", "../images/img-3.jpg"),
    new Package("France", "Romantic destinations", "../images/img-4.jpg"),
    new Package("Japan", "Modern and traditional mix", "../images/img-5.jpg"),
    new Package("Australia", "Adventure and nature", "../images/img-6.jpg"),
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Package</title>

    <link rel="icon" type="image/x-icon" href="../images/favicon.png">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
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
</section>

<div class="heading" style="background:url(../images/header-bg-2.png) no-repeat">
    <h1>packages</h1>
</div>

<section class="packages">

    <h1 class="heading-title">top destinations</h1>

    <!-- SHFAQJA ME PHP -->
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
            <a href="index.php">Home</a>
            <a href="package.php">Package</a>
            <a href="book.html">Book</a>
            <a href="about.html">About</a>
        </div>

        <div class="box">
            <h3>contact info</h3>
            <a href="#">0092-301-9583959</a>
            <a href="#">support@travelagency.com</a>
        </div>
    </div>
</section>

</body>
</html>