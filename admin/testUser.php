<?php
// run this once to insert a test user
include("db_config.php");

$name = "Admin";
$username = "admin";
$password = password_hash("Admin@123!", PASSWORD_DEFAULT); // secure hashed password

$sql = "INSERT INTO users (name, username, password) VALUES ('$name', '$username', '$password')";
if (mysqli_query($conn, $sql)) {
    echo "Test user created.";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
