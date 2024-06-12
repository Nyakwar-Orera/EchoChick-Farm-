<?php
require_once 'includes/dbconnect.php'; // Ensure you have this file for database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Check if email exists in the database
    $stmt = $pdo->prepare("SELECT Email FROM customer WHERE Email = :email");
    $stmt->execute(['email' => $email]);
    
    if ($stmt->rowCount() == 1) {
        // Generate a unique password reset token
        $token = bin2hex(random_bytes(32));
        
        // Store the token in the database with an expiration time
        $insertToken = $pdo->prepare("INSERT INTO password_resets (email, token, expire_at) VALUES (:email, :token, DATE_ADD(NOW(), INTERVAL 1 HOUR))");
        $insertToken->execute(['email' => $email, 'token' => $token]);

        // Send email (simulated here)
        echo "Password reset link (simulate sending via email): ";
        echo "<a href='reset_password.php?token=" . $token . "&email=" . $email . "'>Reset Password</a>";
    } else {
        echo "No account found with that email address.";
    }
} else {
    echo "Please provide an email address.";
}
?>
