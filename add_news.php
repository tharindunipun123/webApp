<?php
include 'db_connect.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the submitted news title and content
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    
    // Insert the news into the database
    $query = "INSERT INTO news (title, content) VALUES ('$title', '$content')";
    
    if (mysqli_query($conn, $query)) {
        echo "News added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add News</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add News</h2>
        <form action="add_news.php" method="POST">
            <div class="form-group">
                <label for="title">News Title:</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="form-group mt-3">
                <label for="content">News Content:</label>
                <textarea name="content" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Add News</button>
        </form>
    </div>
</body>
</html>
