<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../acc/login.php");
    exit;
}

$stmt = $pdo->query("
    SELECT bookings.*, destinations.name AS destination_name
    FROM bookings
    INNER JOIN destinations ON bookings.destination_id = destinations.id
    ORDER BY bookings.id DESC
");

$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Bookings</title>

    <link rel="icon" type="image/x-icon" href="images/favicon.png">
    <link rel="stylesheet" href="scss/styles.css">

    <style>
        .admin-bookings {
            padding: 5rem 10%;
        }

        .admin-bookings h1 {
            text-align: center;
            margin-bottom: 2rem;
            font-size: 3rem;
            text-transform: uppercase;
        }

        .bookings-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            font-size: 1.5rem;
        }

        .bookings-table th,
        .bookings-table td {
            border: 1px solid #ddd;
            padding: 1.2rem;
            text-align: left;
        }

        .bookings-table th {
            background: #222;
            color: #fff;
        }

        .delete-booking-btn {
            background: #d9534f;
            color: #fff;
            border: none;
            padding: 0.8rem 1.2rem;
            cursor: pointer;
            font-size: 1.4rem;
        }

        .delete-booking-btn:hover {
            background: #c9302c;
        }

        .empty-message {
            text-align: center;
            font-size: 1.6rem;
            padding: 2rem;
        }
    </style>
</head>

<body>

<?php include "header.php"; ?>

<div class="heading" style="background:url(images/header-bg-2.png) no-repeat">
    <h1>admin bookings</h1>
</div>

<section class="admin-bookings">

    <h1>Bookings</h1>

    <table class="bookings-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Destination</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Guests</th>
                <th>Arrivals</th>
                <th>Leaving</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody id="bookings-table-body">
            <?php if (count($bookings) > 0) { ?>
                <?php foreach ($bookings as $booking) { ?>
                    <tr id="booking-row-<?php echo $booking['id']; ?>">
                        <td><?php echo htmlspecialchars($booking['id']); ?></td>
                        <td><?php echo htmlspecialchars($booking['destination_name']); ?></td>
                        <td><?php echo htmlspecialchars($booking['name']); ?></td>
                        <td><?php echo htmlspecialchars($booking['email']); ?></td>
                        <td><?php echo htmlspecialchars($booking['phone']); ?></td>
                        <td><?php echo htmlspecialchars($booking['guests']); ?></td>
                        <td><?php echo htmlspecialchars($booking['arrivals']); ?></td>
                        <td><?php echo htmlspecialchars($booking['leaving']); ?></td>
                        <td><?php echo htmlspecialchars($booking['status']); ?></td>
                        <td>
                            <button
                                type="button"
                                class="delete-booking-btn"
                                data-id="<?php echo $booking['id']; ?>">
                                Delete
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr id="empty-bookings-row">
                    <td colspan="10" class="empty-message">No bookings found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</section>

<?php include "footer.php"; ?>

<script>
document.querySelectorAll(".delete-booking-btn").forEach(button => {
    button.addEventListener("click", async function () {
        const bookingId = this.dataset.id;

        if (!confirm("A je i sigurt qe deshiron me fshi kete booking?")) {
            return;
        }

        const formData = new FormData();
        formData.append("id", bookingId);

        try {
            const response = await fetch("delete-booking.php", {
                method: "POST",
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                const row = document.getElementById("booking-row-" + bookingId);
                row.remove();

                const tableBody = document.getElementById("bookings-table-body");

                if (tableBody.children.length === 0) {
                    tableBody.innerHTML = `
                        <tr id="empty-bookings-row">
                            <td colspan="10" class="empty-message">No bookings found.</td>
                        </tr>
                    `;
                }

                alert("Booking u fshi me sukses.");
            } else {
                alert(result.message);
            }
        } catch (error) {
            alert("Gabim gjate fshirjes.");
        }
    });
});
</script>

</body>
</html>