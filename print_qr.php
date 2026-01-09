<?php
include "db.php";
$id = $_GET['id'];
$data = $conn->query("SELECT * FROM extinguisher WHERE id=$id")->fetch_assoc();
?>

<h2>Fire Extinguisher Info</h2>

<div class="print-only-qr">
    <img src="assets/qrcodes/<?= htmlspecialchars($data['qr_image']) ?>" width="200">
</div>

<b>Name:</b> <?= htmlspecialchars($data['name']) ?><br>
<b>Type:</b> <?= htmlspecialchars($data['type']) ?><br>
<b>Serial:</b> <?= htmlspecialchars($data['serial_no']) ?><br>
<b>Location:</b> <?= htmlspecialchars($data['location']) ?><br>
<b>Status:</b> <?= htmlspecialchars($data['status']) ?><br>
<b>
<?php
if ($data['status'] == 'Expired') {
    echo "Expired for " . abs($data['days_left']) . " days";
} else {
    echo "Days left: " . $data['days_left'];
}
?>
</b><br>

<button onclick="printQR()">Print QR</button>

<script>
function printQR() {
    window.print(); // Opens the browser's print dialog
}
</script>

<style>
/* Hide everything except QR when printing */
@media print {
    body * {
        visibility: hidden; /* hide all elements */
    }

    .print-only-qr, .print-only-qr * {
        visibility: visible; /* show only QR div */
    }

    .print-only-qr {
        position: absolute;
        top: 50px;
        left: 50px;
    }
}
</style>
