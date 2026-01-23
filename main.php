<?php
session_start();
include "db.php";

/* AUTO UPDATE STATUS & DAYS LEFT */
$conn->query("
    UPDATE extinguisher
    SET status = IF(expired_date < CURDATE(), 'Expired', 'Active'),
        days_left = DATEDIFF(expired_date, CURDATE())
");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$search = $_GET['search'] ?? '';
$role   = $_SESSION['role'];
$userId = $_SESSION['user_id'];

// Query extinguisher data with creator role
$query = "SELECT e.*, u.position as creator_role 
          FROM extinguisher e 
          LEFT JOIN users u ON e.created_by = u.id 
          WHERE 1";
if ($search != '') {
    $query .= " AND e.serial_no LIKE '%$search%'";
}
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Fire Extinguisher - Main</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
body {
    margin: 0;
    font-family: "Segoe UI", sans-serif;
    background: #f5f8fc;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    padding-top: 80px; /* space for fixed header */
}

/* HEADER */
.header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: #ffffff;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    z-index: 1000;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 15px;
}

.header h3 {
    color: #1d4ed8;
    margin: 0;
}

.role-mark {
    font-size: 0.9rem;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 6px;
    color: white;
}
.role-admin { background-color: #2563eb; }
.role-user  { background-color: #64748b; }

.header .user-info {
    font-weight: 500;
    color: #1d4ed8;
}

/* SIDEBAR */
#sideMenu {
    position: fixed;
    top: 65px;
    left: -260px;
    width: 250px;
    height: 100%;
    background: #0f172a;
    padding-top: 20px;
    transition: 0.3s;
    z-index: 1090;
}

#sideMenu a {
    display: block;
    padding: 12px 20px;
    color: #93c5fd;
    text-decoration: none;
    border-radius: 6px;
    margin-bottom: 8px;
}

#sideMenu a:hover {
    background: #1e293b;
}

/* MENU TOGGLE */
#menuToggle {
    background: #2563eb;
    border: none;
    padding: 8px 12px;
    border-radius: 6px;
    color: white;
    font-size: 18px;
    cursor: pointer;
}

/* CONTENT CARD */
.content-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 20px;
    margin: 20px auto;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    max-width: 95%;
}

/* SEARCH + ADD */
.top-controls {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    margin-bottom: 15px;
}

.top-controls input.form-control {
    max-width: 300px;
}

/* TABLE */
.table-container {
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 12px;
}

.table th {
    background: #1e293b;
    color: #38bdf8;
    text-align: center;
    padding: 12px;
}

.table td {
    background: #f8fafc !important;
    color: #334155 !important;
    border: 1px solid #cbd5e1;
    text-align: center;
    vertical-align: middle;
    padding: 12px;
}

/* Admin mark */
.admin-mark {
    font-weight: bold;
    color: #0284c7 !important;
}

.admin-label {
    font-size: 0.7rem;
    background: #38bdf8;
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    margin-left: 5px;
}

/* Status badge */
.badge-success { background-color: #22c55e !important; }
.badge-danger { background-color: #dc2626 !important; }

/* Buttons inside table */
.btn-view { background-color: #2563eb; color: white; border: none; }
.btn-view:hover { background-color: #1d4ed8; color: white; }

.btn-warning { background-color: #facc15; border: none; color: #334155; }
.btn-danger { background-color: #dc2626; border: none; color: white; }

</style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <div class="header-left">
        <button id="menuToggle">☰</button>
        <h3>Fire Extinguisher</h3>
        <span class="role-mark <?= ($role === 'Admin') ? 'role-admin' : 'role-user' ?>">
            <?= $role ?>
        </span>
    </div>
    <div class="user-info">
        <?php if($role === 'Admin'): ?>
            <strong><?= htmlspecialchars($_SESSION['user']) ?> (Admin)</strong>
        <?php else: ?>
            <?= htmlspecialchars($_SESSION['user']) ?> (User)
        <?php endif; ?>
        <a href="logout.php" class="btn btn-danger btn-sm ms-2">Logout</a>
    </div>
</div>

<!-- SIDEBAR -->
<div id="sideMenu">
    <a href="index.php">Homepage</a>
    <a href="main.php">Main Page</a>
    <a href="view_all.php">View All</a>
    <a href="view_me.php">View Only Me</a>
</div>

<!-- CONTENT CARD -->
<div class="content-card">

    <!-- SEARCH + ADD -->
    <div class="top-controls mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add New</button>
        <form method="get" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search Serial No" value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-secondary">Search</button>
        </form>
    </div>

    <!-- TABLE -->
    <div class="table-container">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th><th>Type</th><th>Serial No</th><th>Location</th>
                    <th>Expiry</th><th>Status</th><th>QR</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td class="<?= ($row['creator_role'] === 'Admin') ? 'admin-mark' : '' ?>">
                        <?= htmlspecialchars($row['name']) ?>
                        <?php if ($row['creator_role'] === 'Admin'): ?>
                            <span class="admin-label">Admin</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['type']) ?></td>
                    <td><?= htmlspecialchars($row['serial_no']) ?></td>
                    <td><?= htmlspecialchars($row['location']) ?></td>
                    <td><?= $row['expired_date'] ?></td>
                    <td>
                        <span class="badge <?= $row['status']=='Expired' ? 'badge-danger':'badge-success' ?>">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($row['qr_image']): ?>
                            <img src="assets/qrcodes/<?= $row['qr_image'] ?>" width="45">
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="view.php?id=<?= $row['id'] ?>" class="btn btn-view btn-sm">View</a>
                        <?php if ($role === 'Admin'): ?>
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_item.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- INCLUDE ADD MODAL -->
<?php include "pop_add.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const menu = document.getElementById("sideMenu");
document.getElementById("menuToggle").onclick = () => {
    menu.style.left = menu.style.left === "0px" ? "-260px" : "0px";
};
</script>

</body>
</html>
