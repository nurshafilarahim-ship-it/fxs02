<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer

include "db.php";

// Fetch extinguishers expiring in 1, 2, or 3 days
$sql = "
    SELECT e.*, u.email
    FROM extinguisher e
    JOIN users u ON e.user_id = u.id
    WHERE DATEDIFF(e.expired_date, CURDATE()) BETWEEN 1 AND 3
";

$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "No extinguishers expiring in the next 3 days.";
} else {
    while ($row = $result->fetch_assoc()) {

        $days_left = (strtotime($row['expired_date']) - strtotime(date('Y-m-d'))) / 86400;

        $message = "
Fire Extinguisher Reminder

Name: {$row['name']}
Serial: {$row['serial_no']}
Location: {$row['location']}
Expiry Date: {$row['expired_date']}

⚠ This extinguisher will expire in {$days_left} day(s).
";

        // Create PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';        // Your SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = 'your_email@gmail.com';  // Your email
            $mail->Password   = 'your_app_password';     // Use App Password if Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('noreply@yourdomain.com', 'Fire Extinguisher Alert');
            $mail->addAddress($row['email']);           // Recipient email

            $mail->Subject = "Fire Extinguisher Expiry Reminder";
            $mail->Body    = $message;

            $mail->send();
            echo "Reminder sent to {$row['email']}<br>";
        } catch (Exception $e) {
            echo "Failed to send to {$row['email']}: {$mail->ErrorInfo}<br>";
        }
    }
}
?>
