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

<b>Name:</b> <?= htmlspecialchars($data['name']) ?><br>
<b>Type:</b> <?= htmlspecialchars($data['type']) ?><br>
<b>Serial No:</b> <?= htmlspecialchars($data['serial_no']) ?><br>
<b>Location:</b> <?= htmlspecialchars($data['location']) ?><br>
<b>Check-up Date:</b> <?= htmlspecialchars($data['date_checkup']) ?><br>
<b>Status:</b> <?= htmlspecialchars($data['status']) ?><br>
<b>
<?php
if ($data['status'] == 'Expired') {
    echo "Expired for " . abs($data['days_left']) . " days";
} else {
    echo "Days left before expiry: " . $data['days_left'];
}
?>
</b><br><br>

<a href="print_qr.php?id=<?= $data['id'] ?>" class="btn btn-primary">Print QR</a>

</body>
</html>
