<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$search = $_GET['search'] ?? '';

$query = "SELECT * FROM extinguisher";
if ($search != '') {
    $query .= " WHERE id = '$search'";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Fire Extinguisher</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="p-4">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center bg-dark text-white p-3">
    <h3>Fire Extinguisher</h3>
    <div>
        Welcome, <?= $_SESSION['user'] ?>
        <a href="logout.php" class="btn btn-danger btn-sm">Keluar</a>
    </div>
</div>

<br>

<!-- ADD + SEARCH -->
<div class="d-flex justify-content-between">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        Add
    </button>

    <form method="get" class="d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Search by ID" value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-secondary">Cari</button>
    </form>
</div>

<br>

<!-- TABLE -->
<table class="table table-bordered table-striped align-middle text-center">
<tr>
    <th>Name</th>
    <th>Type</th>
    <th>Serial No</th>
    <th>Date Checkup</th>
    <th>Expired Date</th>
    <th>Status</th>
    <th>QR</th>
    <th>Action</th>
</tr>

<?php while ($row = $result->fetch_assoc()): ?>

<?php
    // AUTO STATUS CALCULATION (SAFETY)
    $checkup = new DateTime($row['date_checkup']);
    $expiry  = clone $checkup;
    $expiry->modify('+10 years');

    $today = new DateTime();

    if ($today >= $expiry) {
        $status = "<span class='badge bg-danger'>Expired</span>";
    } else {
        $status = "<span class='badge bg-success'>Active</span>";
    }
?>

<tr>
    <td><?= htmlspecialchars($row['name']) ?></td>
    <td><?= htmlspecialchars($row['type']) ?></td>
    <td><?= htmlspecialchars($row['serial_no']) ?></td>
    <td><?= $row['date_checkup'] ?></td>
    <td><?= $expiry->format('Y-m-d') ?></td>
    <td><?= $status ?></td>
    <td>
        <?php if (!empty($row['qr_image'])): ?>
            <img src="assets/qrcodes/<?= $row['qr_image'] ?>" width="60">
        <?php endif; ?>
    </td>
    <td>
        <a href="print_qr.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm mb-1">
            Print
        </a>
        <a href="delete_item.php?id=<?= $row['id'] ?>" 
           class="btn btn-danger btn-sm mb-1"
           onclick="return confirm('Delete this record?')">
            Delete
        </a>
    </td>
</tr>

<?php endwhile; ?>

</table>

<!-- ADD MODAL -->
<?php include "pop_add.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
