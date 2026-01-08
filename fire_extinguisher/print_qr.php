<?php
include "db.php";
$id = $_GET['id'];
$data = $conn->query("SELECT * FROM extinguisher WHERE id=$id")->fetch_assoc();
?>

<h2>Fire Extinguisher Info</h2>
<img src="assets/qrcodes/<?= $data['qr_image'] ?>" width="200"><br><br>

<b>Name:</b> <?= $data['name'] ?><br>
<b>Type:</b> <?= $data['type'] ?><br>
<b>Serial:</b> <?= $data['serial_no'] ?><br>
<b>Status:</b> <?= $data['status'] ?><br>

<button onclick="window.print()">Print</button>
