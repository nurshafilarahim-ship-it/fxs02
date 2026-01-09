<?php
include "db.php";
require "phpqrcode/qrlib.php";

date_default_timezone_set("Asia/Kuala_Lumpur");

/* FORM INPUT */
$name        = $_POST['name'];
$type        = $_POST['type'];
$serial_no   = $_POST['serial_no'];
$expiry_date = $_POST['expiry_date'];

/* SERVER DATE */
$date_checkup = date("Y-m-d");

/* DATE OBJECTS */
$today  = new DateTime($date_checkup);
$expiry = new DateTime($expiry_date);

/* YEARS DIFFERENCE */
$years_diff = $today->diff($expiry)->y;

/* STATUS + DAYS LOGIC */
if ($expiry < $today || $years_diff >= 10) {
    $status = "Expired";
    $days_left = -$today->diff($expiry)->days; // days overdue
} else {
    $status = "Active";
    $days_left = $today->diff($expiry)->days; // days remaining
}

/* INSERT DATA */
$conn->query("
INSERT INTO extinguisher
(name, type, serial_no, date_checkup, expired_date, days_left, status)
VALUES
('$name', '$type', '$serial_no', '$date_checkup', '$expiry_date', '$days_left', '$status')
");

/* GET ID */
$id = $conn->insert_id;

/* DYNAMIC DOMAIN (MOBILE SAFE) */
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$domain   = $_SERVER['HTTP_HOST'];

/* QR CONTENT */
$qrData = $protocol . $domain . "/view.php?id=" . $id;

/* GENERATE QR */
$qrImage = "qr_" . $id . ".png";
QRcode::png($qrData, "assets/qrcodes/" . $qrImage, QR_ECLEVEL_L, 6);

/* SAVE QR */
$conn->query("UPDATE extinguisher SET qr_image='$qrImage' WHERE id=$id");

header("Location: main.php");
exit;
?>
