<?php
// check_login.php
session_start();
require 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Fetch user from DB
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Verify password (must be hashed in DB)
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password.');window.location='index.php';</script>";
        }
    } else {
        echo "<script>alert('User not found.');window.location='index.php';</script>";
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: index.php");
    exit();
}
?>
