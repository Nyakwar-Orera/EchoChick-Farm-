<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request</title>
</head>
<body>
    <h2>Password Reset Request</h2>
    <form action="send_password_reset.php" method="post">
        <label for="email">Enter your email address:</label>
        <input type="email" id="email" name="email" required>
        <input type="submit" value="Request Reset Link">
    </form>
</body>
</html>
