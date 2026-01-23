<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Fire Extinguisher System</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
/* ===== GLOBAL ===== */
body {
    margin: 0;
    font-family: "Segoe UI", sans-serif;
    background: #f5f8fc;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

/* ===== HEADER ===== */
.header {
    height: 65px;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 25px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
}

.menu-btn {
    font-size: 22px;
    color: #2563eb;
    cursor: pointer;
}

.profile-icon {
    font-size: 26px;
    color: #2563eb;
    text-decoration: none;
}

/* ===== SIDEBAR ===== */
#sidebar {
    position: fixed;
    top: 65px;
    left: -260px;
    width: 250px;
    height: 100%;
    background: #0f172a;
    padding: 20px;
    transition: 0.3s;
    z-index: 999;
}

#sidebar a {
    display: block;
    padding: 12px;
    color: #93c5fd;
    text-decoration: none;
    border-radius: 6px;
    margin-bottom: 8px;
}

#sidebar a:hover {
    background: #1e293b;
}

/* ===== MAIN CONTENT ===== */
.main-content {
    flex: 1;
    padding-top: 110px;
    text-align: center;
}

.logo {
    max-width: 160px;
    margin-bottom: 15px;
}

.main-content h1 {
    color: #1d4ed8;
    font-weight: 700;
}

.subtitle {
    color: #475569;
    margin-bottom: 35px;
}

.hero-img {
    max-width: 520px;
    border-radius: 14px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

/* ===== FOOTER ===== */
footer {
    background: #ffffff;
    text-align: center;
    padding: 12px;
    font-size: 14px;
    color: #64748b;
    border-top: 1px solid #e5e7eb;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <span class="menu-btn" onclick="toggleMenu()">☰</span>

    <a href="register.php" class="profile-icon">
        <i class="fa-solid fa-circle-user"></i>
    </a>
</div>

<!-- SIDEBAR -->
<div id="sidebar">
    <a href="index.php">Homepage</a>
    <a href="#" onclick="checkLogin('main.php')">Main Page</a>
    <a href="#" onclick="checkLogin('view_all.php')">View All</a>
    <a href="#" onclick="checkLogin('view_me.php')">View Only Me</a>
</div>

<!-- CONTENT -->
<div class="main-content">
    <img src="assets/logo_lunas.webp" class="logo" alt="Lunas Logo">

    <h1>Fire Extinguisher System</h1>
    <p class="subtitle">Monitor • Manage • Maintain Fire Safety Equipment</p>

    <!-- Slideshow container -->
    <div class="slideshow-container">
        <img class="hero-img" src="https://fireextinguishersgoldcoast.com.au/wp-content/uploads/2017/04/fire-extinguisher-types-1536x811.jpg" alt="Slide 1">
        <img class="hero-img" src="https://www.artisanfire.co.uk/wp-content/uploads/fire-extinguisher.jpg" alt="Slide 2">
        <img class="hero-img" src="https://pyebarkerfs.com/wp-content/uploads/2024/10/tag-inspection.png" alt="Slide 3">
    </div>
</div>

<style>
.slideshow-container {
    display: flex;
    justify-content: center; /* center horizontally */
    align-items: center;     /* center vertically if container has height */
    overflow: hidden;
    max-width: 800px;        /* optional: limit width */
    margin: 20px auto;       /* center container horizontally on page */
}

.hero-img {
    max-width: 100%;
    max-height: 400px;
    object-fit: cover;
    display: none; /* Hide all images initially */
    border-radius: 12px;
}
</style>

<script>
// JavaScript for slideshow
let slideIndex = 0;
const slides = document.querySelectorAll('.hero-img');

function showSlides() {
    slides.forEach(img => img.style.display = 'none'); // Hide all
    slideIndex++;
    if (slideIndex > slides.length) slideIndex = 1; // Loop back
    slides[slideIndex - 1].style.display = 'block'; // Show current
    setTimeout(showSlides, 3000); // Change every 3 seconds
}

// Start slideshow
showSlides();
</script>


<!-- FOOTER -->
<footer>
    © 2026 Fire Extinguisher Management System | All Rights Reserved
</footer>

<script>
function toggleMenu(){
    let s = document.getElementById("sidebar");
    s.style.left = (s.style.left === "0px") ? "-260px" : "0px";
}

const isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;

function checkLogin(page) {
    if (!isLoggedIn) {
        alert("Please login or register first.");
        window.location.href = "register.php";
    } else {
        window.location.href = page;
    }
}
</script>

</body>
</html>
