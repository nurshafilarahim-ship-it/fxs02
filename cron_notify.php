<?php
include "db.php";

$res = $conn->query("
SELECT e.*, u.email
FROM extinguisher e
JOIN users u ON 1=1
WHERE e.days_left = 3
");

while ($row = $res->fetch_assoc()) {
    $msg = "
Fire Extinguisher Alert

Name: {$row['name']}
Serial: {$row['serial_no']}
Expiry in 3 days!
";

    mail($row['email'], "Fire Extinguisher Expiry Alert", $msg);
}
