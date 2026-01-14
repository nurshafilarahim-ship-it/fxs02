<?php
include "auth.php";
include "db.php";

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$sql = ($role === 'Admin')
    ? "SELECT * FROM extinguisher"
    : "SELECT * FROM extinguisher WHERE user_id=$user_id";

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
<title>View Only Me</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h3>My Fire Extinguishers</h3>

<table class="table table-bordered text-center">
<tr>
<th>Name</th><th>Type</th><th>Serial</th><th>Action</th>
</tr>

<?php while($row=$result->fetch_assoc()): ?>
<tr>
<td><?=htmlspecialchars($row['name'])?></td>
<td><?=htmlspecialchars($row['type'])?></td>
<td><?=htmlspecialchars($row['serial_no'])?></td>
<td>
<a href="view.php?id=<?=$row['id']?>" class="btn btn-success btn-sm">View</a>
<a href="edit_item.php?id=<?=$row['id']?>" class="btn btn-warning btn-sm">Edit</a>
<a href="delete_item.php?id=<?=$row['id']?>" class="btn btn-danger btn-sm"
onclick="return confirm('Delete?')">Delete</a>
</td>
</tr>
<?php endwhile; ?>

</table>
</body>
</html>
