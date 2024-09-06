<?php
include 'db_connect.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the submitted data
    $reward_code = mysqli_real_escape_string($conn, $_POST['reward_code']);
    $reward_amount = mysqli_real_escape_string($conn, $_POST['reward_amount']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

   
    $query = "INSERT INTO youtube_rewards (reward_code, reward_amount, description) VALUES ('$reward_code', '$reward_amount', '$description')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Reward added successfully!');</script>";
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
    <title>Add YouTube Reward</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Add YouTube Reward</h2>
        <form action="add_youtube_reward.php" method="POST">
            <div class="form-group">
                <label for="reward_code">Reward Code:</label>
                <input type="text" name="reward_code" class="form-control" required>
            </div>
            <div class="form-group mt-3">
                <label for="reward_amount">Reward Amount:</label>
                <input type="number" name="reward_amount" class="form-control" required>
            </div>
            <div class="form-group mt-3">
                <label for="description">Description:</label>
                <textarea name="description" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Add Reward</button>
        </form>
    </div>
</body>
</html>
