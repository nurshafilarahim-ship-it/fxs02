<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = strtolower($_SESSION['role']);
$user_name = $_SESSION['user'] ?? '';

// ----------------------
// Handle Admin Actions
// ----------------------
if ($role === 'admin' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Get user ID by email
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        $uid = $user['id'];

        $stmt = $conn->prepare("INSERT INTO schedule (user_id, user_email, start_date, end_date, created_by) VALUES (?,?,?,?,?)");
        $stmt->bind_param("isssi", $uid, $email, $start_date, $end_date, $user_id);
        $stmt->execute();
        $msg = "Schedule added successfully.";
    } else {
        $error = "User email not found.";
    }
}

// ----------------------
// Delete (Admin Only)
// ----------------------
if ($role === 'admin' && isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM schedule WHERE id=$id");
    header("Location: schedule.php");
    exit();
}

// ----------------------
// Fetch Schedules
// ----------------------
if ($role === 'admin') {
    $sql = "SELECT s.*, u.name FROM schedule s 
            LEFT JOIN users u ON s.user_id=u.id
            ORDER BY s.start_date ASC";
    $result = $conn->query($sql);
} else {
    $stmt = $conn->prepare("SELECT s.*, u.name FROM schedule s 
                             LEFT JOIN users u ON s.user_id=u.id
                             WHERE s.user_id=? 
                             ORDER BY s.start_date ASC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Schedule Checkup</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    margin:0;
    font-family:"Segoe UI",sans-serif;
    background:#f1f5f9;
    min-height:100vh;
    padding-top:80px;
}

.header{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    background:#fff;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
    z-index:1000;
}

.header-left{
    display:flex;
    align-items:center;
    gap:15px;
}

.header h3{
    color:#1d4ed8;
    margin:0;
    font-weight:600;
}

.role-mark{
    font-size:.85rem;
    font-weight:600;
    padding:4px 10px;
    border-radius:20px;
    color:#fff;
}

.role-admin{background:#2563eb;}
.role-user{background:#64748b;}

.user-info{font-weight:500;color:#1d4ed8;}

#sideMenu{
    position:fixed;
    top:65px;
    left:0;
    width:250px;
    height:100%;
    background:#0f172a;
    padding-top:20px;
    transition:.3s;
    transform:translateX(-100%);
    z-index:1090;
    overflow-y:auto;
}

#sideMenu.open{transform:translateX(0);}

#sideMenu a{
    display:block;
    padding:12px 20px;
    color:#93c5fd;
    text-decoration:none;
    border-radius:6px;
    margin-bottom:8px;
}

#sideMenu a:hover{background:#1e293b;}

#menuToggle{
    background:#2563eb;
    border:none;
    padding:6px 10px;
    border-radius:6px;
    color:#fff;
    cursor:pointer;
}

.content-wrapper{
    transition:margin-left .3s;
    padding:20px;
}

.content-card{
    background:#fff;
    border-radius:14px;
    padding:25px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

/* DROPDOWN SIDEBAR */
.dropdown-btn {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 20px;
    width: 100%;
    text-align: left;
    border: none;
    background: #0f172a;
    color: #93c5fd;
    cursor: pointer;
    border-radius: 6px;
    margin-bottom: 8px;
    font-weight: 600;
}

.dropdown-btn:hover {
    background: #1e293b;
}

.dropdown-btn .arrow {
    transition: transform 0.3s ease;
}

.dropdown-btn.active .arrow {
    transform: rotate(90deg);
}

.dropdown-container {
    display: none;
    flex-direction: column;
    padding-left: 10px;
}

.dropdown-container a {
    padding-left: 35px;
    font-size: 0.9rem;
}
</style>
</head>
<body>

<div class="header">
    <div class="header-left">
        <button id="menuToggle">☰</button>
        <h3>Schedule Checkup</h3>
        <span class="role-mark <?= ($role==='admin')?'role-admin':'role-user' ?>">
            <?= ucfirst($role) ?>
        </span>
    </div>
    <div class="user-info">
        <strong><?= htmlspecialchars($user_name) ?></strong>
        <a href="logout.php" class="btn btn-danger btn-sm ms-2">Logout</a>
    </div>
</div>

<!-- SIDEBAR -->
<div id="sideMenu">
    <a href="index.php">Homepage</a>

    <button class="dropdown-btn">🔥 Fire Extinguisher <span class="arrow">▸</span></button>
    <div class="dropdown-container">
        <a href="main_fire.php">Main Page</a>
        <a href="view_all_fire.php">View All</a>
        <a href="view_me_fire.php">View Only Me</a>
        <a href="schedule.php">Schedule</a>
    </div>

    <button class="dropdown-btn">💧 Pump House Inspection <span class="arrow">▸</span></button>
    <div class="dropdown-container">
        <a href="pump_inspection.php">Recorded List</a>
        <a href="pump_schedule.php">Schedule</a>
    </div>
</div>

<div class="content-wrapper" id="contentWrapper">
<div class="content-card">

<h5 class="mb-3">📅 Checkup Schedule</h5>

<?php if($role==='admin'): ?>
<!-- ADD SCHEDULE FORM -->
<form method="post" class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label">User Email</label>
        <input type="email" name="email" class="form-control" required placeholder="user@email.com">
    </div>
    <div class="col-md-3">
        <label class="form-label">Start Date</label>
        <input type="date" name="start_date" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control" required>
    </div>
    <div class="col-md-2 d-grid">
        <label class="form-label invisible">Submit</label>
        <button type="submit" class="btn btn-primary">➕ Add</button>
    </div>
</form>

<?php if(isset($msg)): ?>
<div class="alert alert-success"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>
<?php if(isset($error)): ?>
<div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php endif; ?>

<!-- SCHEDULE TABLE -->
<div class="table-responsive">
<table class="table table-bordered table-hover align-middle">
<thead class="table-dark">
<tr>
    <th>User Name</th>
    <th>Email</th>
    <th>Start Date</th>
    <th>End Date</th>
    <?php if($role==='admin') echo '<th>Action</th>'; ?>
</tr>
</thead>
<tbody>

<?php if($result && $result->num_rows > 0): ?>
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= htmlspecialchars($row['name'] ?? '-') ?></td>
    <td><?= htmlspecialchars($row['user_email']) ?></td>
    <td><?= htmlspecialchars($row['start_date']) ?></td>
    <td><?= htmlspecialchars($row['end_date']) ?></td>
    <?php if($role==='admin'): ?>
    <td>
        <a href="schedule.php?delete=<?= $row['id'] ?>" 
           class="btn btn-outline-danger btn-sm"
           onclick="return confirm('Delete this schedule?')">
           Delete
        </a>
    </td>
    <?php endif; ?>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="<?= $role==='admin'?5:4 ?>" class="text-center text-muted py-4">
        No schedules found.
    </td>
</tr>
<?php endif; ?>

</tbody>
</table>
</div>

</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Sidebar toggle
const menu = document.getElementById("sideMenu");
const wrapper = document.getElementById("contentWrapper");
document.getElementById("menuToggle").onclick = () => {
    menu.classList.toggle('open');
    wrapper.style.marginLeft = menu.classList.contains('open') ? '250px' : '0';
};

// Dropdown toggle
const dropdowns = document.getElementsByClassName("dropdown-btn");
for (let i = 0; i < dropdowns.length; i++) {
    dropdowns[i].addEventListener("click", function() {
        this.classList.toggle("active");
        const container = this.nextElementSibling;
        if (container.style.display === "flex") {
            container.style.display = "none";
        } else {
            container.style.display = "flex";
        }
    });
}
</script>

</body>
</html>
