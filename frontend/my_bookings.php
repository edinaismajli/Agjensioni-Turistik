<?php
session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    die("Only admin can view bookings.");
}

require_once "db.php";

$stmt = $pdo->prepare("
    SELECT bookings.*, destinations.name AS destination_name
    FROM bookings
    INNER JOIN destinations ON bookings.destination_id = destinations.id
    ORDER BY bookings.created_at DESC
");
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

function clean($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Bookings</title>
    <link rel="stylesheet" href="scss/styles.css">
</head>

<body>

    <?php include "header.php"; ?>

    <section class="booking">
        <h1 class="heading-title">Bookings</h1>

        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; font-size:1.5rem; background:#fff;">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Destination</th>
                <th>Guests</th>
                <th>Arrivals</th>
                <th>Leaving</th>
                <th>Status</th>
            </tr>

            <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?php echo clean($booking["name"]); ?></td>
                <td><?php echo clean($booking["email"]); ?></td>
                <td><?php echo clean($booking["phone"]); ?></td>
                <td><?php echo clean($booking["destination_name"]); ?></td>
                <td><?php echo clean($booking["guests"]); ?></td>
                <td><?php echo clean($booking["arrivals"]); ?></td>
                <td><?php echo clean($booking["leaving"]); ?></td>
                <td><?php echo clean($booking["status"]); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <?php include "footer.php"; ?>

</body>

</html>