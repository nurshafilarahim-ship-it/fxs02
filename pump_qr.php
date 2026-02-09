<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['token'])) {
    die("Invalid QR");
}

$token = $_GET['token'];

$stmt = $conn->prepare("SELECT * FROM pump_house WHERE qr_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Invalid QR Code");
}

$pump = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Pump House Access</title>
</head>
<body>
<h3>Verifying your location...</h3>

<script>
navigator.geolocation.getCurrentPosition(function(position) {
    const userLat = position.coords.latitude;
    const userLng = position.coords.longitude;

    fetch('verify_location.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            user_lat: userLat,
            user_lng: userLng,
            pump_lat: <?= $pump['latitude'] ?>,
            pump_lng: <?= $pump['longitude'] ?>,
            pump_id: <?= $pump['id'] ?>
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "ok") {
            alert("Attendance done ✅");
            window.location.href = "pump_inspection.php?pump_id=<?= $pump['id'] ?>";
        } else {
            alert("You are not at the pump house ❌");
            window.location.href = "dashboard.php";
        }
    });
});
</script>
</body>
</html>
