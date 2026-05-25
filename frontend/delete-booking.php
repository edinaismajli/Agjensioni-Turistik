<?php
session_start();
require_once "db.php";

header("Content-Type: application/json");

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    echo json_encode(["success" => false, "message" => "Only admin can delete bookings."]);
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id) {
    echo json_encode(["success" => false, "message" => "Invalid booking ID."]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error."]);
}
?>