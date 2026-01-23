<?php
session_start();
include "db.php";

// Ensure QR lib exists
require __DIR__ . '/lib/phpqrcode/qrlib.php';

// Only logged-in users
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = intval($_GET['id']); // Prevent injection
$result = $conn->query("SELECT * FROM extinguisher WHERE id=$id");
$data = $result->fetch_assoc();

if (!$data) {
    die("Error: Fire extinguisher not found.");
}

$role = $_SESSION['role'] ?? 'User';
$userName = $_SESSION['user'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = $conn->real_escape_string($_POST['name']);
    $type        = $conn->real_escape_string($_POST['type']);
    $serial_no   = $conn->real_escape_string($_POST['serial_no']);
    $location    = $conn->real_escape_string($_POST['location']);
    $expiry_date = $_POST['expiry_date'];

    $today  = new DateTime(date('Y-m-d'));
    $expiry = new DateTime($expiry_date);
    $years_diff = $today->diff($expiry)->y;

    if ($expiry < $today || $years_diff >= 10) {
        $status = "Expired";
        $days_left = -$today->diff($expiry)->days;
    } else {
        $status = "Active";
        $days_left = $today->diff($expiry)->days;
    }

    // UPDATE
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

    header("Location: main.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Fire Extinguisher</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="theme_css.php">
<style>
/* MAIN THEME COLOR */
:root { --primary-color: #487CB8; }

body {
    background: #0a192f;
    color: #e6f1ff;
    font-family: "Segoe UI", sans-serif;
    padding-top: 80px;
}

/* HEADER STYLE */
.header {
    position: fixed;
    top: 0; left: 0; width: 100%;
    background: #0f172a;
    color: #e6f1ff;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    z-index: 1000;
}
.header h3 { color: var(--primary-color); margin:0; }
.role-mark { font-weight:600; padding:3px 8px; border-radius:6px; }
.role-admin { background-color: var(--primary-color); }
.role-user  { background-color: #64748b; }

/* SIDEBAR */
#sideMenu { position: fixed; top:65px; left:-260px; width:250px; height:100%; background:#0f172a; padding-top:20px; transition:0.3s; z-index:1090; }
#sideMenu a { display:block; padding:12px 20px; color:#93c5fd; text-decoration:none; border-radius:6px; margin-bottom:8px; }
#sideMenu a:hover { background:#1e293b; }

/* MENU TOGGLE */
#menuToggle { background: var(--primary-color); border:none; padding:8px 12px; border-radius:6px; color:white; font-size:18px; cursor:pointer; }

/* FORM CARD */
.card-edit {
    background:#487CB8;
    color:#e6f1ff;
    border-radius:12px;
    padding:20px;
    margin:20px auto;
    max-width:600px;
    box-shadow:0 10px 30px rgba(0,0,0,0.4);
}
.card-edit input,
.card-edit select {
    background:#1e293b !important;
    color:#e6f1ff !important;
    border:1px solid #334155 !important;
}
.card-edit input:focus,
.card-edit select:focus { border-color: var(--primary-color); box-shadow:0 0 0 0.25rem rgba(72,124,184,0.25); color:#fff; }

/* DROPDOWN HOVER COLORS */
option[data-hover-red]:hover { background:#ff4d4d; color:#fff; }
option[data-hover-cream]:hover { background:#fff0b3; color:#000; }
option[data-hover-blue]:hover { background:#4da6ff; color:#fff; }
option[data-hover-black]:hover { background:#333; color:#fff; }
option[data-hover-yellow]:hover { background:#ffff66; color:#000; }

.btn-primary { background: var(--primary-color); border:none; }
.btn-primary:hover { background: #3c6ca1; }
.btn-secondary { background:#64748b; border:none; color:#fff; }
</style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <div>
        <button id="menuToggle">☰</button>
        <h3>Edit Fire Extinguisher</h3>
        <span class="role-mark <?= ($role==='Admin')?'role-admin':'role-user' ?>"><?= $role ?></span>
    </div>
    <div><?= htmlspecialchars($userName) ?> (<?= $role ?>)</div>
</div>

<!-- SIDEBAR -->
<div id="sideMenu">
    <a href="main.php">Main Page</a>
    <a href="view_all.php">View All</a>
    <a href="view_me.php">View Only Me</a>
</div>

<div class="card-edit">
<form method="post">
    <label>Name:</label>
    <input type="text" class="form-control mb-2" name="name" value="<?= htmlspecialchars($data['name']) ?>" required>

    <label>Type:</label>
    <select class="form-control mb-2" name="type" required>
        <option value="A (Water)" <?= $data['type']=='A (Water)'?'selected':'' ?> data-hover-red>Water Extinguisher – Class A</option>
        <option value="B (Foam)" <?= $data['type']=='B (Foam)'?'selected':'' ?> data-hover-cream>Foam Extinguisher – Class A & B</option>
        <option value="C (Dry Powder)" <?= $data['type']=='C (Dry Powder)'?'selected':'' ?> data-hover-blue>Dry Powder – Class A, B & C</option>
        <option value="CO2" <?= $data['type']=='CO2'?'selected':'' ?> data-hover-black>CO₂ – Class B & C</option>
        <option value="K (Wet Chemical)" <?= $data['type']=='K (Wet Chemical)'?'selected':'' ?> data-hover-yellow>Wet Chemical – Class K</option>
    </select>

    <label>Serial No:</label>
    <input type="text" class="form-control mb-2" name="serial_no" value="<?= htmlspecialchars($data['serial_no']) ?>" required>

    <label>Location:</label>
    <input type="text" class="form-control mb-2" name="location" value="<?= htmlspecialchars($data['location']) ?>" required>

    <label>Expiry Date:</label>
    <input type="date" class="form-control mb-2" name="expiry_date" value="<?= $data['expired_date'] ?>" required>

    <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
    <a href="main.php" class="btn btn-secondary mt-3">Cancel</a>
</form>
</div>

<script>
const menu = document.getElementById("sideMenu");
document.getElementById("menuToggle").onclick = () => {
    menu.style.left = menu.style.left === "0px" ? "-260px" : "0px";
};
</script>
</body>
</html>
