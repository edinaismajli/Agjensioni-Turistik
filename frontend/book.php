<?php

session_start();
require_once "db.php";



require __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name        = trim($_POST["name"]);
    $email       = trim($_POST["email"]);
    $phone       = trim($_POST["phone"]);
    $address     = trim($_POST["address"]);
    $destination = trim($_POST["destination"]);
    $guests      = trim($_POST["guests"]);
    $arrivals    = trim($_POST["arrivals"]);
    $leaving     = trim($_POST["leaving"]);

    $errors = [];

    if (!preg_match("/^[a-zA-Z\s]{3,50}$/", $name)) {
        $errors[] = "Invalid name";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email";
    }

    if (!preg_match("/^[0-9]{8,15}$/", $phone)) {
        $errors[] = "Invalid phone number";
    }

    if ($guests < 1 || $guests > 20) {
        $errors[] = "Guests must be between 1 and 20";
    }

    if (strtotime($arrivals) >= strtotime($leaving)) {
        $errors[] = "Leaving date must be after arrival date";
    }

    try {
        $stmt = $pdo->prepare("SELECT id FROM destinations WHERE name = ?");
        $stmt->execute([$destination]);
        $destinationData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$destinationData) {
            $errors[] = "Invalid destination";
        }

        if (count($errors) == 0) {
            $stmt = $pdo->prepare("
                INSERT INTO bookings 
                (destination_id, name, email, phone, address, guests, arrivals, leaving)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
    $destinationData["id"],
    $name,
    $email,
    $phone,
    $address,
    $guests,
    $arrivals,
    $leaving
]);

$subject = "Booking Confirmation";
$message = "Hello $name, your booking for $destination from $arrivals to $leaving was received successfully.";
$headers = "From: support@travelagency.com";

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username = $_ENV['SMTP_USER'];
    $mail->Password = $_ENV['SMTP_PASS'];  
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom($_ENV['SMTP_USER'], 'Travel Agency');
    $mail->addAddress($email, $name);
    $mail->addCC($_ENV['ADMIN_EMAIL']);
    $mail->Subject = "Booking Confirmation";
    $mail->Subject = "Booking Confirmation";
$mail->isHTML(true);
$mail->Body = "
<!DOCTYPE html>
<html>
<head>
  <meta charset='UTF-8'>
  <style>
    body { font-family: Arial, sans-serif; color: #333; }
    .container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; }
    h2 { color: #0066cc; }
    .details p { margin: 5px 0; }
  </style>
</head>
<body>
  <div class='container'>
    <h2>📩 Booking Confirmation</h2>
    <p>Hello $name,</p>
    <p>Your booking for <strong>$destination</strong> from <strong>$arrivals</strong> to <strong>$leaving</strong> was received successfully.</p>
    <p><strong>Guests:</strong> $guests</p>
    <p><strong>Phone:</strong> $phone</p>
    <p><strong>Address:</strong> $address</p>
    <p>Thank you for choosing Travel Agency!</p>
    <hr>
    <p style='font-size:12px;color:#777;'>Travel Agency System</p>
  </div>
</body>
</html>
";


    $mail->send();

    $adminMail = new PHPMailer(true);
$adminMail->isSMTP();
$adminMail->Host       = 'smtp.gmail.com';
$adminMail->SMTPAuth   = true;
$adminMail->Username   = $_ENV['SMTP_USER'];
$adminMail->Password   = $_ENV['SMTP_PASS'];
$adminMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$adminMail->Port       = 587;

$adminMail->setFrom($_ENV['SMTP_USER'], 'Travel Agency System');
$adminMail->addAddress($_ENV['ADMIN_EMAIL'], 'Administrator');
$adminMail->Subject = "New Booking Received - $destination";
$adminMail->isHTML(true);
$adminMail->Body = "
<!DOCTYPE html>
<html>
<head><meta charset='UTF-8'></head>
<body>
  <h2>📩 New Booking Received</h2>
  <p>A new booking has been made. Here are the details:</p>
  <ul>
    <li><strong>Name:</strong> $name</li>
    <li><strong>Email:</strong> $email</li>
    <li><strong>Phone:</strong> $phone</li>
    <li><strong>Address:</strong> $address</li>
    <li><strong>Destination:</strong> $destination</li>
    <li><strong>Guests:</strong> $guests</li>
    <li><strong>Arrival:</strong> $arrivals</li>
    <li><strong>Leaving:</strong> $leaving</li>
  </ul>
  <p>Please review and prepare arrangements accordingly.</p>
</body>
</html>
";
$adminMail->send();
    echo "Booking confirmation email sent!";
} catch (Exception $e) {
    echo "Email could not be sent. Error: {$mail->ErrorInfo}";
}

$_SESSION["booking_name"] = $name;
$_SESSION["booking_email"] = $email;
$_SESSION["booking_destination"] = $destination;

setcookie("last_destination", $destination, time() + 3600);

echo "Booking completed successfully!";
        } else {
            foreach ($errors as $error) {
                echo htmlspecialchars($error) . "<br>";
            }
        }

    } catch (PDOException $e) {
        echo "Something went wrong. Please try again.";
    }
}
?>