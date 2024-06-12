<?php
include('../includes/confirmlogin1.php');
check_login();
include("../includes/custheader.php");
include('../includes/dbconnect.php'); // Include your database connection file

// Fetch team members from the database
$sql = "SELECT FirstName, LastName, role, Photo FROM admin WHERE Status = 1";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$teamMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Echochick Farm</title>
    <link rel="stylesheet" href="../chattels/css/style.css">
    <link rel="icon" type="image/x-icon" href="../chattels/images/farmimages/favicon1.png"> 
</head>
<body>
    <header>
        <h1>About Echochick Farm</h1>
    </header>
    <main>
        <section class="about-section">
            <img src="../chattels/images/farmimages/about-us.jpg" alt="About Echochick Farm" class="about-image">
            <div class="about-text">
                <h2>Welcome to Echochick Farm</h2>
                <p>At Echochick Farm, we are dedicated to sustainable farming practices that respect the environment and ensure the highest quality of life for our poultry. Our farm is nestled in the heart of the countryside, where we raise our chickens in spacious, clean environments, allowing them to roam freely. This not only enhances their wellbeing but also enriches the quality of our produce.</p>
                <p>With years of expertise and a deep passion for agriculture, we are proud to bring to your table fresh, organic, and nutritious poultry products. Join us in embracing a healthy lifestyle with every bite of our ethically raised offerings.</p>
            </div>
        </section>
        <center>
            <h2>Our Mission, Vision, and Core Values</h2>
            </center>
            <section class="grid-container">
    <div class="mission-vision-values">
        <h2>Our Mission, Vision, and Core Values</h2>
        <div>
            <h3>Mission Statement</h3>
            <p>To provide high-quality, sustainably farmed poultry products while enhancing animal welfare and contributing positively to our community.</p>
        </div>
        <div>
            <h3>Vision Statement</h3>
            <p>To be a leader in sustainable agriculture, promoting a healthy lifestyle through ethically produced food, accessible to all.</p>
        </div>
        <div>
            <h3>Core Values</h3>
            <ul>
                <li>Integrity in all our practices</li>
                <li>Respect for nature and animals</li>
                <li>Commitment to community and education</li>
            </ul>
        </div>
    </div>
    <div class="history">
        <h2>Our History</h2>
        <p>Founded in 2018, Echochick Farm has evolved from a small family-run operation into a beacon of sustainable farming, setting benchmarks in the industry and fostering a close-knit community of like-minded individuals.</p>
    </div>
</section>

            <center>
            <h2>Meet Our Team</h2>
            </center>

        <section class="team">
    <?php foreach ($teamMembers as $member): ?>
        <div class="team-member">
            <img src="../chattels/images/Profiles/<?php echo htmlspecialchars($member['Photo']); ?>" alt="<?php echo htmlspecialchars($member['FirstName'] . ' ' . $member['LastName']); ?>">
            <div>
                <h3><?php echo htmlspecialchars($member['FirstName'] . ' ' . $member['LastName']); ?></h3>
                <p><?php echo htmlspecialchars($member['role']); ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</section>

    </main>
</body>
</html>
