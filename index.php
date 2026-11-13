<?php
session_start();

if (empty($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit;
}

// Already logged in — route by role
if ($_SESSION['role'] === 'Admin') {
    header("Location: admin/dashboard.php");
} else {
    header("Location: employee/dashboard.php");
}
exit;