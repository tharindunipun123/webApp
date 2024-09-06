<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/news.css">
</head>
<body>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session only if it hasn't already been started
} 
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$success_message = '';
$error_message = '';

// Handle form submission for reward code
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reward_code'])) {
    $reward_code = mysqli_real_escape_string($conn, $_POST['reward_code']);
    $user_id = $_SESSION['user_id'];

    // Query to check if the reward code is valid
    $reward_query = "SELECT * FROM youtube_rewards WHERE reward_code = '$reward_code'";
    $reward_result = mysqli_query($conn, $reward_query);

    if (mysqli_num_rows($reward_result) > 0) {
        $reward = mysqli_fetch_assoc($reward_result);
        $reward_amount = $reward['reward_amount'];


        $update_balance_query = "UPDATE users SET balance = balance + $reward_amount WHERE id = '$user_id'";
        if (mysqli_query($conn, $update_balance_query)) {
            // Fetch the updated balance
            $balance_query = "SELECT balance FROM users WHERE id = '$user_id'";
            $balance_result = mysqli_query($conn, $balance_query);
            $user = mysqli_fetch_assoc($balance_result);

            $success_message = "Reward added to your balance: " . $reward_amount . " BOTH. Your new balance is: " . $user['balance'] . " BOTH.";
        } else {
            $error_message = 'Failed to update balance. Please try again later.';
        }
    } else {
        $error_message = 'Invalid reward code. Please try again.';
    }
}
?>

<script>
    var userId = '<?php echo $_SESSION['user_id']; ?>'; // Pass the user ID from PHP to JavaScript
</script>

<!-- Content Section -->
<div class="container mt-3">
    <!-- Title Section -->
    <div class="title">
        <center><h3>News</h3></center>
    </div>

    <!-- Navigation Buttons -->
    <div class="d-flex justify-content-around mb-3">
        <a href="#" class="btn btn-dark" id="show-news">News</a>
        <a href="#" class="btn btn-dark" id="show-youtube">YouTube</a>
        <a href="#" class="btn btn-dark" id="show-social">Social</a>
    </div>

    <!-- News Section -->
    <div id="news-section">
        <?php
        // Fetch all the news from the database
        $query = "SELECT * FROM news ORDER BY created_at DESC";
        $result = mysqli_query($conn, $query);
        ?>
        <div class="container mt-3">
            <div class="list-group">
                <?php
                // Loop through the fetched news and display them
                if (mysqli_num_rows($result) > 0) {
                    while ($news = mysqli_fetch_assoc($result)) {
                        echo '<a href="#" class="list-group-item list-group-item-action">';
                        echo $news['title']; // Display the news title
                        echo '</a>';
                    }
                } else {
                    echo '<p>No news available.</p>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- YouTube Section -->
    <div id="youtube-section" style="display: none;">
        <div class="list-group">
            <?php
            // Fetch all rewards from the youtube_rewards table
            $query = "SELECT * FROM youtube_rewards ORDER BY created_at DESC";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                // Iterate over each reward and display it dynamically
                while ($reward = mysqli_fetch_assoc($result)) {
                    echo '<div class="list-group-item list-group-item-action flex-column align-items-start mb-3">';
                    echo '<p class="card-title">' . $reward['description'] . '</p>';
                    echo '<p class="card-subtitle mb-2">Reward: ' . $reward['reward_amount'] . ' BOTH tokens</p>';
                    echo '<div class="mt-3">';
                    echo '<div class="input-group">';
                    echo '<input type="text" class="form-control code-input me-2" placeholder="Enter code from video" data-code="' . $reward['reward_code'] . '" data-amount="' . $reward['reward_amount'] . '">';
                    echo '<button class="btn btn-reward get-reward-btn">Get Reward</button>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No rewards available at this time.</p>';
            }
            ?>
        </div>
    </div>

    <!-- Social Section -->
    <div id="social-section" style="display: none;">
        <div class="list-group">
            <p>Social content will go here.</p>
        </div>
    </div>
</div>

<!-- Load jQuery and Bootstrap scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Place the jQuery Script Here -->
<script>
    $(document).ready(function(){
        // Show News section by default
        $('#show-news').click(function(e){
            e.preventDefault();
            $('#news-section').show();   // Show news content
            $('#youtube-section, #social-section').hide();  // Hide other sections
            $('#show-news').addClass('active');   // Add active class to the news button
            $('#show-youtube, #show-social').removeClass('active'); // Remove active class from other buttons
        });

        // Show YouTube section
        $('#show-youtube').click(function(e){
            e.preventDefault();
            $('#youtube-section').show();  // Show YouTube content
            $('#news-section, #social-section').hide();  // Hide other sections
            $('#show-youtube').addClass('active');   // Add active class to the YouTube button
            $('#show-news, #show-social').removeClass('active'); // Remove active class from other buttons
        });

        // Show Social section
        $('#show-social').click(function(e){
            e.preventDefault();
            $('#social-section').show();  // Show social content
            $('#news-section, #youtube-section').hide();  // Hide other sections
            $('#show-social').addClass('active');   // Add active class to the social button
            $('#show-news, #show-youtube').removeClass('active'); // Remove active class from other buttons
        });
    });

    $(document).ready(function() {
    // Event delegation to handle clicks on dynamically generated buttons
    $(document).on('click', '.get-reward-btn', function() {
        // Get the reward code from the input field
        var inputField = $(this).siblings('.code-input');
        var enteredCode = inputField.val().trim().toLowerCase();  // Trim and convert to lowercase
        var actualCode = String(inputField.data('code')).trim().toLowerCase();  // Convert to string, trim, and convert to lowercase
        var rewardAmount = inputField.data('amount'); // Get reward amount

        console.log('Entered Code:', enteredCode); // Debugging
        console.log('Actual Code:', actualCode);   // Debugging

        // Compare the sanitized codes
        if (enteredCode === actualCode) {
            console.log("true");

            $.ajax({
    url: window.location.href,  // Submit to the current URL
    method: 'POST',
    data: {
        reward_code: actualCode,
        reward_amount: rewardAmount,
        user_id: userId
    },
    success: function(response) {
        try {
            console.log("success");
            let res = JSON.parse(response);  // Ensure the response is parsed correctly
            if (res.success) {
                Swal.fire('Success', 'Reward added to your balance: ' + rewardAmount + ' BOTH', 'success');
                $('.display-4').text(res.new_balance + ' BOTH');  // Update the balance display
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        } catch (e) {
            Swal.fire('Success', 'Reward added to your balance: ' + rewardAmount + ' BOTH', 'success');
            console.error('Parsing error:', e, response);  // Debugging output
        }
    },
    error: function(xhr, status, error) {
        Swal.fire('Error', 'An error occurred. Please try again.', 'error');
        console.error('Error details:', error, xhr.responseText);  // Debugging output
    }
});

  

        } else {
            Swal.fire('Error', 'Invalid reward code!', 'error');
        }
    });
});


</script>

</body>
</html>
