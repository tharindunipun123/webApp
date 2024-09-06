<?php
session_start();
include 'db_connect.php';

$response = ['success' => false]; // Default response

// Check if the user is logged in
if (isset($_SESSION['user_id']) && isset($_POST['new_username']) && isset($_POST['new_email'])) {
    $user_id = $_SESSION['user_id'];
    $new_username = mysqli_real_escape_string($conn, $_POST['new_username']);
    $new_email = mysqli_real_escape_string($conn, $_POST['new_email']);

    // Update the user's profile
    $update_query = "UPDATE users SET name = '$new_username', email = '$new_email' WHERE id = $user_id";
    if (mysqli_query($conn, $update_query)) {
        $response['success'] = true;
    }
}

echo json_encode($response);
?>
