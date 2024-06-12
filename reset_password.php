<?php
require_once 'includes/dbconnect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['token']) && isset($_GET['email'])) {
    $token = $_GET['token'];
    $email = $_GET['email'];

    // Validate the token and email
    $stmt = $pdo->prepare("SELECT email FROM password_resets WHERE token = :token AND email = :email AND expire_at > NOW()");
    $stmt->execute(['token' => $token, 'email' => $email]);

    if ($stmt->rowCount() == 1) {
        // Show reset form
        echo '<h2>Reset Your Password</h2>
        <form action="update_password.php" method="post">
            <input type="hidden" name="email" value="' . $email . '">
            <input type="hidden" name="token" value="' . $token . '">
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Reset Password">
        </form>';
    } else {
        echo "Invalid or expired link.";
    }
} else {
    echo "Invalid request.";
}
?>

