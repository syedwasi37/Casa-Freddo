<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false]);
    exit();
}

$country = trim($_POST['country'] ?? '');
$city = trim($_POST['city'] ?? '');
$area = trim($_POST['area'] ?? '');

if ($country === '' || $city === '' || $area === '') {
    http_response_code(422);
    echo json_encode(['ok' => false, 'message' => 'Missing location fields']);
    exit();
}

$_SESSION['delivery_location'] = [
    'country' => htmlspecialchars($country, ENT_QUOTES, 'UTF-8'),
    'city' => htmlspecialchars($city, ENT_QUOTES, 'UTF-8'),
    'area' => htmlspecialchars($area, ENT_QUOTES, 'UTF-8')
];

echo json_encode(['ok' => true, 'area' => $_SESSION['delivery_location']['area']]);

