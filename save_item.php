<?php
session_start();
include "db.php";
require __DIR__ . "/lib/phpqrcode/qrlib.php";

date_default_timezone_set("Asia/Kuala_Lumpur");

if (!isset($_SESSION['user_id'])) {
   die("Unauthorized");
}

$user_id     = $_SESSION['user_id'];
$name        = $_POST['name'] ?? '';
$type        = $_POST['type'] ?? '';
$serial_no   = $_POST['serial_no'] ?? '';
$expiry_date = $_POST['expiry_date'] ?? '';
$location    = $_POST['location'] ?? '';

$date_checkup = date("Y-m-d");
$today  = new DateTime($date_checkup);
$expiry = new DateTime($expiry_date);

// Determine status and days left
if ($expiry < $today) {
    $status = "Expired";
    $days_left = -$today->diff($expiry)->days;
} else {
    $status = "Active";
    $days_left = $today->diff($expiry)->days;
}

/* Insert extinguisher into database, including created_by*/
$stmt = $conn->prepare("
INSERT INTO extinguisher
(user_id, created_by, name, type, serial_no, location, date_checkup, expired_date, days_left, status)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "iissssssds",
    $user_id,      // user_id
    $user_id,      // created_by
    $name,
    $type,
    $serial_no,
    $location,
    $date_checkup,
    $expiry_date,
    $days_left,
    $status
);

if (!$stmt->execute()) {
    die("Insert failed: " . $stmt->error);
}

$id = $conn->insert_id;
 

/* QR code generation*/ 
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$domain   = $_SERVER['HTTP_HOST'];
$qrData   = $protocol . $domain . "/view.php?id=" . $id;

$qrImage = "qr_" . $id . ".png";
QRcode::png($qrData, "assets/qrcodes/" . $qrImage, QR_ECLEVEL_L, 6);

// Update row with QR image
$conn->query("UPDATE extinguisher SET qr_image='$qrImage' WHERE id=$id");

header("Location: main.php");
exit;
