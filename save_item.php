<?php
include "db.php";
include "lib/phpqrcode/qrlib.php";

/* Ensure QR folder exists */
$qrFolder = "assets/qrcodes/";
if (!is_dir($qrFolder)) {
    mkdir($qrFolder, 0777, true);
}

/* Get form data */
$name = $_POST['name'];
$type = $_POST['type'];
$serial = $_POST['serial_no'];
$expired = $_POST['expired_date'];
$checkup = $_POST['date_checkup'];

/* Auto status */
$today = date("Y-m-d");
$status = ($expired < $today) ? "Expired" : "Active";

/* 1️⃣ Insert data FIRST */
$conn->query("INSERT INTO extinguisher 
(name,type,serial_no,date_checkup,expired_date,status,qr_image)
VALUES
('$name','$type','$serial','$checkup','$expired','$status','')");

/* 2️⃣ Get inserted ID */
$id = $conn->insert_id;

/* 3️⃣ Generate QR (URL-based) */
$qrName = uniqid() . ".png";
$qrPath = $qrFolder . $qrName;

/* ⚠️ Change IP if using phone */
$qrData = "http://localhost/fire_extinguisher/view.php?id=$id";

QRcode::png($qrData, $qrPath, QR_ECLEVEL_L, 4);

/* 4️⃣ Update QR image name */
$conn->query("UPDATE extinguisher SET qr_image='$qrName' WHERE id='$id'");

/* Back to main page */
header("Location: main.php");
exit;
?>
