<?php
include "db.php";
require "phpqrcode/qrlib.php";

date_default_timezone_set("Asia/Kuala_Lumpur");

$name      = $_POST['name'];
$type      = $_POST['type'];
$serial_no = $_POST['serial_no'];

/* SERVER DATE */
$date_checkup = date("Y-m-d");

/* AUTO EXPIRE = +10 YEARS */
$expired_date = date("Y-m-d", strtotime("+10 years"));

/* DEFAULT STATUS */
$status = "Active";

/* INSERT DATA FIRST */
$conn->query("
INSERT INTO extinguisher 
(name, type, serial_no, date_checkup, expired_date, status)
VALUES
('$name', '$type', '$serial_no', '$date_checkup', '$expired_date', '$status')
");

/* GET INSERTED ID */
$id = $conn->insert_id;

/* 🔥 DYNAMIC DOMAIN (NO LOCALHOST) */
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$domain   = $_SERVER['HTTP_HOST'];

$qrData = $protocol . $domain . "/view.php?id=" . $id;

/* GENERATE QR */
$qrImage = "qr_" . $id . ".png";
QRcode::png($qrData, "assets/qrcodes/" . $qrImage, QR_ECLEVEL_L, 6);

/* SAVE QR IMAGE NAME */
$conn->query("UPDATE extinguisher SET qr_image='$qrImage' WHERE id=$id");

header("Location: main.php");
?>
