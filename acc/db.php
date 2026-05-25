<?php

$host = "127.0.0.1";
$port = 3307;
$username = "root";
$password = "";
$dbname = "agjensioni-turistik";

$con = mysqli_connect($host, $username, $password, $dbname, $port);

if (!$con) {
    die("Db connection error..." . mysqli_connect_error());
}

?>
