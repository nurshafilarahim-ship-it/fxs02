<?php
include "db.php";

/* GET ITEMS EXPIRE IN 3 DAYS */
$result = $conn->query("
    SELECT e.*, u.email
    FROM extinguisher e
    JOIN users u ON e.user_id = u.id
    WHERE DATEDIFF(e.expired_date, CURDATE()) = .>=3
");

while ($row = $result->fetch_assoc()) {

    $to = $row['email'];
    $subject = "Fire Extinguisher Expiry Reminder";

    $message = "
Fire Extinguisher Reminder

Name: {$row['name']}
Serial: {$row['serial_no']}
Location: {$row['location']}
Expiry Date: {$row['expired_date']}

⚠ This extinguisher will expire in 3 days.
";

    $headers = "From: noreply@yourdomain.com";

    mail($to, $subject, $message, $headers);
}
