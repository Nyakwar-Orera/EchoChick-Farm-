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
        $mail->addAddress('echochickfarm@gmail.com'); // Support email address

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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAQs - ECHOCHICK FARM</title>
  <link rel="icon" type="image/x-icon" href="../chattels/images/farmimages/favicon1.png">
  <?php include("../includes/head.php"); ?>
  <link rel="stylesheet" href="../chattels/css/faq.css"> <!-- Link to the FAQ CSS file -->
</head>
<body>
<header>
    <?php include("../includes/custheader.php"); ?>
</header>
<div class="container">
    <h2>Frequently Asked Questions</h2>
    <div class="faq-container">
        <!-- Static FAQ Items -->
        <div class="faq-item">
            <div class="faq-question">
                <h3>What are your business hours?</h3>
            </div>
            <div class="faq-answer">
                <p>Our business hours are Monday to Saturday, 9 AM to 5 PM.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">
                <h3>What products do you offer?</h3>
            </div>
            <div class="faq-answer">
                <p>We offer a variety of poultry products including fresh chicken, eggs, and organic feed.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">
                <h3>How can I place an order?</h3>
            </div>
            <div class="faq-answer">
                <p>You can place an order through our website or by calling our customer service line.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">
                <h3>Do you offer delivery services?</h3>
            </div>
            <div class="faq-answer">
                <p>Yes, we offer delivery services within a 50-mile radius of our farm.</p>
            </div>
        </div>
    </div>
    <!-- Customer Question Form -->
    <div class="question-form">
        <h2>Have a Question?</h2>
        <form action="faq.php" method="post">
            <label for="customerName">Your Name:</label>
            <input type="text" id="customerName" name="customerName" required>
            <label for="customerEmail">Your Email:</label>
            <input type="email" id="customerEmail" name="customerEmail" required>
            <label for="customerQuestion">Your Question:</label>
            <textarea id="customerQuestion" name="customerQuestion" rows="4" required></textarea>
            <button type="submit">Submit</button>
        </form>
    </div>
</div>
<?php include("../includes/foot.php");?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(item => {
        item.querySelector('.faq-question').addEventListener('click', function () {
            item.querySelector('.faq-answer').classList.toggle('active');
        });
    });
});
</script> 
</body>
</html>
