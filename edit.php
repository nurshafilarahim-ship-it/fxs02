<?php
include "db.php";
require "phpqrcode/qrlib.php";

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM extinguisher WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = $_POST['name'];
    $type        = $_POST['type'];
    $serial_no   = $_POST['serial_no'];
    $expiry_date = $_POST['expiry_date'];
    $location    = $_POST['location']; // new
    $date_checkup = $data['date_checkup']; // keep original
    $today  = new DateTime($date_checkup);
    $expiry = new DateTime($expiry_date);

    $years_diff = $today->diff($expiry)->y;

    if ($expiry < $today || $years_diff >= 10) {
        $status = "Expired";
        $days_left = -$today->diff($expiry)->days;
    } else {
        $status = "Active";
        $days_left = $today->diff($expiry)->days;
    }

    // UPDATE data
    $conn->query("
        UPDATE extinguisher SET
        name='$name',
        type='$type',
        serial_no='$serial_no',
        location='$location',
        expired_date='$expiry_date',
        days_left='$days_left',
        status='$status',
        edited=1,
        last_edited=NOW()
        WHERE id=$id
    ");

    // QR remains same, it links to view.php?id=...
    header("Location: main.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Fire Extinguisher</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

<h2>Edit Fire Extinguisher</h2>

<form method="post">
    <label>Name:</label>
    <input type="text" class="form-control mb-2" name="name" value="<?= htmlspecialchars($data['name']) ?>" required>

    <label>Type:</label>
    <select class="form-control mb-2" name="type" required>
        <option value="A (Water)" <?= $data['type']=='A (Water)' ? 'selected' : '' ?>>Water Extinguisher – Class A</option>
        <option value="B (Foam)" <?= $data['type']=='B (Foam)' ? 'selected' : '' ?>>Foam Extinguisher – Class A & B</option>
        <option value="C (Dry Powder)" <?= $data['type']=='C (Dry Powder)' ? 'selected' : '' ?>>Dry Powder – Class A, B & C</option>
        <option value="CO2" <?= $data['type']=='CO2' ? 'selected' : '' ?>>CO₂ – Class B & C</option>
        <option value="K (Wet Chemical)" <?= $data['type']=='K (Wet Chemical)' ? 'selected' : '' ?>>Wet Chemical – Class K</option>
    </select>

    <label>Serial No:</label>
    <input type="text" class="form-control mb-2" name="serial_no" value="<?= htmlspecialchars($data['serial_no']) ?>" required>

    <label>Location:</label>
    <input type="text" class="form-control mb-2" name="location" value="<?= htmlspecialchars($data['location']) ?>" required>

    <label>Expiry Date:</label>
    <input type="date" class="form-control mb-2" name="expiry_date" value="<?= $data['expired_date'] ?>" required>

    <button type="submit" class="btn btn-primary">Save Changes</button>
    <a href="main.php" class="btn btn-secondary">Cancel</a>
</form>

</body>
</html>
