<?php
session_start();
include 'con2.php';

$user_id = $_SESSION['user_id'];
$user_lat = $_POST['user_lat'];
$user_lng = $_POST['user_lng'];
$house_code = $_POST['house_code'];

// Get pump house coordinates
$stmt = $conn->prepare("SELECT latitude, longitude FROM pump_houses WHERE house_code = ?");
$stmt->bind_param("s", $house_code);
$stmt->execute();
$result = $stmt->get_result();
$house = $result->fetch_assoc();

$house_lat = $house['latitude'];
$house_lng = $house['longitude'];

// Distance calculation (meters)
function getDistance($lat1, $lon1, $lat2, $lon2) {
    $earth_radius = 6371000;
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) * sin($dLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return $earth_radius * $c;
}

$distance = getDistance($user_lat, $user_lng, $house_lat, $house_lng);
$allowed_radius = 50; // meters

if ($distance <= $allowed_radius) {

    // Redirect to inspection form (next step)
    header("Location: pump_inspection_form.php");
    exit;

} else {
    echo "<h2 style='color:red;'>❌ Location didn't match</h2>";
    echo "<p>You must be at the pump house to perform inspection.</p>";
}
