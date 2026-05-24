<?php
session_start();
require_once '../includes/DB.php';

header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Nuk ke autorizim.']);
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID nuk eshte valide.']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM packages WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Gabim ne databaze.']);
}