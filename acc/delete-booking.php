<?php
session_start();
require_once __DIR__ . "/db.php";

header("Content-Type: application/json");

// Vetëm admini mund të fshijë paketa
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    echo json_encode(["success" => false, "message" => "Only admin can delete packages."]);
    exit;
}

// Merr ID e paketës nga POST
$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id) {
    echo json_encode(["success" => false, "message" => "Invalid package ID."]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM packages WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(["success" => true, "message" => "Package deleted successfully."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
