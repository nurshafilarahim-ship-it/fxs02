<?php
include "db.php";

if (!isset($_GET['id'])) {
    die("Invalid QR");
}

$id = intval($_GET['id']);
$data = $conn->query("SELECT * FROM extinguisher WHERE id=$id")->fetch_assoc();

if (!$data) {
    die("QR data not found");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Print QR</title>

<!-- REQUIRED FOR MOBILE PRINT -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    margin: 0;
    padding: 0;
    background: #0f172a;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    font-family: Arial, sans-serif;
}

/* QR container */
.qr-wrapper {
    background: #ffffff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.4);
    text-align: center;
}

.qr-wrapper img {
    width: 220px;
}

/* Print button */
.print-btn {
    margin-top: 20px;
    padding: 10px 18px;
    background: linear-gradient(135deg, #38bdf8, #2563eb);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
}

/* PRINT SETTINGS */
@media print {
    body {
        background: #ffffff;
    }

    body * {
        visibility: hidden;
    }

    .qr-wrapper,
    .qr-wrapper * {
        visibility: visible;
    }

    .qr-wrapper {
        position: absolute;
        top: 0;
        left: 0;
        padding: 0;
        box-shadow: none;
        border-radius: 0;
    }

    .print-btn {
        display: none;
    }
}
</style>
</head>

<body>

<div class="qr-wrapper">
    <img src="assets/qrcodes/<?= htmlspecialchars($data['qr_image']) ?>" alt="QR Code">
    <button class="print-btn" onclick="printQR()">Print QR</button>
</div>

<script>
function printQR() {
    // Mobile-safe print trigger
    setTimeout(() => {
        window.print();
    }, 300);
}

/* OPTIONAL: auto print when page opened from QR
   Uncomment if you want auto print */

 window.onload = () => {
    setTimeout(() => window.print(), 600);
};
</script>

</body>
</html>
