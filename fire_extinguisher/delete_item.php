<?php
include "db.php";

if (!isset($_GET['id'])) {
    header("Location: main.php");
    exit;
}

$id = $_GET['id'];

/* 1. Get QR image name */
$result = $conn->query("SELECT qr_image FROM extinguisher WHERE id='$id'");
$data = $result->fetch_assoc();

if ($data) {
    $qrPath = "assets/qrcodes/" . $data['qr_image'];

    /* 2. Delete QR image file */
    if (file_exists($qrPath)) {
        unlink($qrPath);
    }

    /* 3. Delete database record */
    $conn->query("DELETE FROM extinguisher WHERE id='$id'");
}

/* 4. Back to main page */
header("Location: main.php");
exit;
?>
