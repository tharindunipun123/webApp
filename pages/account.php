<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session if it hasn't already been started
}
include 'db_connect.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if the user is not logged in
    exit;
}

// Get the user's details from the database
$user_id = $_SESSION['user_id'];
$user_query = "SELECT name, email FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);

// Check if the user exists in the database
if (mysqli_num_rows($user_result) > 0) {
    $user = mysqli_fetch_assoc($user_result);
    $username = $user['name'];
    $email = $user['email'];
} else {
    $username = "Unknown User";
    $email = "Unknown Email";
}

// Handle Logout
if (isset($_GET['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: login.php"); // Redirect to login page
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/account.css">
</head>
<body>
<div class="container mt-3">
    <div class="title"><center><h3>Settings</h3></center></div>

    <!-- User Info Section -->
    <div class="card card-custom">
        <div class="card-body text-center">
            <img src="path_to_avatar.png" class="rounded-circle" alt="Avatar" />
            <h5 id="username-display"><?php echo $username; ?></h5> <!-- Display username -->
            <p id="email-display"><?php echo $email; ?></p> <!-- Display email -->
        </div>
    </div>

    <!-- Settings Options -->
    <div class="list-group">
        <a href="#" class="list-group-item list-group-item-action" id="share-button">
            <i class="bi bi-share-fill"></i> Share
        </a>
        <a href="#" class="list-group-item list-group-item-action" id="edit-profile">
            <i class="bi bi-pencil-square"></i> Edit Profile
        </a>
        <a href="login.php" class="list-group-item list-group-item-action" id="logout">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="edit-profile-form">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new-username" class="form-label">New Username</label>
                        <input type="text" class="form-control" id="new-username" name="new_username" required>
                    </div>
                    <div class="mb-3">
                        <label for="new-email" class="form-label">New Email</label>
                        <input type="email" class="form-control" id="new-email" name="new_email" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Load jQuery, Bootstrap, and SweetAlert -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Handle Edit Profile click
    document.getElementById('edit-profile').addEventListener('click', function () {
        var modal = new bootstrap.Modal(document.getElementById('editProfileModal'), {});
        modal.show();
    });

    // Handle Share click (Copy URL to clipboard)
    document.getElementById('share-button').addEventListener('click', function () {
        var shareURL = "https://example.com/share"; // Replace with the actual URL
        navigator.clipboard.writeText(shareURL).then(function() {
            Swal.fire({
                icon: 'success',
                title: 'URL Copied!',
                text: 'The URL has been copied to your clipboard.',
                showConfirmButton: false,
                timer: 1500
            });
        }).catch(function(error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to copy the URL.',
                showConfirmButton: false,
                timer: 1500
            });
        });
    });

    // Handle form submission for updating username and email
    $('#edit-profile-form').on('submit', function (e) {
        e.preventDefault(); // Prevent form from reloading the page

        var newUsername = $('#new-username').val();
        var newEmail = $('#new-email').val();

        $.ajax({
            url: 'update_profile.php', // A separate PHP file to handle profile updates
            type: 'POST',
            data: {
                new_username: newUsername,
                new_email: newEmail
            },
            success: function (response) {
                if (response.success) {
                    // Update the displayed username and email
                    $('#username-display').text(newUsername);
                    $('#email-display').text(newEmail);

                    // Show success message and close the modal
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Your profile has been updated.',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    var modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
                    modal.hide(); // Hide the modal after success
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Your profile has been updated.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },
            error: function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred.',
                });
            }
        });
    });
    // Handle Logout click
    document.getElementById('logout').addEventListener('click', function () {
        window.location.href = 'index.php'; // Redirect to trigger logout
    });
</script>
</body>
</html>
