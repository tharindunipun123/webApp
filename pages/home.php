<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to access this page.";
    exit;
}

include './db_connect.php'; // Connect to the database

// Get user details
$user_id = $_SESSION['user_id'];
$query = "SELECT name, balance, referral_code, telegram_photo_url FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<script>
    var userId = '<?php echo $_SESSION['user_id']; ?>'; // Set the user_id from PHP to JavaScript
</script>

<link rel="stylesheet" href="css/home.css">

<div class="container mt-3">
    <div class="profile-container">
        <img src="<?php echo $user['telegram_photo_url']; ?>" class="rounded-circle" alt="Avatar" /> <!-- Display Telegram profile photo -->
        <div class="welcome-message">
            <p>Welcome,</p>
            <h5><?php echo $user['name']; ?></h5> <!-- Display the user's name dynamically -->
        </div>
    </div>

    <div class="data text-container">
        <h4 class="h4">Current Balance</h4>
        <h1 class="display-4"><?php echo $user['balance']; ?> BOTH</h1> <!-- Display the user's balance dynamically -->
        <p class="p">Earning rate +20.00 BOTH/hr</p>
    </div>

    <div class="card card-custom">
        <div class="card-body d-flex justify-content-between align-items-center">
            <a href="#" class="btn button-custom w-100 d-flex align-items-center justify-content-between" id="copy-referral">
                <span><i class="bi bi-clipboard-check"></i> Referral Code: <span id="referral-code"><?php echo $user['referral_code']; ?></span></span>
                <i class="bi bi-clipboard" id="clipboard-icon" style="font-size: 1.2rem;"></i>
            </a>
        </div>
    </div>

    <div class="friends">
        <h2>Friends</h2>
        <?php
        // Fetch referred users (friends) from the referrals table
        $referral_query = "SELECT u.name FROM users u 
                           INNER JOIN referrals r ON u.id = r.user_id
                           WHERE r.parent_id = '$user_id'";
        $referral_result = mysqli_query($conn, $referral_query);

        if (mysqli_num_rows($referral_result) > 0) {
            while ($friend = mysqli_fetch_assoc($referral_result)) {
                echo "<p>" . $friend['name'] . "</p>";
            }
        } else {
            echo "<p>No Friends Found</p>";
        }
        ?>
    </div>
</div>


<script>
// Clipboard Copy Functionality
document.getElementById('clipboard-icon').addEventListener('click', function() {
    // Get the referral code text
    var referralCode = document.getElementById('referral-code').innerText;
    
    // Copy the referral code to the clipboard
    navigator.clipboard.writeText(referralCode).then(function() {
        // Success alert
        alert("Referral code copied to clipboard: " + referralCode);
    }).catch(function(error) {
        // Error alert
        alert("Failed to copy referral code.");
        console.error("Clipboard error:", error);
    });
});
</script>
