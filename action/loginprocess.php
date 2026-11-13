<?php
session_start();
require_once __DIR__ . '/../utils/conn.php';

// Only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Basic validation
if ($email === '' || $password === '') {
    $_SESSION['flash_error'] = "Email and password are required.";
    header("Location: ../login.php");
    exit;
}

$sql  = "SELECT employee_id, role, password FROM users WHERE email = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    error_log("Login prepare failed: " . mysqli_error($conn));
    $_SESSION['flash_error'] = "Server error. Try again.";
    header("Location: ../login.php");
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Generic message for BOTH "no user" and "wrong password" → prevents account enumeration
$fail = "Invalid email or password.";

if ($row = mysqli_fetch_assoc($result)) {
    if (password_verify($password, $row['password'])) {
        // Prevent session fixation
        session_regenerate_id(true);

        $_SESSION['employee_id'] = $row['employee_id'];
        $_SESSION['role']        = $row['role'];

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        // Redirect by role
        if ($row['role'] === 'Admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../employee/dashboard.php");
        }
        exit;
    }
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

$_SESSION['flash_error'] = $fail;
header("Location: ../login.php");
exit;