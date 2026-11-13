<?php
session_start();
require_once("../utils/auth.php");
require_role("Admin");
require_once("../utils/conn.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../admin/users/index.php"); exit;
}

$required = ['employee_id','name','designation','role','email','phone','password',
             'joining_date','pan_number','bank_acc_number','bank_ifsc',
             'annual_ctc','monthly_salary'];
foreach ($required as $f) {
    if (trim($_POST[$f] ?? '') === '') {
        set_flash('error', "Field '$f' is required.");
        header("Location: ../admin/users/create.php"); exit;
    }
}

$employee_id     = trim($_POST['employee_id']);
$name            = trim($_POST['name']);
$designation     = trim($_POST['designation']);
$role            = $_POST['role'] === 'Admin' ? 'Admin' : 'Employee';
$email           = trim($_POST['email']);
$phone           = trim($_POST['phone']);
$password_hash   = password_hash($_POST['password'], PASSWORD_DEFAULT);
$joining_date    = trim($_POST['joining_date']);
$pan_number      = strtoupper(trim($_POST['pan_number']));
$bank_acc_number = trim($_POST['bank_acc_number']);
$bank_ifsc       = strtoupper(trim($_POST['bank_ifsc']));
$annual_ctc      = trim($_POST['annual_ctc']);
$monthly_salary  = trim($_POST['monthly_salary']);

// Duplicate employee_id / email?
$check = mysqli_prepare($conn, "SELECT id FROM users WHERE employee_id = ? OR email = ? LIMIT 1");
mysqli_stmt_bind_param($check, "ss", $employee_id, $email);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);
if (mysqli_stmt_num_rows($check) > 0) {
    mysqli_stmt_close($check);
    set_flash('error', "Employee ID or Email already exists.");
    header("Location: ../admin/users/create.php"); exit;
}
mysqli_stmt_close($check);

$sql = "INSERT INTO users
    (employee_id, name, designation, pan_number, bank_acc_number, bank_ifsc,
     annual_ctc, monthly_salary, joining_date, leaving_date, role, email, phone, password)
    VALUES (?,?,?,?,?,?,?,?,?,'NA',?,?,?,?)";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param(
    $stmt, "sssssssssssss",
    $employee_id, $name, $designation, $pan_number, $bank_acc_number, $bank_ifsc,
    $annual_ctc, $monthly_salary, $joining_date, $role, $email, $phone, $password_hash
);

if (mysqli_stmt_execute($stmt)) {
    set_flash('success', "User created successfully.");
} else {
    error_log("user_create: " . mysqli_stmt_error($stmt));
    set_flash('error', "Failed to create user.");
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
header("Location: ../admin/users/index.php");
exit;