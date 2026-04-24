<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name        = $_POST["name"];
    $email       = $_POST["email"];
    $phone       = $_POST["phone"];
    $address     = $_POST["address"];
    $destination = $_POST["destination"];
    $guests      = $_POST["guests"];
    $arrivals    = $_POST["arrivals"];
    $leaving     = $_POST["leaving"];

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

        $_SESSION["booking_name"] = $name;

        setcookie("last_destination", $destination, time() + 3600);

        echo "Booking completed successfully!";
    }
}
?>