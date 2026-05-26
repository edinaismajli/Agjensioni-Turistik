<?php
require_once __DIR__ . "/db.php";

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

require_once '../includes/session.php';
require_once '../classes/User.php';
require_once '../classes/Admin.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../frontend/index.php');
    exit();
}

$admin = new Admin(
    $_SESSION['user_id'],
    $_SESSION['username'],
    $_SESSION['email'],
    $_SESSION['role']
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../frontend/images/favicon.png">
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../frontend/scss/admin.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="sidebar">
        <h4 class="admin-title">Admin Dashboard</h4>

        <p style="color: white; text-align: center; margin-bottom: 10px;">
            Welcome, <?php echo htmlspecialchars($admin->getUsername()); ?>
        </p>

        <p style="color: white; text-align: center; margin-bottom: 15px;">
            Role: <?php echo htmlspecialchars($_SESSION['role']); ?>
        </p>

        <div class="date-time">
            <div id="date"></div>
            <div id="time"></div>
        </div>

        <button data-section-id="addPackageSection">Add Package</button>
        <button data-section-id="bookings">Manage Bookings</button>
        <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin"): ?>
       <button onclick="window.location.href='/Agjensioni-Turistik/frontend/index.php'">Go to Index</button>


        <?php endif; ?>
        <button id="logoutButton">LogOut</button>
    </div>

    <div id="mainContent" class="container">
        <div id="addPackageSection" class="content-section">
            <h1 class="header">Add Package</h1>
       <form id="addPackageForm" class="form-addpkg" method="post" action="../acc/add-package.php">
    <input type="text" id="packageName" name="packageName" class="input-field" placeholder="Package Name" required>

    <textarea id="packageDescription" name="packageDescription" class="input-field" placeholder="Package Description" required></textarea>

    <input type="text" id="packageCountry" name="packageCountry" class="input-field" placeholder="Country" required>

    <input type="number" id="packageDuration" name="packageDuration" class="input-field" placeholder="Duration Days" min="1" required>

    <input type="number" id="packagePrice" name="packagePrice" class="input-field" placeholder="Price" min="1" step="0.01" required>

    <button type="submit" class="action-button">Add Package</button>
</form>





        </div>

        
        <div id="bookings" class="content-section hidden">
    <h1 class="header">
        Manage Bookings
        <button id="refreshBookings" class="refresh-button">
            <i class="fas fa-sync-alt"></i> <span id="refreshText">Refresh</span>
        </button>
    </h1>

    <table  border="0.7px"cellpadding="10" cellspacing="0" style="width:100%; font-size:1.5rem; background:#fff;color:gray; border:0.7px solid #808080;">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Destination</th>
            <th>Guests</th>
            <th>Arrivals</th>
            <th>Leaving</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php foreach ($bookings as $booking): ?>
        <tr id="booking-<?php echo (int)$booking["id"]; ?>">
            <td><?php echo clean($booking["name"]); ?></td>
            <td><?php echo clean($booking["email"]); ?></td>
            <td><?php echo clean($booking["phone"]); ?></td>
            <td><?php echo clean($booking["destination_name"]); ?></td>
            <td><?php echo clean($booking["guests"]); ?></td>
            <td><?php echo clean($booking["arrivals"]); ?></td>
            <td><?php echo clean($booking["leaving"]); ?></td>
            <td><?php echo clean($booking["status"]); ?></td>
            <td>
                <button class="delete-booking-btn" data-id="<?php echo (int)$booking["id"]; ?>">
                    Delete
                </button>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

    <script src="../frontend/admin.js"></script>
</body>

</html>

<script>
document.querySelectorAll(".delete-booking-btn").forEach(button => {
    button.addEventListener("click", async function () {
        if (!confirm("A je i sigurt qe deshiron me fshi kete booking?")) {
            return;
        }

        const bookingId = this.dataset.id;
        const formData = new FormData();
        formData.append("id", bookingId);

        const response = await fetch("delete-booking.php", {
            method: "POST",
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            document.getElementById("booking-" + bookingId).remove();
        } else {
            alert(result.message || "Gabim gjate fshirjes.");
        }
    });
});
</script>
