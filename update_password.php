<?php
require_once 'includes/dbconnect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['password']) && isset($_POST['token']) && isset($_POST['email'])) {
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $token = $_POST['token'];
    $email = $_POST['email'];

    // Update the password
    $stmt = $pdo->prepare("UPDATE customer SET Password = :password WHERE Email = (SELECT email FROM password_resets WHERE token = :token AND email = :email AND expire_at > NOW())");
    $result = $stmt->execute(['password' => $password, 'token' => $token, 'email' => $email]);

    if ($result) {
        echo "Your password has been reset successfully!";
        // Optionally, clean up the password reset token
        $deleteToken = $pdo->prepare("DELETE FROM password_resets WHERE email = :email");
        $deleteToken->execute(['email' => $email]);
        header("Location: login.php");
        exit();
    } else {
        echo "Failed to reset your password.";
    }
} else {
    echo "Invalid request.";
}
?>
