<?php
include '../db_connect.php'; // Include the database connection

session_start();

if (!isset($_POST['user_id'])) {
    echo "No user ID provided!";
    exit;
}

$user_id = $_POST['user_id'];

// Update balance logic (increment balance for mining simulation)
$update_balance_query = "UPDATE users SET balance = balance + 20 WHERE id = '$user_id'";
mysqli_query($conn, $update_balance_query);

// Retrieve the updated balance
$balance_query = "SELECT balance FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $balance_query);
$user = mysqli_fetch_assoc($result);

// Return the new balance to the AJAX call
echo $user['balance'];
?>
