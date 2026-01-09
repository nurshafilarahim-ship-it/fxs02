<?php
include "db.php";
$id = $_GET['id'];
$data = $conn->query("SELECT * FROM extinguisher WHERE id=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Fire Extinguisher Info</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<h3>Fire Extinguisher Information</h3>

<img src="assets/qrcodes/<?= $data['qr_image'] ?>" width="180"><br><br>

<b>Name:</b> <?= $data['name'] ?><br>
<b>Type:</b> <?= $data['type'] ?><br>
<b>Serial No:</b> <?= $data['serial_no'] ?><br>
<b>Check-up Date:</b> <?= $data['date_checkup'] ?><br>
<b>Expiry Date:</b> <?= $data['expired_date'] ?><br>

<b>Status:</b>
<?= $data['status'] ?><br>

<b>
<?= ($data['status']=="Expired")
    ? "Expired for ".abs($data['days_left'])." days"
    : "Days left: ".$data['days_left']
?>
</b>

</body>
</html>
