<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$DB_HOST = "localhost";
$DB_USER = "root";          // change for production
$DB_PASS = "";              // change for production
$DB_NAME = "enablemining_admin";

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (!$conn) {
    // Don't leak DB errors to the browser in production
    error_log("DB Connection failed: " . mysqli_connect_error());
    http_response_code(500);
    die("Database connection error. Please try again later.");
}

mysqli_set_charset($conn, "utf8mb4");