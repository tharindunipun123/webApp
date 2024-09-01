<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include the modern UI CSS here -->
    <link rel="stylesheet" href="css/navbar.css">
</head>
<body>
    <!-- Main Content Area -->
    <div id="main-content">
        <?php include 'pages/home.php'; ?>
    </div>

    <!-- Bottom Navigation Bar with Rounded Corners -->
    <nav class="navbar navbar-dark bg-dark fixed-bottom">
        <div class="container-fluid d-flex justify-content-around">
            <a class="nav-link text-center" href="home">
                <i class="bi bi-house-door-fill"></i>
                <span class="d-block">Home</span>
            </a>
            <a class="nav-link text-center" href="news">
                <i class="bi bi-newspaper"></i>
                <span class="d-block">News</span>
            </a>
            <a class="nav-link text-center" href="account">
                <i class="bi bi-person-fill"></i>
                <span class="d-block">Account</span>
            </a>
        </div>
    </nav>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
    <script src="ajax.js"></script>
</body>
</html>
