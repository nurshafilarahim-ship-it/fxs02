<?php
include "db.php";
if (!isset($_SESSION['user'])) header("Location: index.php");

$search = $_GET['search'] ?? '';
$query = "SELECT * FROM extinguisher";
if ($search) {
    $query .= " WHERE id='$search'";
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

<div class="d-flex justify-content-between align-items-center bg-dark text-white p-3">
    <h3>Fire Extinguisher</h3>
    <div>
        Welcome, <?= $_SESSION['user'] ?>
        <a href="logout.php" class="btn btn-danger btn-sm">keluar</a>
    </div>
</div>

<br>

<div class="d-flex justify-content-between">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add</button>

    <form method="get">
        <input type="text" name="search" placeholder="Search by ID">
        <button class="btn btn-secondary">Cari</button>
    </form>
</div>

<br>

<table class="table table-bordered">
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

<?php while($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['name'] ?></td>
<td><?= $row['type'] ?></td>
<td><?= $row['serial_no'] ?></td>
<td><?= $row['date_checkup'] ?></td>
<td><?= $row['expired_date'] ?></td>
<td><?= $row['status'] ?></td>
<td><img src="assets/qrcodes/<?= $row['qr_image'] ?>" width="60"></td>
<td>
    <a href="print_qr.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Print</a>
    <a href="delete_item.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
</td>
</tr>
<?php endwhile; ?>
</table>

<!-- ADD MODAL -->
<?php include "pop_add.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
