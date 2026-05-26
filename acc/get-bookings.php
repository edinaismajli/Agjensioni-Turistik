<?php
session_start();
require_once __DIR__ . "/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    echo json_encode(["success" => false, "message" => "Only admin can view bookings."]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT bookings.*, destinations.name AS destination_name
        FROM bookings
        INNER JOIN destinations ON bookings.destination_id = destinations.id
        ORDER BY bookings.created_at DESC
    ");
    $stmt->execute();

    echo json_encode([
        "success" => true,
        "bookings" => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error."]);
}
?>