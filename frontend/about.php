<?php
// about.php

date_default_timezone_set('Europe/Tirane');

$ora = date('H');
if ($ora < 12) {
    $pershendetja = "Good Morning!";
} elseif ($ora < 18) {
    $pershendetja = "Good Afternoon!";
} else {
    $pershendetja = "Good Evening!";
}

$reviews = [
    [
        "emri" => "Kashif Abbas Kazmi",
        "roli" => "Traveler",
        "komenti" => "Exceptional experience! Everything was perfectly planned and the destinations were beautiful.",
        "yjet" => 5,
        "foto" => "images/kashif.png"
    ],
    [
        "emri" => "Muhammad Sarim",
        "roli" => "Traveler",
        "komenti" => "Amazing destinations and professional guides. A truly unforgettable journey.",
        "yjet" => 4,
        "foto" => "images/sarim.jpg"
    ],
    [
        "emri" => "Muhammad Abdullah",
        "roli" => "Traveler",
        "komenti" => "Adventure redefined! Perfect for people who love exciting experiences.",
        "yjet" => 3,
        "foto" => "images/abdullah.png"
    ]
];

$yearly_guests = 175000;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>About Us</title>

    <link rel="icon" type="image/x-icon" href="images/favicon.png">

    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">

    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        text-decoration: none;
        transition: 0.2s linear;
        font-family: Arial, Helvetica, sans-serif;
    }

    body {
        background: #f5f5f5;
    }

    section {
        padding: 3rem 9%;
    }

    .heading-title {
        text-align: center;
        margin-bottom: 2rem;
        font-size: 3rem;
        color: #222;
    }

    /* STILI PËR HEADER-IN E RI DINAMIK */
    .header {
        position: sticky;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem 9%;
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
    }

    .logo {
        font-size: 2rem;
        font-weight: bold;
        color: #222;
        text-transform: lowercase;
    }

    .navbar a {
        margin-left: 2rem;
        color: #222;
        font-size: 1.1rem;
    }

    .navbar a:hover {
        color: #0099ff;
    }

    /* Nëse ke ndonjë stil specifik për butonin logout */
    .navbar a.logout {
        color: red;
    }

    .navbar a.logout:hover {
        color: darkred;
    }

    #menu-btn {
        font-size: 2rem;
        cursor: pointer;
        display: none;
    }

    .heading {
        background: url('images/header-bg-1.png') no-repeat;
        background-size: cover;
        background-position: center;
        padding: 6rem 2rem;
        text-align: center;
    }

    .heading h1 {
        font-size: 4rem;
        color: #fff;
        text-shadow: 0 .3rem .5rem rgba(0, 0, 0, .4);
    }

    .welcome-msg {
        font-size: 1.2rem;
        color: #0099ff;
        font-weight: bold;
        margin-bottom: 0.5rem;
        display: block;
    }

    .about {
        display: flex;
        align-items: center;
        gap: 3rem;
        flex-wrap: wrap;
        background: #fff;
    }

    .about .image {
        flex: 1 1 20rem;
    }

    .about .image img {
        width: 100%;
        border-radius: 10px;
    }

    .about .content {
        flex: 1 1 40rem;
    }

    .about .content h3 {
        font-size: 2.5rem;
        color: #222;
        margin-bottom: 1rem;
    }

    .about .content p {
        font-size: 1rem;
        color: #555;
        line-height: 2;
        padding: 0.5rem 0;
    }

    .icons-container {
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .icons {
        background: #f0f0f0;
        padding: 1.5rem;
        text-align: center;
        border-radius: 10px;
        flex: 1 1 12rem;
    }

    .icons i {
        font-size: 2rem;
        color: #0099ff;
        margin-bottom: 1rem;
    }

    .icons span {
        display: block;
        font-size: 1rem;
        color: #222;
        font-weight: bold;
    }

    .reviews {
        background: #eee;
    }

    /* KUTIJAT E RRESHTUARA BASHKË DHE TË BARABARTA */
    .slide {
        background: #fff;
        border-radius: 10px;
        padding: 2.5rem 2rem;
        text-align: center;
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
        height: 390px !important;
        /* Lartësi fikse që të jenë krejtësisht kopje e njëra-tjetrës */
        position: relative;
    }

    .slide .stars {
        padding-bottom: 1rem;
    }

    .slide .stars i {
        font-size: 1.3rem;
        color: gold;
    }

    .slide p {
        font-size: 1rem;
        line-height: 1.8;
        color: #555;
        padding: 0;
        margin: 0;
    }

    /* Grupi i të dhënave që qëndron i gozhduar në fund */
    .slide .client-info {
        position: absolute;
        bottom: 2.5rem;
        left: 0;
        right: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .slide .client-info h3 {
        font-size: 1.5rem;
        color: #222;
        margin: 0;
        padding: 0;
    }

    .slide .client-info span {
        color: #0099ff;
        font-size: 1rem;
        display: block;
        margin: 0.3rem 0 0.8rem 0;
    }

    .slide .client-info img {
        height: 5rem;
        width: 5rem;
        border-radius: 50%;
        margin: 0 auto;
        object-fit: cover;
    }

    .footer {
        background: #222;
    }

    .footer .box-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(20rem, 1fr));
        gap: 2rem;
    }

    .footer .box h3 {
        color: #fff;
        font-size: 1.5rem;
        padding-bottom: 1rem;
    }

    .footer .box a {
        display: block;
        color: #ddd;
        padding: 0.7rem 0;
        font-size: 1rem;
    }

    .footer .box a i {
        color: #0099ff;
        padding-right: .5rem;
    }

    .footer .box a:hover {
        color: #0099ff;
    }

    .credit {
        text-align: center;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(255, 255, 255, .2);
        color: #fff;
        font-size: 1rem;
    }

    .credit span {
        color: #0099ff;
    }

    /* RESPONSIVE */
    @media (max-width:768px) {
        #menu-btn {
            display: inline-block;
        }

        .navbar {
            position: absolute;
            top: 99%;
            left: 0;
            right: 0;
            background: #fff;
            border-top: 1px solid rgba(0, 0, 0, .1);
            padding: 1rem;
        }

        .navbar a {
            display: block;
            margin: 1rem 0;
            margin-left: 0;
        }

        .heading h1 {
            font-size: 3rem;
        }

        .slide {
            height: 410px !important;
        }
    }
    </style>
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="heading">
        <h1>About Us</h1>
    </div>

    <section class="about">
        <div class="image">
            <img src="images/about-img.jpg" alt="">
        </div>

        <div class="content">
            <span class="welcome-msg"><?php echo $pershendetja; ?></span>

            <h3>Why Choose Us?</h3>

            <p>
                Explore the Caribbean's best-kept secrets and captivating locales,
                where every destination unveils a paradise waiting to be discovered.
            </p>

            <p>
                Experience unparalleled hospitality with over <?php echo number_format($yearly_guests); ?> yearly
                guests,
                offering amazing accommodations and memorable locations.
            </p>

            <div class="icons-container">
                <div class="icons">
                    <i class="fas fa-map"></i>
                    <span>Top Destinations</span>
                </div>

                <div class="icons">
                    <i class="fas fa-hand-holding-usd"></i>
                    <span>Affordable Price</span>
                </div>

                <div class="icons">
                    <i class="fas fa-headset"></i>
                    <span>24/7 Guide Service</span>
                </div>
            </div>
        </div>
    </section>

    <section class="reviews">
        <h1 class="heading-title">Clients Reviews</h1>

        <div class="swiper reviews-slider">
            <div class="swiper-wrapper">

                <?php foreach ($reviews as $rev): ?>
                <div class="swiper-slide slide">
                    <div class="stars">
                        <?php for($i = 0; $i < $rev['yjet']; $i++): ?>
                        <i class="fas fa-star"></i>
                        <?php endfor; ?>
                    </div>

                    <p><?php echo $rev['komenti']; ?></p>

                    <div class="client-info">
                        <h3><?php echo $rev['emri']; ?></h3>
                        <span><?php echo $rev['roli']; ?></span>
                        <img src="<?php echo $rev['foto']; ?>" alt="">
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <section class="footer">
        <div class="box-container">
            <div class="box">
                <h3>Quick Links</h3>
                <a href="index.php"><i class="fas fa-angle-right"></i> Home</a>
                <a href="package.php"><i class="fas fa-angle-right"></i> Package</a>
                <a href="book.php"><i class="fas fa-angle-right"></i> Book</a>
                <a href="about.php"><i class="fas fa-angle-right"></i> About</a>
            </div>

            <div class="box">
                <h3>Contact Info</h3>
                <a href="#"><i class="fas fa-phone"></i> 0092-301-9583959</a>
                <a href="#"><i class="fas fa-envelope"></i> support@travelagency.com</a>
                <a href="#"><i class="fas fa-map"></i> Islamabad, Pakistan</a>
            </div>

            <div class="box">
                <h3>Follow Us</h3>
                <a href="#"><i class="fab fa-facebook-f"></i> Facebook</a>
                <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
                <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
            </div>
        </div>

        <div class="credit">
            Created by <span>Kashif Abbas Kazmi & Muhammad Sarim</span> | © <?php echo date('Y'); ?> All Rights
            Reserved!
        </div>
    </section>

    <script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>

    <script>
    // Skripti për hapjen/mbylljen e menusë responsive në celularë
    let menu = document.querySelector('#menu-btn');
    let navbar = document.querySelector('.header .navbar');

    menu.onclick = () => {
        menu.classList.toggle('fa-times');
        navbar.classList.toggle('active');
    };

    window.onscroll = () => {
        menu.classList.remove('fa-times');
        navbar.classList.remove('active');
    };

    var swiper = new Swiper(".reviews-slider", {
        loop: true,
        spaceBetween: 20,
        autoHeight: false,
        grabCursor: true,

        breakpoints: {
            640: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        },
    });
    </script>

</body>

</html>