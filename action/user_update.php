<?php
session_start();
require_once("../utils/auth.php");
require_role("Admin");
require_once("../utils/conn.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../admin/users/index.php"); exit;
}

$id              = (int)($_POST['id'] ?? 0);
$employee_id     = trim($_POST['employee_id'] ?? '');
$name            = trim($_POST['name'] ?? '');
$designation     = trim($_POST['designation'] ?? '');
$role            = ($_POST['role'] ?? '') === 'Admin' ? 'Admin' : 'Employee';
$email           = trim($_POST['email'] ?? '');
$phone           = trim($_POST['phone'] ?? '');
$joining_date    = trim($_POST['joining_date'] ?? '');
$pan_number      = strtoupper(trim($_POST['pan_number'] ?? ''));
$bank_acc_number = trim($_POST['bank_acc_number'] ?? '');
$bank_ifsc       = strtoupper(trim($_POST['bank_ifsc'] ?? ''));
$annual_ctc      = trim($_POST['annual_ctc'] ?? '');
$monthly_salary  = trim($_POST['monthly_salary'] ?? '');
$new_password    = $_POST['password'] ?? '';

if ($id <= 0 || $employee_id === '' || $email === '') {
    set_flash('error', "Missing required fields.");
    header("Location: ../admin/users/index.php"); exit;
}

// Check duplicate employee_id / email for OTHER users
$chk = mysqli_prepare($conn, "SELECT id FROM users WHERE (employee_id = ? OR email = ?) AND id <> ? LIMIT 1");
mysqli_stmt_bind_param($chk, "ssi", $employee_id, $email, $id);
mysqli_stmt_execute($chk);
mysqli_stmt_store_result($chk);
if (mysqli_stmt_num_rows($chk) > 0) {
    mysqli_stmt_close($chk);
    set_flash('error', "Employee ID or Email already used by another user.");
    header("Location: ../admin/users/edit.php?id=$id"); exit;
}
mysqli_stmt_close($chk);

if ($new_password !== '') {
    $hash = password_hash($new_password, PASSWORD_DEFAULT);
    $sql = "UPDATE users SET employee_id=?, name=?, designation=?, role=?, email=?, phone=?,
            joining_date=?, pan_number=?, bank_acc_number=?, bank_ifsc=?,
            annual_ctc=?, monthly_salary=?, password=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssssssssi",
        $employee_id, $name, $designation, $role, $email, $phone,
        $joining_date, $pan_number, $bank_acc_number, $bank_ifsc,
        $annual_ctc, $monthly_salary, $hash, $id);
} else {
    $sql = "UPDATE users SET employee_id=?, name=?, designation=?, role=?, email=?, phone=?,
            joining_date=?, pan_number=?, bank_acc_number=?, bank_ifsc=?,
            annual_ctc=?, monthly_salary=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssssssssi",
        $employee_id, $name, $designation, $role, $email, $phone,
        $joining_date, $pan_number, $bank_acc_number, $bank_ifsc,
        $annual_ctc, $monthly_salary, $id);
}

if (mysqli_stmt_execute($stmt)) {
    // If admin edited themselves → keep session role in sync
    if ($id === (int)($_SESSION['id'] ?? 0)) { $_SESSION['role'] = $role; }
    set_flash('success', "User updated.");
} else {
    error_log("user_update: " . mysqli_stmt_error($stmt));
    set_flash('error', "Update failed.");
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
header("Location: ../admin/users/index.php");
exit;