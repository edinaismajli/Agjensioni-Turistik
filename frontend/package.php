<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../acc/login.php');
    exit;
}

require_once "db.php";

$stmt = $pdo->query("
    SELECT packages.*, destinations.name AS destination_name
    FROM packages
    INNER JOIN destinations ON packages.destination_id = destinations.id
    ORDER BY packages.id ASC
");

$packages = $stmt->fetchAll(PDO::FETCH_ASSOC);

$packageImages = [
    "India" => "images/img-4.jpg",
    "Switzerland" => "images/img-8.jpg",
    "Latvia" => "images/img-9.jpg",
    "France" => "images/img-11.jpg",
    "Japan" => "images/img-12.jpg",
    "Australia" => "images/img-6.jpg"
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
        <?php foreach ($packages as $p) { 
            $destinationName = $p["destination_name"];
            $image = $packageImages[$destinationName] ?? "images/img-1.jpg";
        ?>
            <div class="box" id="package-box-<?php echo $p['id']; ?>">
                <div class="image">
                    <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($destinationName); ?>">
                </div>

                <div class="content">
                    <h3><?php echo htmlspecialchars($p["title"]); ?></h3>
                    <p><?php echo htmlspecialchars($p["description"]); ?></p>

                    <button 
                        type="button" 
                        class="btn weather-btn" 
                        data-destination="<?php echo htmlspecialchars($destinationName); ?>">
                        Show Weather
                    </button>

                    <p class="weather-result"></p>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == "admin") { ?>
                        <button 
                            type="button" 
                            class="btn delete-package-btn"
                            data-id="<?php echo $p['id']; ?>">
                            Delete
                        </button>
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

document.querySelectorAll('.delete-package-btn').forEach(button => {
    button.addEventListener('click', async function () {
        const packageId = this.dataset.id;

        if (!confirm('A je i sigurt qe deshiron me fshi kete pakete?')) {
            return;
        }

        const formData = new FormData();
        formData.append('id', packageId);

        try {
            const response = await fetch('delete-package.php', {
                method: 'POST',
                body: formData
            });

            const text = await response.text();
            console.log(text);

            const result = JSON.parse(text);

            if (result.success) {
                document.getElementById('package-box-' + packageId).remove();
                alert('Paketa u fshi me sukses.');
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.log(error);
            alert('Gabim gjate fshirjes. Kontrollo console ose delete-package.php.');
        }
    });
});

document.querySelectorAll('.delete-package-btn').forEach(button => {
    button.addEventListener('click', async function () {
        const packageId = this.dataset.id;

        if (!confirm('A je i sigurt qe deshiron me fshi kete pakete?')) {
            return;
        }

        const formData = new FormData();
        formData.append('id', packageId);

        try {
            const response = await fetch('delete-package.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                document.getElementById('package-box-' + packageId).remove();
                alert('Paketa u fshi me sukses.');
            } else {
                alert(result.message);
            }
        } catch (error) {
            alert('Gabim gjate fshirjes.');
        }
    });
});
</script>

</body>
</html>