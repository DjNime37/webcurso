<?php
// guardar_ubicacion.php
session_start();

if (isset($_POST['lat']) && isset($_POST['lng'])) {
    $lat = filter_input(INPUT_POST, 'lat', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $lng = filter_input(INPUT_POST, 'lng', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    $_SESSION['ubicacion'] = [
        'lat' => $lat,
        'lng' => $lng,
        'texto' => "Lat: {$lat}, Lng: {$lng}"
    ];

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
    exit;
}



