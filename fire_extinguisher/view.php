<?php
include "db.php";

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM extinguisher WHERE id='$id'")->fetch_assoc();
?>

<h2>Fire Extinguisher Info</h2>

<p><b>Name:</b> <?= $data['name'] ?></p>
<p><b>Type:</b> <?= $data['type'] ?></p>
<p><b>Serial:</b> <?= $data['serial_no'] ?></p>
<p><b>Status:</b> <?= $data['status'] ?></p>
<p><b>Expired:</b> <?= $data['expired_date'] ?></p>
