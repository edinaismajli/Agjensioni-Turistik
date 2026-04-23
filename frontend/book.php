<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $destination = $_POST["destination"];
    $guests = $_POST["guests"];

    $_SESSION["booking_name"] = $name;
    setcookie("last_destination", $destination, time() + 3600);

    echo "Booking completed successfully!";
}
?>