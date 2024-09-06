<?php
session_start();
include 'db_connect.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch login details from the form
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']); // Encrypt the password (same method used in registration)

    // Check if user exists in the database
    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // User found, start session and redirect to home
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id']; // Store user ID in session
        $_SESSION['user_name'] = $user['name']; // Store user name in session

        header("Location: index.php"); // Redirect to the main page
        exit();
    } else {
        // If the user does not exist, create a new user
        $name = explode('@', $email)[0]; // Use part of the email as the default name (before the @)
        $referral_code = substr(md5(time()), 0, 6); // Generate a unique referral code

        $create_user_query = "INSERT INTO users (name, email, password, referral_code) VALUES ('$name', '$email', '$password', '$referral_code')";
        if (mysqli_query($conn, $create_user_query)) {
            // Get the new user's ID
            $user_id = mysqli_insert_id($conn);

            // Start session and log in the new user
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $name;

            // Redirect to the main page
            header("Location: index.php");
            exit();
        } else {
            echo "Error creating new user: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script async src="https://telegram.org/js/telegram-widget.js?15" data-telegram-login="BothWebAppTestBot" data-size="large" data-auth-url="http://localhost/webapp/telegram_login.php" data-request-access="write"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Login or Create Account</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group mt-3">
                <label for="password">Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Login or Create Account</button>
        </form>
    </div>
    <script async src="https://telegram.org/js/telegram-widget.js?19"
            data-telegram-login="BothWebAppTestBot" 
            data-size="large"
            data-auth-url="telegram_login.php"
            data-request-access="write"></script>
</body>
</html>
