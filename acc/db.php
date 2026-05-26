<?php

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "agjensioni-turistik";
$port = 3307;

try {
$pdo = new PDO("mysql:host=127.0.0.1;port=3307;dbname=agjensioni-turistik;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>