<?php
session_start();
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$user_lat = $data['user_lat'];
$user_lng = $data['user_lng'];
$pump_lat = $data['pump_lat'];
$pump_lng = $data['pump_lng'];
$pump_id = $data['pump_id'];

function distance($lat1, $lon1, $lat2, $lon2) {
    $earth_radius = 6371000;
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) * sin($dLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $earth_radius * $c;
}

$distance = distance($user_lat, $user_lng, $pump_lat, $pump_lng);

if ($distance <= 50) { // 50 meters allowed
    $stmt = $conn->prepare("INSERT INTO pump_attendance (user_id, pump_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $_SESSION['user_id'], $pump_id);
    $stmt->execute();

    echo json_encode(["status" => "ok"]);
} else {
    echo json_encode(["status" => "fail"]);
}
