<?php

session_start();
require_once "db.php";

require __DIR__ . '/../vendor/autoload.php';

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
    $mail->Username   = 'blend.selmani3@student.uni-pr.edu'; 
    $mail->Password   = 'icak rygp xmxa ispe';   
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('support@travelagency.com', 'Travel Agency');
    $mail->addAddress($email, $name);

    $mail->Subject = "Booking Confirmation";
    $mail->Body    = "Hello $name, your booking for $destination from $arrivals to $leaving was received successfully.";

    $mail->send();
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