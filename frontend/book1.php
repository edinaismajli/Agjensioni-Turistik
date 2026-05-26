<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Book Your Trip</title>

    <link rel="icon" type="image/x-icon" href="images/favicon.png" />
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="scss/styles.css" />

    <style>
    .booking .book-form .flex .inputBox select {
        width: 100%;
        padding: 1.2rem 1.4rem;
        font-size: 1.6rem;
        color: #222;
        background: #fff;
        border: 1px solid #222;
        cursor: pointer;
    }

    .booking-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 2rem;
    }
    </style>
</head>

<body>
    <?php include "header.php"; ?>

    <div id="menu-btn" class="fas fa-bars"></div>
    </section>

    <div class="heading" style="background: url(images/header-bg-3.png) no-repeat">
        <h1>book now</h1>
    </div>

    <section class="booking">
        <h1 class="heading-title">Book your trip!</h1>

        <form action="./book.php" method="post" class="book-form">
            <div class="flex">
                <div class="inputBox">
                    <span>Name:</span>
                    <input type="text" name="name" placeholder="Enter your name" required />
                </div>

                <div class="inputBox">
                    <span>Email:</span>
                    <input type="email" name="email" placeholder="Enter your email" required />
                </div>

                <div class="inputBox">
                    <span>Phone:</span>
                    <input type="text" name="phone" placeholder="Enter your phone number" required />
                </div>

                <div class="inputBox">
                    <span>Address:</span>
                    <input type="text" name="address" placeholder="Enter your address" required />
                </div>

                <div class="inputBox">
                    <span>Destination:</span>

                    <select name="destination" required>
                        <option value="">Select a destination</option>
                        <option value="India">India</option>
                        <option value="Switzerland">Switzerland</option>
                        <option value="Latvia">Latvia</option>
                        <option value="France">France</option>
                        <option value="Japan">Japan</option>
                        <option value="Australia">Australia</option>
                    </select>
                </div>

                <div class="inputBox">
                    <span>Guests:</span>
                    <input type="number" name="guests" min="1" max="20" placeholder="Number of guests" required />
                </div>

                <div class="inputBox">
                    <span>Arrivals:</span>
                    <input type="date" name="arrivals" required />
                </div>

                <div class="inputBox">
                    <span>Leaving:</span>
                    <input type="date" name="leaving" required />
                </div>
            </div>

            <div class="booking-actions">
                <input type="submit" value="Submit" class="btn" />
                <a href="my_bookings.php" class="btn">View Bookings</a>
            </div>
        </form>
    </section>




    <?php include "footer.php"; ?>

</body>

</html>