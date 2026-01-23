<?php
session_start();
include "db.php";

$id = intval($_GET['id']);
$data = $conn->query("SELECT * FROM extinguisher WHERE id=$id")->fetch_assoc();

if (!$data) {
    die("Error: Fire extinguisher not found.");
}

$role = $_SESSION['role'] ?? 'User';
$userName = $_SESSION['user'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Fire Extinguisher Info</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
:root { --primary-color: #487CB8; }

body {
    margin: 0;
    font-family: "Segoe UI", sans-serif;
    background: #0a192f;
    color: #fff;
    padding-top: 90px;
}

/* HEADER */
.header {
    position: fixed;
    top:0; left:0; width:100%;
    background:#0f172a;
    padding:15px 20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap: wrap;
    z-index:1000;
}
.header h3 {
    color: var(--primary-color);
    margin:0 10px;
}
.role-mark {
    font-weight:600;
    padding:3px 8px;
    border-radius:6px;
    font-size:14px;
}
.role-admin { background-color: var(--primary-color); }
.role-user  { background-color: #64748b; }

/* SIDEBAR */
#sideMenu {
    position:fixed;
    top:70px;
    left:-260px;
    width:250px;
    height:100%;
    background:#0f172a;
    padding:20px;
    transition:0.3s ease-in-out;
    z-index:1090;
}
#sideMenu a {
    display:block;
    padding:12px 20px;
    color:#93c5fd;
    text-decoration:none;
    border-radius:6px;
    margin-bottom:8px;
}
#sideMenu a:hover { background:#1e293b; }

#menuToggle {
    background: var(--primary-color);
    border:none;
    padding:8px 12px;
    border-radius:6px;
    color:white;
    font-size:18px;
    cursor: pointer;
}

/* INFO CARD */
.container-info {
    max-width:900px;
    margin:20px auto;
    background:#A4BEDC;
    border-radius:12px;
    padding:30px;
    box-shadow:0 10px 30px rgba(0,0,0,0.4);
    position: relative;
}

/* INFO GRID */
.info-grid {
    display:grid;
    grid-template-columns: 1fr 2fr;
    row-gap:15px;
    column-gap:20px;
}
.label { font-weight:600; color: #333; }
.value { font-weight:500; color: #1e293b; }

/* STATUS */
.status {
    padding:6px 14px;
    border-radius:20px;
    font-size:14px;
}
.status.active { background: rgba(72,124,184,0.2); color: var(--primary-color); }
.status.expired { background: rgba(220,38,38,0.15); color: #dc2626; }

/* QR */
.qr-box {
    position:absolute;
    top:30px;
    right:30px;
    text-align:center;
}
.qr-box img {
    background:#fff;
    padding:10px;
    border-radius:10px;
    max-width:140px;
}

/* FOOTER */
.footer {
    margin-top:40px;
    text-align:right;
}
.btn-primary { background: var(--primary-color); border:none; }
.btn-secondary { background:#334155; border:none; }

/* ===================== */
/* 📱 RESPONSIVE PART */
/* ===================== */

@media (max-width: 768px) {
    body { padding-top:100px; }

    /* Hide Toggle, Role Mark, and Username on mobile */
    #menuToggle, .role-mark, .header div:last-child {
        display: none !important;
    }

    .header {
        justify-content: center; /* Center Title */
        text-align: center;
    }

    .container-info {
        margin:15px;
        padding:20px;
        text-align: center; /* Center Text */
    }

    .info-grid {
        grid-template-columns: 1fr;
        justify-items: center; /* Center Grid Items */
    }

    .qr-box {
        position:relative;
        top:auto;
        right:auto;
        margin:20px auto;
    }

    .footer { text-align:center; }
    
    /* Hide Back Button on Mobile */
    .btn-secondary { display: none !important; }

    .footer a {
        display:block;
        width:100%;
        margin-bottom:10px;
    }
}
</style>
</head>

<body>

<div class="header">
    <div class="d-flex align-items-center">
        <button id="menuToggle">☰</button>
        <h3 class="mb-0">Fire Extinguisher Info</h3>
        <span class="ms-2 role-mark <?= ($role==='Admin')?'role-admin':'role-user' ?>"><?= $role ?></span>
    </div>
    <div><?= htmlspecialchars($userName) ?> (<?= $role ?>)</div>
</div>

<div id="sideMenu">
    <a href="main.php">Main Page</a>
    <a href="view_all.php">View All</a>
    <a href="view_me.php">View Only Me</a>
</div>

<div class="container-info">
    <div class="info-grid">
        <div class="label">Name</div>
        <div class="value"><?= htmlspecialchars($data['name']) ?></div>

        <div class="label">Type</div>
        <div class="value"><?= htmlspecialchars($data['type']) ?></div>

        <div class="label">Serial No</div>
        <div class="value"><?= htmlspecialchars($data['serial_no']) ?></div>

        <div class="label">Location</div>
        <div class="value"><?= htmlspecialchars($data['location']) ?></div>

        <div class="label">Check-up Date</div>
        <div class="value"><?= htmlspecialchars($data['date_checkup']) ?></div>

        <div class="label">Status</div>
        <div class="value">
            <span class="status <?= $data['status']=='Expired'?'expired':'active' ?>">
                <?= htmlspecialchars($data['status']) ?>
            </span>
        </div>

        <div class="label">Expiry Info</div>
        <div class="value">
            <?= $data['status']=='Expired'
                ? "Expired for ".abs($data['days_left'])." days"
                : "Days left before expiry: ".$data['days_left']; ?>
        </div>
    </div>

    <div class="qr-box">
        <img src="assets/qrcodes/<?= $data['qr_image'] ?>" alt="QR Code">
    </div>

    <div class="footer">
        <a href="print_qr.php?id=<?= $data['id'] ?>" class="btn btn-primary">Print QR</a>
        <a href="main.php" class="btn btn-secondary">Back</a>
    </div>
</div>

<script>
const menu = document.getElementById("sideMenu");
const toggleBtn = document.getElementById("menuToggle");

toggleBtn.onclick = (e) => {
    e.stopPropagation();
    if (menu.style.left === "0px") {
        menu.style.left = "-260px";
    } else {
        menu.style.left = "0px";
    }
};

document.addEventListener('click', (e) => {
    if (menu.style.left === "0px" && !menu.contains(e.target) && e.target !== toggleBtn) {
        menu.style.left = "-260px";
    }
});
</script>

</body>
</html>