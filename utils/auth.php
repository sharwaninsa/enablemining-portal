<?php
// utils/auth.php  -- include AFTER session_start()

/**
 * Must be called at the top of every protected page.
 * Ensures the user is logged in AND has the required role.
 */
// ... existing content ...
function require_role($role) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // 1. Not logged in → login page
    if (empty($_SESSION['employee_id'])) {
        header("Location: " . base_url() . "login.php");
        exit;
    }

    // 2. Logged in but wrong role → send to their own dashboard
    if ($_SESSION['role'] !== $role) {
        if ($_SESSION['role'] === 'Admin') {
            header("Location: " . base_url() . "admin/dashboard.php");
        } else {
            header("Location: " . base_url() . "employee/dashboard.php");
        }
        exit;
    }
}

/** Simple helper: build an absolute-ish base URL path (works in subfolders) */
function base_url() {
    // Project root is the folder containing index.php.
    // We compute path relative to this file's grandparent (utils/ -> root).
    $script = $_SERVER['SCRIPT_NAME'];          // e.g. /enablemining/admin/dashboard.php
    $root   = str_replace('\\', '/', dirname(__DIR__)); // filesystem path (unused)
    // Simpler: derive from SCRIPT_NAME based on known depth
    // If URL is /app/admin/x.php, base is /app/ ; if /app/x.php, base is /app/
    $parts = explode('/', trim($script, '/'));
    // Try to detect a "root" by removing known subfolder segment
    if (count($parts) >= 2 && in_array($parts[count($parts)-2] ?? '', ['admin','employee','action'])) {
        array_pop($parts); // remove file
        array_pop($parts); // remove subfolder
    } else {
        array_pop($parts); // remove file
    }
    return '/' . implode('/', $parts) . (count($parts) ? '/' : '');
}

function is_admin() {
    return (($_SESSION['role'] ?? '') === 'Admin');
}
function is_employee() {
    return (($_SESSION['role'] ?? '') === 'Employee');
}

// ... existing content ...
require_once __DIR__ . '/flash.php';   // ← add this line at the end