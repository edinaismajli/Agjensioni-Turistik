<?php

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "agjensioni-turistik";
$port = 3307;

$con = mysqli_connect($servername, $username, $password, $dbname, $port);

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>