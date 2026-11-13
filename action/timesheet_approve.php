<?php
session_start();
require_once("../utils/auth.php");
require_role("Admin");
require_once("../utils/conn.php");

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header("Location: ../admin/timesheets/index.php"); exit; }

$stmt = mysqli_prepare($conn, "UPDATE timesheet SET is_approved = 'true' WHERE id = ? AND is_approved = 'false'");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) === 1) {
    set_flash('success', "Timesheet approved.");
} else {
    set_flash('error', "Timesheet not found or already approved.");
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
header("Location: ../admin/timesheets/index.php");
exit;