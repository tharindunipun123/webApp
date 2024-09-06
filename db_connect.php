<?php
// Database connection details
$host = "localhost";     // Your database host (usually 'localhost')
$user = "root";          // Your database username
$password = "";          // Your database password (leave empty if no password for local dev)
$database = "webapp"; // The name of your database

// Create connection
$conn = mysqli_connect($host, $user, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
