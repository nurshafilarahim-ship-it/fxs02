<?php
session_start();
include 'con2.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['house_code'])) {
    die("Invalid QR code.");
}

$house_code = $_GET['house_code'];

// Get pump house location
$stmt = $conn->prepare("SELECT * FROM pump_houses WHERE house_code = ?");
$stmt->bind_param("s", $house_code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Pump house not found.");
}

$house = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pump House Inspection</title>
</head>
<body>
<h2>Checking Location...</h2>
<p id="status">Please allow location access.</p>

<script>
navigator.geolocation.getCurrentPosition(success, error);

function success(position) {
    const userLat = position.coords.latitude;
    const userLng = position.coords.longitude;

    fetch("verify_location.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: `user_lat=${userLat}&user_lng=${userLng}&house_code=<?php echo $house_code; ?>`
    })
    .then(res => res.text())
    .then(data => {
        document.body.innerHTML = data;
    });
}

function error() {
    document.getElementById("status").innerText = "Location access denied.";
}
</script>
</body>
</html>
