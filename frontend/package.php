<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../acc/login.php');
    exit;
}

include '../classes/package.php';

$packages = [
    new Package("India", "Explore the rich culture of India", "images/img-4.jpg"),
    new Package("Switzerland", "Beautiful mountains and lakes", "images/img-8.jpg"),
    new Package("Latvia", "Hidden gem in Europe", "images/img-9.jpg"),
    new Package("France", "Romantic destinations", "images/img-11.jpg"),
    new Package("Japan", "Modern and traditional mix", "images/img-12.jpg"),
    new Package("Australia", "Adventure and nature", "images/img-6.jpg")
];
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

<?php include 'header.php'; ?>

<div class="heading" style="background:url(images/header-bg-2.png) no-repeat">
    <h1>packages</h1>
</div>

<section class="packages">
    <h1 class="heading-title">top destinations</h1>

    <div class="box-container">
        <?php foreach ($packages as $p) { ?>
            <div class="box">
                <div class="image">
                    <img src="<?php echo htmlspecialchars($p->getImage()); ?>" alt="<?php echo htmlspecialchars($p->getName()); ?>">
                </div>

                <div class="content">
                    <h3><?php echo htmlspecialchars($p->getName()); ?></h3>
                    <p><?php echo htmlspecialchars($p->getDescription()); ?></p>

                    <button
                        type="button"
                        class="btn weather-btn"
                        data-destination="<?php echo htmlspecialchars($p->getName()); ?>">
                        Show Weather
                    </button>

                    <p class="weather-result"></p>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] == "admin") { ?>
    <button type="button" class="btn delete-package-btn">Delete</button>
<?php } ?>

                    <a href="book1.php" class="btn">book now</a>
                </div>
            </div>
        <?php } ?>
    </div>
</section>

<?php include 'footer.php'; ?>

<script>
const destinationCoordinates = {
    India: { latitude: 28.6139, longitude: 77.2090 },
    Switzerland: { latitude: 46.9480, longitude: 7.4474 },
    Latvia: { latitude: 56.9496, longitude: 24.1052 },
    France: { latitude: 48.8566, longitude: 2.3522 },
    Japan: { latitude: 35.6762, longitude: 139.6503 },
    Australia: { latitude: -33.8688, longitude: 151.2093 }
};

document.querySelectorAll('.weather-btn').forEach(button => {
    button.addEventListener('click', async function () {
        const destination = this.dataset.destination;
        const resultElement = this.nextElementSibling;
        const coordinates = destinationCoordinates[destination];

        if (!coordinates) {
            resultElement.textContent = 'Weather data not available.';
            return;
        }

        resultElement.textContent = 'Loading weather...';

        try {
            const url = `https://api.open-meteo.com/v1/forecast?latitude=${coordinates.latitude}&longitude=${coordinates.longitude}&current=temperature_2m,wind_speed_10m&timezone=auto`;

            const response = await fetch(url);
            const data = await response.json();

            if (data.current) {
               resultElement.textContent = `Temperature: ${data.current.temperature_2m}°C, Wind: ${data.current.wind_speed_10m} km/h`;
            } else {
                resultElement.textContent = 'Weather data not found.';
            }
        } catch (error) {
            resultElement.textContent = 'Error loading weather.';
        }
    });
});

document.querySelectorAll('.delete-package-btn').forEach(button => {
    button.addEventListener('click', function () {
        if (!confirm('A je i sigurt qe deshiron me fshi kete pakete?')) {
            return;
        }

        this.closest('.box').remove();
    });
});
</script>

</body>
</html>