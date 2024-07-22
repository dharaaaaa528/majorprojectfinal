<?php

require_once 'server.php';

// Initialize the session
session_start();

// Update the following variables
$google_oauth_client_id = '277042704945-445qenkddmcfif22rmi2t1js475019u1.apps.googleusercontent.com';
$google_oauth_client_secret = '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';
$google_oauth_redirect_uri = 'http://localhost/majorprojectfinal/major-project/google-oauth.php';
$google_oauth_version = 'v3';

// Check if the code is present in the URL
if (isset($_GET['code']) && !empty($_GET['code'])) {
    // Execute cURL request to retrieve the access token
    $params = [
        'code' => $_GET['code'],
        'client_id' => $google_oauth_client_id,
        'client_secret' => $google_oauth_client_secret,
        'redirect_uri' => $google_oauth_redirect_uri,
        'grant_type' => 'authorization_code'
    ];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($response, true);

    // Make sure access token is valid
    if (isset($response['access_token']) && !empty($response['access_token'])) {
        // Execute cURL request to retrieve the user info associated with the Google account
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/' . $google_oauth_version . '/userinfo');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $response['access_token']]);
        $response = curl_exec($ch);
        curl_close($ch);
        $profile = json_decode($response, true);

        // Make sure the profile data exists
        if (isset($profile['email'])) {
            $google_name_parts = [];
            $google_name_parts[] = isset($profile['given_name']) ? preg_replace('/[^a-zA-Z0-9]/s', '', $profile['given_name']) : '';
            $google_name_parts[] = isset($profile['family_name']) ? preg_replace('/[^a-zA-Z0-9]/s', '', $profile['family_name']) : '';

            // Check if the account exists in the database
            $stmt = $pdo->prepare('SELECT * FROM userinfo WHERE email = ?');
            $stmt->execute([$profile['email']]);
            $account = $stmt->fetch(PDO::FETCH_ASSOC);

            $current_time = date('Y-m-d H:i:s');

            // If the account does not exist in the database, insert the account into the database
            if (!$account) {
                // Generate a random password
                $random_password = bin2hex(random_bytes(8));
                $hashed_password = password_hash($random_password, PASSWORD_DEFAULT);

                $stmt = $pdo->prepare('INSERT INTO userinfo (username, password, email, created_at, last_login) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([implode(' ', $google_name_parts), $hashed_password, $profile['email'], $current_time, $current_time]);
                $id = $pdo->lastInsertId();
            } else {
                $id = $account['userid'];
                // Update the last_login timestamp
                $stmt = $pdo->prepare('UPDATE userinfo SET last_login = ? WHERE userid = ?');
                $stmt->execute([$current_time, $id]);
            }

            // Authenticate the account
            session_regenerate_id();
            $_SESSION['google_loggedin'] = TRUE;
            $_SESSION['userid'] = $id; // Use 'userid' for consistency
            $_SESSION['username'] = implode(' ', $google_name_parts);

            // Redirect to profile page
            header('Location: usermain.php');
            exit;
        } else {
            exit('Could not retrieve profile information! Please try again later!');
        }
    } else {
        exit('Invalid access token! Please try again later!');
    }
} else {
    // Define params and redirect to Google Authentication page
    $params = [
        'response_type' => 'code',
        'client_id' => $google_oauth_client_id,
        'redirect_uri' => $google_oauth_redirect_uri,
        'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
        'access_type' => 'offline',
        'prompt' => 'consent'
    ];
    header('Location: https://accounts.google.com/o/oauth2/auth?' . http_build_query($params));
    exit;
}
?>


