<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if the user is not logged in
    exit();
}

// Include the page based on the 'page' parameter in the URL, default to 'home'
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Bootstrap Icons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Include your custom CSS here -->
    <link rel="stylesheet" href="css/navbar.css">
</head>
<body>
    <!-- Main Content Area -->
    <div id="main-content">
        <?php
        // Include the page based on the URL parameter 'page'
        if ($page == 'home') {
            include 'pages/home.php';
        } elseif ($page == 'news') {
            include 'pages/news.php';
        } elseif ($page == 'account') {
            include 'pages/account.php';
        } else {
            include 'pages/home.php'; // Default to home if page not recognized
        }
        ?>
    </div>

    <!-- Bottom Navigation Bar with Rounded Corners -->
    <nav class="navbar navbar-dark fixed-bottom">
        <div class="container-fluid d-flex justify-content-around">
            <a class="nav-link text-center" href="index.php?page=home">
                <i class="bi bi-house-door-fill"></i>
                <span class="d-block">Home</span>
            </a>
            <a class="nav-link text-center" href="index.php?page=news">
                <i class="bi bi-newspaper"></i>
                <span class="d-block">News</span>
            </a>
            <a class="nav-link text-center" href="index.php?page=account">
                <i class="bi bi-gear-fill"></i>
                <span class="d-block">Settings</span>
            </a>
        </div>
    </nav>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="ajax.js"></script>
</body>
</html>
