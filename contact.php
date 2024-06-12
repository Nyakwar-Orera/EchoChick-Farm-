<?php
include('../includes/confirmlogin1.php');
check_login();

// Include the PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/vendor/autoload.php'; // Adjust the path as necessary

 

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM farm WHERE id = 2"; // Assuming the farm details are in row with id 2
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $farm = $stmt->fetch(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerName = htmlspecialchars($_POST['customerName']);
    $customerEmail = htmlspecialchars($_POST['customerEmail']);
    $customerMessage = htmlspecialchars($_POST['customerMessage']);

    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server address
        $mail->SMTPAuth = true;
        $mail->Username = 'echochickfarm@gmail.com'; // my gmail Gmail
        $mail->Password = 'vitdprjohazzdplj'; // Using environment variable for password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('echochickfarm@gmail.com', 'ECHOCHICK FARM');
        $mail->addAddress($farm->farmemail); // Add a recipient

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Form Submission';
        $mail->Body = "<p><strong>Name:</strong> {$customerName}</p>
                       <p><strong>Email:</strong> {$customerEmail}</p>
                       <p><strong>Message:</strong> {$customerMessage}</p>";

        $mail->send();
        header('Location: faq.php'); // Redirect after successful submission
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
  <title>Contact Us - ECHOCHICK-FARM</title>
  <link rel="icon" type="image/x-icon" href="../chattels/images/farmimages/favicon1.png">
  <?php include("../includes/head.php"); ?>
  <link rel="stylesheet" href="../chattels/css/faq.css"> <!-- Link to the FAQ CSS file -->
  
</head>
<body>
<header>
    <?php include("../includes/custheader.php"); ?>
</header>
<div class="container">
    <h2>Contact Us</h2>
    <div class="contact-info">
        <p><strong>Farm Name:</strong> <?php echo htmlspecialchars($farm->farmname); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($farm->farmemail); ?></p>
        <p><strong>Telephone:</strong> <?php echo htmlspecialchars($farm->farmtelephone); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($farm->farmaddress); ?></p>
    </div>
    <div class="contact-form">
        <h2>Send Us a Message</h2>
        <form action="contact.php" method="post">
            <label for="customerName">Your Name:</label>
            <input type="text" id="customerName" name="customerName" required>
            <label for="customerEmail">Your Email:</label>
            <input type="email" id="customerEmail" name="customerEmail" required>
            <label for="customerMessage">Your Message:</label>
            <textarea id="customerMessage" name="customerMessage" rows="4" required></textarea>
            <button type="submit">Submit</button>
        </form>
    </div>
</div>
<?php include("../includes/foot.php");?>
</body>
</html>
