<?php
session_start();
include "db.php";

// Allow admin only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    die("Access denied.");
}

// Force Excel download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=fire_extinguisher_report.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Fetch all extinguisher data
$sql = "SELECT e.*, u.username 
        FROM extinguisher e
        LEFT JOIN users u ON e.created_by = u.id
        ORDER BY e.expired_date ASC";
$result = $conn->query($sql);

// Output table
echo "<table border='1'>";
echo "<tr>
        <th>No</th>
        <th>Username</th>
        <th>Name</th>
        <th>Type</th>
        <th>Serial No</th>
        <th>Location</th>
        <th>Expiry Date</th>
        <th>Days Left</th>
        <th>Status</th>
        <th>QR Code</th>
      </tr>";

$no = 1;
while ($row = $result->fetch_assoc()) {

    $daysLeft = (int)date_diff(new DateTime(), new DateTime($row['expired_date']))->format('%r%a');
    $status = ($daysLeft < 0) ? "Expired" : (($daysLeft <= 3) ? "Expiring Soon" : "Active");

    $qrPath = !empty($row['qr_image']) ? "assets/qrcodes/" . $row['qr_image'] : '';

    echo "<tr>
            <td>{$no}</td>
            <td>{$row['username']}</td>
            <td>{$row['name']}</td>
            <td>{$row['type']}</td>
            <td>{$row['serial_no']}</td>
            <td>{$row['location']}</td>
            <td>{$row['expired_date']}</td>
            <td>{$daysLeft}</td>
            <td>{$status}</td>
            <td>";

    if ($qrPath && file_exists($qrPath)) {
        echo "<img src='{$qrPath}' width='70'>";
    } else {
        echo "No QR";
    }

    echo "</td></tr>";

    $no++;
}

echo "</table>";
?>
