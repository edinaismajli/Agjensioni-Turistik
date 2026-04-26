<?php
session_start();

$destinations = [
    "India",
    "Switzerland",
    "Latvia",
    "France",
    "Japan",
    "Australia"
];

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

    if (!preg_match("/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/", $email)) {
        $errors[] = "Invalid email";
    }

    if (!preg_match("/^[0-9]{8,15}$/", $phone)) {
        $errors[] = "Invalid phone number";
    }

    if ($guests < 1 || $guests > 20) {
        $errors[] = "Guests must be between 1 and 20";
    }

    if (count($errors) == 0) {

        $userBooking = [
            "name" => $name,
            "email" => $email,
            "destination" => $destination,
            "guests" => $guests
        ];

        $_SESSION["booking_name"] = $name;
        $_SESSION["booking_email"] = $email;
        $_SESSION["booking_destination"] = $destination;

        setcookie("last_destination", $destination, time() + 3600);

        echo "Booking completed successfully!";
    }
}
?>