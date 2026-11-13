<?php
session_start();
require_once("../utils/auth.php");
require_role("Employee");
require_once("../utils/conn.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../employee/timesheet/index.php"); exit;
}

$employee_id = $_SESSION['employee_id'];
$today       = date('Y-m-d');
$login       = $_POST['login_time']  ?? '';
$logout      = $_POST['logout_time'] ?? '';
$break       = $_POST['break_time']  ?? '';
$resume      = $_POST['resume_time'] ?? '';
$progress    = trim($_POST['progress'] ?? '');

// ---- Validation ----
if ($login === '' || $logout === '' || $progress === '') {
    set_flash('error', "Login, Logout and Progress are required.");
    header("Location: ../employee/timesheet/create.php"); exit;
}
if (($break === '') !== ($resume === '')) {
    set_flash('error', "Please provide both break and resume times, or neither.");
    header("Location: ../employee/timesheet/create.php"); exit;
}

// Normalize to HH:MM:SS
$login  = date('H:i:s', strtotime($login));
$logout = date('H:i:s', strtotime($logout));
$break  = $break  ? date('H:i:s', strtotime($break))  : '00:00:00';
$resume = $resume ? date('H:i:s', strtotime($resume)) : '00:00:00';

// ---- Compute hours worked (handles overnight shifts) ----
$loginTs  = strtotime($today . ' ' . $login);
$logoutTs = strtotime($today . ' ' . $logout);
if ($logoutTs <= $loginTs) $logoutTs += 86400;             // overnight

$breakSec = 0;
if ($break !== '00:00:00' && $resume !== '00:00:00') {
    $bTs = strtotime($today . ' ' . $break);
    $rTs = strtotime($today . ' ' . $resume);
    if ($rTs <= $bTs) $rTs += 86400;                       // overnight break
    $breakSec = $rTs - $bTs;
    if ($breakSec < 0) $breakSec = 0;
}

$workedSec = max(0, ($logoutTs - $loginTs) - $breakSec);
$hours_worked = sprintf('%02d:%02d', intdiv($workedSec, 3600), intdiv($workedSec % 3600, 60));

// ---- Metadata (auto-derived) ----
$today_day = date('l');                                    // e.g. Thursday
$week_day  = 'Week ' . date('W');                          // ISO week number
$is_approved = 'false';

// ---- Duplicate guard (one per employee per day) ----
$chk = mysqli_prepare($conn, "SELECT id FROM timesheet WHERE employee_id = ? AND today_date = ? LIMIT 1");
mysqli_stmt_bind_param($chk, "ss", $employee_id, $today);
mysqli_stmt_execute($chk);
mysqli_stmt_store_result($chk);
if (mysqli_stmt_num_rows($chk) > 0) {
    mysqli_stmt_close($chk);
    set_flash('error', "You already submitted today's timesheet.");
    header("Location: ../employee/timesheet/index.php"); exit;
}
mysqli_stmt_close($chk);

// ---- Insert ----
$sql = "INSERT INTO timesheet
    (employee_id, today_date, today_day, week_day,
     login_time, break_time, resume_time, logout_time,
     progress, hours_worked, is_approved)
    VALUES (?,?,?,?,?,?,?,?,?,?,?)";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param(
    $stmt, "sssssssssss",
    $employee_id, $today, $today_day, $week_day,
    $login, $break, $resume, $logout,
    $progress, $hours_worked, $is_approved
);

if (mysqli_stmt_execute($stmt)) {
    set_flash('success', "Timesheet submitted. Hours worked: $hours_worked");
} else {
    error_log("timesheet_create: " . mysqli_stmt_error($stmt));
    set_flash('error', "Failed to submit timesheet.");
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
header("Location: ../employee/timesheet/index.php");
exit;