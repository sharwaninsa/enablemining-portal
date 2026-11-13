<?php
session_start();
require_once("../utils/auth.php");
require_role("Admin");
require_once("../utils/conn.php");

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header("Location: ../admin/users/index.php"); exit; }

// Prevent self-delete
$me = mysqli_prepare($conn, "SELECT employee_id FROM users WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($me, "i", $id);
mysqli_stmt_execute($me);
mysqli_stmt_store_result($me);
mysqli_stmt_bind_result($me, $target_emp);
mysqli_stmt_fetch($me);
mysqli_stmt_close($me);

if ($target_emp === $_SESSION['employee_id']) {
    set_flash('error', "You cannot delete your own account.");
    header("Location: ../admin/users/index.php"); exit;
}

// Delete (leaves FK is CASCADE-by-hand → delete child rows first)
$del1 = mysqli_prepare($conn, "DELETE FROM leaves WHERE employee_id = ?");
mysqli_stmt_bind_param($del1, "s", $target_emp);
mysqli_stmt_execute($del1);
mysqli_stmt_close($del1);

$del2 = mysqli_prepare($conn, "DELETE FROM timesheet WHERE employee_id = ?");
mysqli_stmt_bind_param($del2, "s", $target_emp);
mysqli_stmt_execute($del2);
mysqli_stmt_close($del2);

$del3 = mysqli_prepare($conn, "DELETE FROM payroll WHERE employee_id = ?");
mysqli_stmt_bind_param($del3, "s", $target_emp);
mysqli_stmt_execute($del3);
mysqli_stmt_close($del3);

$del4 = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
mysqli_stmt_bind_param($del4, "i", $id);
if (mysqli_stmt_execute($del4)) {
    set_flash('success', "User deleted.");
} else {
    set_flash('error', "Delete failed.");
}
mysqli_stmt_close($del4);
mysqli_close($conn);
header("Location: ../admin/users/index.php");
exit;