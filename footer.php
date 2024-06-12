<?php

include('dbconnect.php'); // Database connection file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/vendor/autoload.php'; // Adjust the path as necessary

$formMessage = "";
$formMessageType = ""; // New variable to keep track of the message type (success or error)

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_contact_form'])) {
    $name = htmlspecialchars(strip_tags(trim($_POST["name"])));
    $email = htmlspecialchars(strip_tags(trim($_POST["email"])));
    $message = htmlspecialchars(strip_tags(trim($_POST["message"])));

    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($message)) {
        try {
            // Prepare an insert statement
            $sql = "INSERT INTO messages (name, email, message) VALUES (:name, :email, :message)";
            $stmt = $pdo->prepare($sql);
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':message', $message, PDO::PARAM_STR);
            
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Email notification
                $mail = new PHPMailer(true);

                try {
                    // SMTP configuration
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'echochickfarm@gmail.com'; // Your Gmail
                    $mail->Password = 'vitdprjohazzdplj'; // Gmail App-specific password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = 465;

                    // Recipients
                    $mail->setFrom('echochickfarm@gmail.com', 'ECHOCHICK FARM');
                    $mail->addAddress('echochickfarm@gmail.com'); // Support email address

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'New Contact Form Submission';
                    $mail->Body = "<p><strong>Name:</strong> {$name}</p><p><strong>Email:</strong> {$email}</p><p><strong>Message:</strong> {$message}</p>";
                    $mail->AltBody = 'Name: ' . $name . '\r\nEmail: ' . $email . '\r\nMessage: ' . $message;

                    $mail->send();
                    $formMessage = "Message sent successfully.";
                    $formMessageType = "success";
                    // Clear input fields only on successful submission
                    $name = $email = $message = "";
                } catch (Exception $e) {
                    $formMessage = "Mailer Error: " . $mail->ErrorInfo;
                    $formMessageType = "error";
                }
            }
        } catch (Exception $e) {
            $formMessage = "Database Error: " . $e->getMessage();
            $formMessageType = "error";
        }
        
        // Close statement
        unset($stmt);
    } else {
        $formMessage = "Please correct your input and try again.";
        $formMessageType = "error";
    }
    
    // Close connection
    unset($pdo);
}

?>

<footer class="site-footer">
    <link rel="stylesheet" href="../chattels/css/home.css">
    <div class="footer-container">
        <div class="footer-section">
            <section id="contact-us">
                <h3>Contact Us</h3>
                <p>Email: <?php echo htmlspecialchars($farmInfo['farmemail']); ?></p>
                <p>Telephone: <?php echo htmlspecialchars($farmInfo['farmtelephone']); ?></p>
                <p>Address: <?php echo htmlspecialchars($farmInfo['farmaddress']); ?></p>
            </section>
        </div>
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#blog">Blog</a></li>
                <li><a href="#faq">FAQs</a></li>
                <li><a href="#news">News</a></li>
                <li><a href="#innovation">Innovation</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Contact Form</h3>
            <!-- Display the form message -->
            <?php if (!empty($formMessage)): ?>
            <p style="color: <?php echo $formMessageType === 'success' ? 'green' : 'red'; ?>;"><?php echo $formMessage; ?></p>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <input type="text" name="name" placeholder="Your Name" required value="<?php echo isset($_POST['name']) && $formMessageType !== 'success' ? htmlspecialchars($_POST['name']) : ''; ?>">
                <input type="email" name="email" placeholder="Your Email" required value="<?php echo isset($_POST['email']) && $formMessageType !== 'success' ? htmlspecialchars($_POST['email']) : ''; ?>">
                <textarea name="message" placeholder="Your Message" required><?php echo isset($_POST['message']) && $formMessageType !== 'success' ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                <button type="submit" name="submit_contact_form">Send</button>
            </form>
        </div>
    </div>
    <div class="footer-social">
        <h3>Follow Us</h3>
        <p>Find us on social media.</p>
        <a href="https://www.facebook.com/NyakwarOreraJnr" target="_blank"><i class="fab fa-facebook-f"></i></a>
        <a href="https://twitter.com/ogonyopatrick" target="_blank"><i class="fab fa-twitter"></i></a>
        <a href="mailto:patrickogonyo76@gmail.com" target="_blank"><i class="fab fa-google"></i></a>
        <a href="https://wa.me/254740704740" target="_blank"><i the "fab fa-whatsapp"></i></a>
        <a href="https://www.youtube.com/@patrickogonyo1316" target="_blank"><i class="fab fa-youtube"></i></a>
    </div>
    <div class="footer-bottom">
        <p>Copyright &copy; <?php echo date("Y"); ?> Designed by Patrick Ogonyo</p>
    </div>
</footer>

