/* ===== GLOBAL THEME ===== */
:root {
    --bg-main: #0b1c3d;
    --bg-panel: #102a56;
    --bg-header: #0f2f6f;
    --bg-soft: #1e3a8a;

    --primary: #2563eb;
    --primary-soft: #60a5fa;

    --border: #1e40af;

    --text-main: #ffffff;
    --text-muted: #e5e7eb;
}

/* ===== BASE ===== */
body {
    background: var(--bg-main);
    color: var(--text-main);
    font-family: "Segoe UI", Tahoma, sans-serif;
    font-size: 16px;
    line-height: 1.6;
}

/* ===== HEADERS ===== */
h1, h2, h3, h4, h5 {
    color: var(--text-main);
    font-weight: 600;
}

/* ===== CARDS / CONTAINERS ===== */
.card, .container, .table-container, .custom-dark-modal, form {
    background: var(--bg-panel) !important;
    color: var(--text-main);
    border-radius: 12px;
    border: 1px solid var(--border);
}

/* ===== INPUTS ===== */
input, select, textarea {
    background: #163b7a !important;
    color: var(--text-main) !important;
    border: 1px solid var(--border) !important;
    padding: 10px;
}

input::placeholder {
    color: var(--text-muted);
}

/* ===== BUTTONS ===== */
button, .btn {
    background: linear-gradient(135deg, var(--primary), var(--primary-soft));
    color: white !important;
    border: none;
    font-weight: 600;
}

.btn-secondary {
    background: #334155 !important;
}

/* ===== TABLE ===== */
.table thead th {
    background: var(--bg-header) !important;
    color: white !important;
    font-size: 15px;
}

.table tbody td {
    background: #f8fafc !important;
    color: #0f172a !important;
    font-size: 15px;
}

/* ===== SIDEBAR ===== */
#sidebar, #sideMenu {
    background: var(--bg-header) !important;
}

#sidebar a, #sideMenu a {
    color: white;
    font-size: 16px;
}

#sidebar a:hover, #sideMenu a:hover {
    background: var(--bg-soft);
}

/* ===== FOOTER ===== */
footer {
    background: var(--bg-header);
    color: var(--text-muted);
}

/* ===== BADGES ===== */
.badge {
    font-size: 0.9rem;
    padding: 6px 10px;
}

/* ===== LINKS ===== */
a {
    color: var(--primary-soft);
}

a:hover {
    color: white;
}
