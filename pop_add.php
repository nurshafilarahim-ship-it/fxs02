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

$search_serial = $_GET['search'] ?? '';

$sql = "SELECT e.*, u.position AS creator_role 
        FROM extinguisher e
        LEFT JOIN users u ON e.created_by=u.id
        WHERE 1";
if ($search_serial) {
    $sql .= " AND e.serial_no LIKE ?";
}
$sql .= " ORDER BY e.id DESC";

$stmt = $conn->prepare($sql);
if ($search_serial) {
    $like_search = "%$search_serial%";
    $stmt->bind_param("s", $like_search);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Fire Extinguisher</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
:root { --primary-color: #487CB8; }

body {
    margin:0;
    font-family:"Segoe UI",sans-serif;
    background:#f1f5f9;
    min-height:100vh;
    padding-top:80px;
}

/* ===== HEADER ===== */
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

/* ===== SIDEBAR ===== */
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

/* ===== DROPDOWN ===== */
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

.dropdown-btn:hover { background: #1e293b; }

.dropdown-btn .arrow { transition: transform 0.3s ease; }
.dropdown-btn.active .arrow { transform: rotate(90deg); }

.dropdown-container {
    display: none;
    flex-direction: column;
    padding-left: 10px;
}
.dropdown-container a { padding-left: 35px; font-size: 0.9rem; }

/* ===== MAIN CONTENT ===== */
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

.custom-table{
    border-radius:12px;
    overflow:hidden;
}

.main-row{
    background:#e2e8f0;
    transition:.2s;
}

.main-row:hover{
    background:#cbd5e1;
}

.badge{ font-size:0.75rem; }

/* ===== MODAL DESIGN - MATCHES EDIT PAGE ===== */
.custom-add-modal .modal-content {
    background: #487CB8 !important;
    color: #fff !important;
    border-radius: 12px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
}

.custom-add-modal .modal-header {
    border-bottom-color: rgba(255,255,255,0.2);
}

.custom-add-modal .modal-footer {
    border-top-color: rgba(255,255,255,0.2);
}

.custom-add-modal .btn-close {
    filter: brightness(0) invert(1);
}

.text-info-custom {
    color: #EDF2F8 !important;
    font-weight: 600;
    margin-bottom: 4px;
    display: block;
}

.custom-input {
    background: #1e293b !important;
    border: 1px solid #334155 !important;
    color: #fff !important;
    border-radius: 6px;
}

.custom-input:focus {
    background: #2d3a4f !important;
    color: #fff;
    border-color: #38bdf8;
    box-shadow: 0 0 0 0.25rem rgba(56,189,248,0.25);
}

.dropdown-menu-dark-custom {
    background-color: #1e293b;
    border: 1px solid #334155;
}

.dropdown-menu-dark-custom .dropdown-item {
    color: #e2e8f0;
    transition: all 0.2s;
}

.dropdown-menu-dark-custom .dropdown-item:hover {
    background-color: var(--hover-color);
    color: #000 !important;
}

input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1);
}

.warning-text {
    color: #ffb347;
    font-size: 0.75rem;
    margin-top: 4px;
    display: none;
}

.modal-btn-primary {
    background: #0f172a;
    border: none;
    color: white;
}

.modal-btn-primary:hover {
    background: #1e293b;
    color: white;
}
</style>
</head>
<body>

<!-- HEADER -->
<div class="header">
    <div class="header-left">
        <button id="menuToggle">☰</button>
        <h3>Fire Extinguisher</h3>
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

    <!-- Fire Extinguisher Group -->
    <button class="dropdown-btn">🔥 Fire Extinguisher <span class="arrow">▸</span></button>
    <div class="dropdown-container">
        <a href="main.php">Main Page</a>
        <a href="view_all.php">View All</a>
        <a href="view_me.php">View Only Me</a>
    </div>

    <!-- Pump House Group -->
    <button class="dropdown-btn">💧 Pump House Inspection <span class="arrow">▸</span></button>
    <div class="dropdown-container">
        <a href="pump_inspection.php">Recorded List</a>
        <a href="schedule.php">Schedule</a>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="content-wrapper" id="contentWrapper">
<div class="content-card">

<!-- SEARCH FORM -->
<form method="get" class="row g-2 mb-3">
    <div class="col-auto">
        <input type="text" name="search" class="form-control" placeholder="Search Serial No" value="<?= htmlspecialchars($search_serial) ?>">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
        <a href="main.php" class="btn btn-secondary btn-sm">Reset</a>
    </div>
</form>

<!-- ADD / EXPORT -->
<?php if($role==='admin'): ?>
<div class="mb-3">
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addExtinguisherModal">
        ➕ Add New
    </button>
    <a href="export_excel.php" class="btn btn-success btn-sm">📤 Print / Export</a>
</div>
<?php endif; ?>

<!-- TABLE -->
<div class="table-responsive">
<table class="table table-hover align-middle custom-table">
<thead class="table-dark">
<tr>
    <th>Name</th>
    <th>Type</th>
    <th>Serial No</th>
    <th>Location</th>
    <th>Expiry</th>
    <th>Status</th>
    <th>QR</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php if($result && $result->num_rows>0): ?>
<?php while($row=$result->fetch_assoc()): ?>
<tr class="main-row">
    <td><?= htmlspecialchars($row['name']) ?></td>
    <td><?= htmlspecialchars($row['type']) ?></td>
    <td><?= htmlspecialchars($row['serial_no']) ?></td>
    <td><?= htmlspecialchars($row['location']) ?></td>
    <td><?= htmlspecialchars($row['expired_date']) ?></td>
    <td>
        <span class="badge <?= ($row['status']=='Expired')?'bg-danger':'bg-success' ?>"><?= $row['status'] ?></span>
    </td>
    <td><?php if($row['qr_image']): ?><img src="assets/qrcodes/<?= $row['qr_image'] ?>" width="40"><?php endif; ?></td>
    <td>
        <a href="view.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">View</a>
        <?php if($role==='admin'): ?>
            <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="delete_item.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a>
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="8" class="text-center text-muted py-3">No records found.</td></tr>
<?php endif; ?>
</tbody>
</table>
</div>

</div>
</div>

<!-- ========================================= -->
<!-- ADD FIRE EXTINGUISHER MODAL (POPUP) -->
<!-- ========================================= -->
<div class="modal fade custom-add-modal" id="addExtinguisherModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form action="save_item.php" method="post" id="addFormModal">
        <div class="modal-header">
          <h5 class="modal-title">Add Fire Extinguisher</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          
          <!-- Name -->
          <label class="form-label text-info-custom">Name:</label>
          <input type="text" class="form-control custom-input mb-3" name="name" id="modalName" required>

          <!-- Location -->
          <label class="form-label text-info-custom">Location:</label>
          <input type="text" class="form-control custom-input mb-3" name="location" id="modalLocation" required>

          <!-- Type Dropdown -->
          <label class="form-label text-info-custom">Type:</label>
          <div class="dropdown mb-3">
            <button class="btn btn-outline-info w-100 dropdown-toggle text-start" type="button"
                    id="modalTypeDropdown" data-bs-toggle="dropdown" style="color:#fff; border-color:#334155;">
              Select Type
            </button>
            <ul class="dropdown-menu w-100 dropdown-menu-dark-custom">
              <li><a class="dropdown-item" href="#" data-value="A (Water)" style="--hover-color:#ff4d4d;">Water – Class A</a></li>
              <li><a class="dropdown-item" href="#" data-value="B (Foam)" style="--hover-color:#fff0b3;">Foam – Class A & B</a></li>
              <li><a class="dropdown-item" href="#" data-value="C (Dry Powder)" style="--hover-color:#4da6ff;">Dry Powder – Class A,B,C</a></li>
              <li><a class="dropdown-item" href="#" data-value="CO2" style="--hover-color:#333333;">CO₂ – Class B,C</a></li>
              <li><a class="dropdown-item" href="#" data-value="K (Wet Chemical)" style="--hover-color:#ffff66;">Wet Chemical – Class K</a></li>
            </ul>
            <input type="hidden" name="type" id="modalTypeInput" required>
          </div>

          <!-- Serial No -->
          <label class="form-label text-info-custom">Serial No:</label>
          <input type="text" class="form-control custom-input mb-3" name="serial_no" id="modalSerial" required>

          <!-- Expiry Date -->
          <label class="form-label text-info-custom">Expiry Date:</label>
          <input type="date" class="form-control custom-input mb-2" name="expiry_date" id="modalExpiryDate" required>
          <small class="warning-text" id="modalExpiryWarning">
            ⚠️ Expiry date cannot be more than 10 years from today!
          </small>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn modal-btn-primary px-4">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Sidebar toggle
const menu = document.getElementById("sideMenu");
const wrapper = document.getElementById("contentWrapper");
const menuToggle = document.getElementById("menuToggle");
if(menuToggle) {
    menuToggle.onclick = () => {
        menu.classList.toggle('open');
        wrapper.style.marginLeft = menu.classList.contains('open') ? '250px' : '0';
    };
}

// Sidebar dropdown toggle
const dropdowns = document.getElementsByClassName("dropdown-btn");
for (let i = 0; i < dropdowns.length; i++) {
    dropdowns[i].addEventListener("click", function() {
        this.classList.toggle("active");
        const container = this.nextElementSibling;
        if (container.style.display === "flex") container.style.display = "none";
        else container.style.display = "flex";
    });
}

// ========== MODAL DROPDOWN LOGIC ==========
const modalDropdownItems = document.querySelectorAll('#addExtinguisherModal .dropdown-menu .dropdown-item');
const modalDropdownBtn = document.getElementById('modalTypeDropdown');
const modalTypeInput = document.getElementById('modalTypeInput');

if(modalDropdownItems.length && modalDropdownBtn && modalTypeInput) {
    modalDropdownItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            modalDropdownItems.forEach(i => i.classList.remove('active-type'));
            this.classList.add('active-type');
            modalDropdownBtn.textContent = this.textContent;
            modalTypeInput.value = this.dataset.value;
        });
    });
}

// ========== EXPIRY DATE VALIDATION ==========
const expiryDateInput = document.getElementById('modalExpiryDate');
const expiryWarningSpan = document.getElementById('modalExpiryWarning');

function validateExpiryDate(dateValue) {
    if(!dateValue) return true;
    const selectedDate = new Date(dateValue);
    const today = new Date();
    today.setHours(0,0,0,0);
    const maxDate = new Date();
    maxDate.setFullYear(today.getFullYear() + 10);
    maxDate.setHours(0,0,0,0);
    
    if(selectedDate > maxDate) {
        if(expiryWarningSpan) expiryWarningSpan.style.display = 'block';
        return false;
    } else {
        if(expiryWarningSpan) expiryWarningSpan.style.display = 'none';
        return true;
    }
}

if(expiryDateInput) {
    expiryDateInput.addEventListener('change', function() {
        if(!validateExpiryDate(this.value)) {
            this.value = '';
            this.focus();
        }
    });
}

// ========== FORM SUBMIT VALIDATION ==========
const addModalForm = document.getElementById('addFormModal');
if(addModalForm) {
    addModalForm.addEventListener('submit', function(e) {
        if(!modalTypeInput.value) {
            e.preventDefault();
            alert('❌ Please select a fire extinguisher type.');
            return false;
        }
        
        const expiryVal = expiryDateInput.value;
        if(!expiryVal) {
            e.preventDefault();
            alert('❌ Please select an expiry date.');
            return false;
        }
        
        const selectedDate = new Date(expiryVal);
        const today = new Date();
        today.setHours(0,0,0,0);
        const maxDate = new Date();
        maxDate.setFullYear(today.getFullYear() + 10);
        maxDate.setHours(0,0,0,0);
        if(selectedDate > maxDate) {
            e.preventDefault();
            alert('❌ Expiry date cannot be more than 10 years from today!');
            return false;
        }
        
        return true;
    });
}

// Reset modal when closed
const addModal = document.getElementById('addExtinguisherModal');
if(addModal) {
    addModal.addEventListener('hidden.bs.modal', function () {
        const form = document.getElementById('addFormModal');
        if(form) form.reset();
        if(modalDropdownBtn) modalDropdownBtn.textContent = 'Select Type';
        if(modalTypeInput) modalTypeInput.value = '';
        if(expiryWarningSpan) expiryWarningSpan.style.display = 'none';
        if(modalDropdownItems) {
            modalDropdownItems.forEach(i => i.classList.remove('active-type'));
        }
    });
}
</script>

</body>
</html>