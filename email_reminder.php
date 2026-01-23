<?php
// ============================
// FIRE EXTINGUISHER EMAIL REMINDER
// ============================

// Show all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log file
$logFile = __DIR__ . '/cron_test.log';
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Cron started\n", FILE_APPEND);

// Include DB and PHPMailer
include __DIR__ . '/db.php';
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    // ============================
    // FETCH EXTINGUISHERS EXPIRING IN 1-3 DAYS
    // ============================
    $stmt = $conn->prepare("
        SELECT e.*, u.email, u.name AS user_name
        FROM extinguisher e
        LEFT JOIN users u ON e.created_by = u.id
        WHERE DATEDIFF(e.expired_date, CURDATE()) BETWEEN 1 AND 3
    ");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - No expiries found\n", FILE_APPEND);
    }

    // ============================
    // SETUP PHPMailer
    // ============================
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'mr.rusydanofficial@gmail.com';
    $mail->Password   = 'ohindcatrypzpbjl';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->setFrom('mr.rusydanofficial@gmail.com', 'Fire Extinguisher Alert');

    // ============================
    // LOOP AND SEND EMAILS
    // ============================
    while ($row = $result->fetch_assoc()) {
        $today = new DateTime(date('Y-m-d'));
        $expiry = new DateTime($row['expired_date']);

       $daysLeft = (int)$today->diff($expiry)->format('%r%a');

		if ($daysLeft >= 0 && $daysLeft <= 3) {
        // send email
        }

        try {
            $mail->addAddress($row['email']);
            $mail->Subject = "⚠ Expiry Reminder ({$daysLeft} days left)";
            $mail->Body    = "
Hello {$row['user_name']},

Your fire extinguisher will expire in {$daysLeft} day(s).

Name: {$row['name']}
Serial: {$row['serial_no']}
Location: {$row['location']}
Expiry: {$row['expired_date']}

Please take action.
";

            $mail->send();
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Email sent to {$row['email']}\n", FILE_APPEND);

            $mail->clearAddresses(); // clear for next recipient

        } catch (Exception $e) {
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - Mail error to {$row['email']}: {$mail->ErrorInfo}\n", FILE_APPEND);
        }
    }

    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Cron finished ✅\n", FILE_APPEND);

} catch (Exception $e) {
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Cron failed ❌: {$e->getMessage()}\n", FILE_APPEND);
}
