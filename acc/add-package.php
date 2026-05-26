<?php
session_start();
require_once __DIR__ . "/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    echo json_encode(["success" => false, "message" => "Only admin can add packages."]);
    exit;
}

$name = trim($_POST["packageName"] ?? "");
$description = trim($_POST["packageDescription"] ?? "");
$country = trim($_POST["packageCountry"] ?? "");
$duration = filter_input(INPUT_POST, "packageDuration", FILTER_VALIDATE_INT);
$price = filter_input(INPUT_POST, "packagePrice", FILTER_VALIDATE_FLOAT);

if ($name === "" || $description === "" || $country === "" || !$duration || !$price) {
    echo json_encode(["success" => false, "message" => "Please fill all fields."]);
    exit;
}

try {
    $pdo->beginTransaction();

    $destinationStmt = $pdo->prepare("
        INSERT INTO destinations (name, country, price)
        VALUES (?, ?, ?)
    ");
    $destinationStmt->execute([$name, $country, $price]);

    $destinationId = $pdo->lastInsertId();

    $packageStmt = $pdo->prepare("
        INSERT INTO packages (destination_id, title, description, duration_days, price)
        VALUES (?, ?, ?, ?, ?)
    ");
    $packageStmt->execute([$destinationId, $name, $description, $duration, $price]);

    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "Package added successfully."
    ]);
} catch (PDOException $e) {
    $pdo->rollBack();

    echo json_encode([
        "success" => false,
        "message" => "Database error."
    ]);
}
?>