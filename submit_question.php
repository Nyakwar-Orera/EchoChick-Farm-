<?php
include('../includes/confirmlogin1.php');
check_login();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/vendor/autoload.php'; // Adjust the path as necessary

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerName = htmlspecialchars($_POST['customerName']);
    $customerEmail = htmlspecialchars($_POST['customerEmail']);
    $customerQuestion = htmlspecialchars($_POST['customerQuestion']);

    // Save the question to the database (optional)
    try {
        $dsn = 'mysql:host=localhost;dbname=poultryfarm;charset=utf8mb4';
        $username = 'root';
        $password = ''; // Replace with your database password

        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO messages (name, email, message) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$customerName, $customerEmail, $customerQuestion]);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'echochickfarm@gmail.com';
        $mail->Password = 'vitdprjohazzdplj'; // Gmail App-specific password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('echochickfarm@gmail.com', 'ECHOCHICK FARM');
        $mail->addAddress($farm->farmemail); // Add a recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Customer Question';
        $mail->Body = "<p><strong>Name:</strong> {$customerName}</p>
                       <p><strong>Email:</strong> {$customerEmail}</p>
                       <p><strong>Question:</strong> {$customerQuestion}</p>";

        $mail->send();
        header('Location: faq.php?success=1'); // Redirect after successful submission
        exit;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
