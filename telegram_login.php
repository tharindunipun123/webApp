<?php
session_start();
include 'db_connect.php'; // Include your database connection

$telegram_token = '7248377845:AAES_CWSWFDoiigFcDlpFdplFo4W55aVPnQ'; // Replace with your actual Telegram bot token

// Function to verify the Telegram login data
function verifyTelegramData($auth_data, $bot_token) {
    $check_hash = $auth_data['hash'];
    unset($auth_data['hash']);
    $data_check_arr = [];
    foreach ($auth_data as $key => $value) {
        $data_check_arr[] = $key . '=' . $value;
    }
    sort($data_check_arr);
    $data_check_string = implode("\n", $data_check_arr);
    $secret_key = hash('sha256', $bot_token, true);
    $hash = hash_hmac('sha256', $data_check_string, $secret_key);

    return ($hash === $check_hash && (time() - $auth_data['auth_date']) < 86400); // 1-day validity
}

// Get the Telegram data
$telegram_data = $_GET;

if (verifyTelegramData($telegram_data, $telegram_token)) {
    // Store the Telegram data in session
    $telegram_id = $telegram_data['id'];
    $username = $telegram_data['first_name'] . ' ' . $telegram_data['last_name'];
    $profile_photo_url = $telegram_data['photo_url']; // Get photo URL
    
    // Check if the user already exists
    $check_user_query = "SELECT id FROM users WHERE telegram_id = '$telegram_id'";
    $result = mysqli_query($conn, $check_user_query);

    if (mysqli_num_rows($result) > 0) {
        // User already exists, log them in
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $username;
    } else {
        // New user, insert into the database
        $referral_code = substr(md5(time()), 0, 6); // Generate referral code
        $insert_query = "INSERT INTO users (name, email, telegram_id, telegram_photo_url, referral_code, balance) 
                         VALUES ('$username', '$telegram_data[email]', '$telegram_id', '$profile_photo_url', '$referral_code', 0)";
        if (mysqli_query($conn, $insert_query)) {
            $_SESSION['user_id'] = mysqli_insert_id($conn);
            $_SESSION['user_name'] = $username;
        }
    }
    // Redirect to the home page after login
    header("Location: index.php");
    exit;
}

?>
