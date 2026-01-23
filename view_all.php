<?php
include "auth.php";
include "db.php";

$result = $conn->query("SELECT e.*, u.position as creator_role 
                        FROM extinguisher e 
                        LEFT JOIN users u ON e.created_by = u.id");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>View All</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#0a192f; color:#fff; font-family:'Segoe UI', sans-serif; min-height:100vh; padding:20px;">

<h3 style="color:#38bdf8;">View All Fire Extinguishers</h3>

<div class="table-container" style="overflow-x:auto; border-radius:12px; border:1px solid #334155; background:#0f172a;">
<table class="table table-bordered text-center m-0" style="color:#fff;">
    <thead style="background:#1e293b; color:#38bdf8;">
        <tr>
            <th>Name</th><th>Type</th><th>Serial No</th><th>Location</th>
            <th>Expiry</th><th>Status</th><th>QR</th><th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php while($row=$result->fetch_assoc()): ?>
        <tr style="background:#0f172a; color:#fff;">
            <td class="<?= ($row['creator_role'] === 'Admin') ? 'admin-mark' : '' ?>">
                <?= htmlspecialchars($row['name']) ?>
                <?php if ($row['creator_role']==='Admin'): ?>
                    <span class="admin-label">Admin</span>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['type']) ?></td>
            <td><?= htmlspecialchars($row['serial_no']) ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td><?= $row['expired_date'] ?></td>
            <td>
                <span class="badge <?= $row['status']=='Expired'?'badge-danger':'badge-success' ?>">
                    <?= $row['status'] ?>
                </span>
            </td>
            <td>
                <?php if($row['qr_image']): ?>
                    <img src="assets/qrcodes/<?= $row['qr_image'] ?>" width="45">
                <?php endif; ?>
            </td>
            <td>
                <a href="view.php?id=<?= $row['id'] ?>" class="btn btn-view btn-sm">View</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
</div>
<br>
<a href="main.php" class="btn btn-secondary">Back to Main</a>

<style>
.admin-mark { font-weight:bold; color:#0284c7; }
.admin-label { font-size:0.7rem; background:#38bdf8; color:#fff; padding:2px 6px; border-radius:4px; margin-left:5px; }
.badge-success { background:#22c55e; }
.badge-danger { background:#dc2626; }
.btn-view { background:#2563eb; color:#fff; border:none; }
.btn-view:hover { background:#1d4ed8; }
</style>
</body>
</html>
