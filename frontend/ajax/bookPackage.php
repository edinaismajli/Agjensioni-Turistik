<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'error';
    exit;
}

$packageId = filter_input(INPUT_POST, 'package_id', FILTER_VALIDATE_INT);

if (!$packageId) {
    http_response_code(400);
    echo 'error';
    exit;
}

$_SESSION['selected_package_id'] = $packageId;

echo 'success';
?>