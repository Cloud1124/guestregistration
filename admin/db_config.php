<?php
// db_config.php
$host = "localhost";
$username = "root";  // use your MySQL username
$password = "";      // use your MySQL password
$database = "db_registration"; // your database name

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
