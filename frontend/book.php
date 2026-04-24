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

    $_SESSION["booking_name"] = $name;

    setcookie("last_destination", $destination, time() + 3600);

    echo "Booking completed successfully!";
}
?>