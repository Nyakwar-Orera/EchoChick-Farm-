<?php
// Include database connection
include('includes/dbconnect.php');
session_start();  // Start the session

// Prepare and execute query to fetch featured products
$featuredProductsQuery = "SELECT * FROM products WHERE featured = 1 LIMIT 3";
$featuredProductsResult = $pdo->query($featuredProductsQuery);

// Prepare and execute query to fetch the latest news
$newsQuery = "SELECT * FROM news ORDER BY published_date DESC LIMIT 3";
$newsResult = $pdo->query($newsQuery);

// Fetch specific farm information (adjust ID as necessary)
$query = $pdo->query("SELECT farmname, farmemail, farmtelephone, farmaddress FROM farm WHERE id = 2");
$farmInfo = $query->fetch(PDO::FETCH_ASSOC);


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
    <title>Home | Echochick Farm</title>
    <link rel="icon" type="image/x-icon" href="chattels/images/farmimages/favicon1.png">
    <link rel="stylesheet" href="chattels/css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: url('chattels/images/farmimages/background.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .section {
            background-color: rgba(255, 255, 255, 0.9);
            margin: 20px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 80%;
            max-width: 1200px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .product-item {
            flex: 1 1 30%;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            margin-bottom: 20px;
            background-color: #fff;
            border-radius: 5px;
        }
        img.product-image {
            width: 30%;
            height: auto;
            display: block;
            margin-bottom: 10px;
        }
        h3 {
            color: #333;
            font-size: 1.2em;
        }
        p {
            font-size: 1em;
            color: #666;
        }
        #about-us {
            background-color: rgba(255, 255, 255, 0.95);
                margin: 20px auto;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    width: 80%;
    max-width: 1200px;
        }
#about-us h2 {
    color: #333;
    font-size: 2em;
    margin-bottom: 10px;
}
#about-us p {
    font-size: 1.2em;
    color: #666;
    line-height: 1.6;
}

.team {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 20px;
  text-align: center;
}

.team-member {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 40px;
  background-color: #f9f9f9;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  width: 250px; /* Adjust the width as needed */
}

.team-member img {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  margin-bottom: 20px;
  object-fit: cover;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

    </style>
</head>
<body>
<header>
    <!-- Navigation bar setup -->
    <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="navbar-menu-wrapper d-flex align-items-stretch w-100">
            <div class="logo">
                <a class="navbar-brand brand-logo" href="#welcome-section">
                    <img src="chattels/images/farmimages/favicn.jpeg" alt="Farm Logo" style="height: 50px;">
                    Echochick Farm
                </a>
            </div>
            <ul class="navbar-nav">
                <li><a class="nav-link" href="#welcome-section">Home</a></li>
                <li><a class="nav-link" href="#featured-products">Products</a></li>
                <li><a class="nav-link" href="#about-us">About Us</a></li>
                <li><a class="nav-link" href="#contact-us">Contact Us</a></li>
                <li><a class="nav-link" href="login.php">Login</a></li>
            </ul>
        </div>
    </nav>
</header>

<main>
    <!-- Welcome section -->
    <section id="welcome-section" class="section">
        <h1>Welcome to Our Poultry Farm</h1>
        <p>An overview of our farm...</p>
    </section>
    <!-- About Us Section -->
    <section id="about-us" class="section">
        <h2>About Echochick Farm</h2>
        <p>
        At Echochick Farm, we are dedicated to sustainable farming practices that respect the environment and ensure the highest quality of life for our poultry. Our farm is nestled in the heart of the countryside, where we raise our chickens in spacious, clean environments, allowing them to roam freely. This not only enhances their wellbeing but also enriches the quality of our produce.
        </p>
        <p>
            With years of expertise and a deep passion for agriculture, we are proud to bring to your table fresh, organic, and nutritious poultry products. Join us in embracing a healthy lifestyle with every bite of our ethically raised offerings.
        </p>
    <img src="chattels/images/farmimages/about-us.jpg" alt="About Echochick Farm" style="width: 80%; height: auto; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.3);">
    </section>

    <!-- Featured products section -->
    <section id="featured-products" class="section">
        <h2>Featured Products</h2>
        <?php while ($product = $featuredProductsResult->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="product-item">
                <h3><?php echo htmlspecialchars($product['ProductName'] ?? 'No Name'); ?></h3>
                <p>Price: KES <?php echo htmlspecialchars($product['ProductPrice'] ?? '0'); ?></p>
                <?php if (!empty($product['ProductImage'])): ?>
                    <img src="chattels/images/products/<?php echo htmlspecialchars($product['ProductImage']); ?>" alt="<?php echo htmlspecialchars($product['ProductName'] ?? 'Product Image'); ?>" class="product-image">
                <?php else: ?>
                    <img src="chattels/images/product_images/default.png" alt="Default Image" class="product-image">
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </section>

    <!-- Latest news section -->
    <section id="latest-news" class="section">
        <h2>Latest News</h2>
        <?php while ($news = $newsResult->fetch(PDO::FETCH_ASSOC)): ?>
            <article>
                <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                <p><?php echo htmlspecialchars($news['content']); ?></p>
            </article>
        <?php endwhile; ?>
    </section>


        <section class="section">
        <center>
            <h2>Meet Our Team</h2>
            </center>
    <?php foreach ($teamMembers as $member): ?>
        <div class="team-member">
            <img src="chattels/images/Profiles/<?php echo htmlspecialchars($member['Photo']); ?>" alt="<?php echo htmlspecialchars($member['FirstName'] . ' ' . $member['LastName']); ?>">
            <div>
                <h3><?php echo htmlspecialchars($member['FirstName'] . ' ' . $member['LastName']); ?></h3>
                <p><?php echo htmlspecialchars($member['role']); ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</section>
</main>

<footer>
    <!-- Footer -->
    <?php include('includes/footer.php'); ?>
</footer>

<!-- Script for ready checks and display -->
<script>
function onReady(callback) {
    var intervalID = window.setInterval(checkReady, 1000);
    function checkReady() {
        if (document.getElementsByTagName('body')[0] !== undefined) {
            window.clearInterval(intervalID);
            callback.call(this);
        }
    }
}

function show(id, value) {
    document.getElementById(id).style.display = value ? 'block' : 'none';
}

onReady(function () {
    show('page', true);
    show('loading', false);
});
</script>
</body>
</html>
