<?php
include "db.php";
$id = $_GET['id'];
$data = $conn->query("SELECT * FROM extinguisher WHERE id=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Fire Extinguisher Info</title>
</head>
<body>

<h2>Fire Extinguisher Information</h2>

<img src="assets/qrcodes/<?= $data['qr_image'] ?>" width="200"><br><br>

<b>Name:</b> <?= $data['name'] ?><br>
<b>Type:</b> <?= $data['type'] ?><br>
<b>Serial No:</b> <?= $data['serial_no'] ?><br>
<b>Check-up Date:</b> <?= $data['date_checkup'] ?><br>
<b>Status:</b> <?= $data['status'] ?>

</body>
</html>
