<?php
include 'db.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

$result = $conn->query("SELECT email FROM users WHERE role = 'user'");

while ($row = $result->fetch_assoc()) {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'mfplunas01@mylunas.com.my';
    $mail->Password = 'Welcome@2026';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('mfplunas01@mylunas.com.my', 'Pump Inspection System');
    $mail->addAddress($row['email']);

    $mail->Subject = 'Daily Pump House Inspection Reminder';
    $mail->Body = 'Please remember to inspect the pump house today. Thank you.';

    $mail->send();
}
