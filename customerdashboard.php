<?php 
include("../includes/confirmlogin1.php");

// Assuming the session is already started and checked in confirmlogin1.php
$customerId = $_SESSION['userid'];

try {
     //Customer Profile Details
     $sqlProfile = "SELECT FirstName, LastName, Telephone, Email, Address, Photo FROM customer WHERE id = :customerId";
    $stmtProfile = $pdo->prepare($sqlProfile);
    $stmtProfile->bindParam(':customerId', $customerId, PDO::PARAM_INT);
    $stmtProfile->execute();
    $profileDetails = $stmtProfile->fetch(PDO::FETCH_ASSOC);

    // Order Tracking Status
    $sqlTracking = "SELECT order_tracking.order_status, order_tracking.delivery_date, products.ProductName FROM order_tracking JOIN products ON products.id = order_tracking.product_id WHERE order_tracking.customer_id = :customerId ORDER BY order_tracking.delivery_date DESC LIMIT 3";
$stmtTracking = $pdo->prepare($sqlTracking);
$stmtTracking->bindParam(':customerId', $customerId, PDO::PARAM_INT);
$stmtTracking->execute();
$trackingDetails = $stmtTracking->fetchAll(PDO::FETCH_ASSOC);

    // Recent News
    $sqlNews = "SELECT title, content, published_date FROM news ORDER BY published_date DESC LIMIT 5";
    $stmtNews = $pdo->query($sqlNews);
    $newsArticles = $stmtNews->fetchAll(PDO::FETCH_ASSOC);

    //Personalized Product Recommendations
    $sqlRecommendations = "SELECT DISTINCT p.id, p.ProductName, p.ProductImage FROM products p JOIN orders o ON p.id = o.ProductId WHERE o.CustomerId = :customerId AND p.CategoryName IN (SELECT CategoryName FROM products JOIN orders ON products.id = orders.ProductId WHERE orders.CustomerId = :customerId) ORDER BY RAND() LIMIT 3";
$stmtRecommendations = $pdo->prepare($sqlRecommendations);
$stmtRecommendations->bindParam(':customerId', $customerId, PDO::PARAM_INT);
$stmtRecommendations->execute();
$recommendedProducts = $stmtRecommendations->fetchAll(PDO::FETCH_ASSOC);


    // Counting registered customers
    $sqlCustomers = "SELECT id FROM customer";
    $queryCustomers = $pdo->prepare($sqlCustomers);
    $queryCustomers->execute();
    $registeredCustomersCount = $queryCustomers->rowCount();

    // Total Products
    $sqlProducts = "SELECT COUNT(id) AS total_products FROM products";
    $stmtProducts = $pdo->query($sqlProducts);
    $totalProducts = $stmtProducts->fetchColumn();

    // Total Customer Orders
    $sqlOrders = "SELECT COUNT(id) AS total_orders FROM orders WHERE CustomerId = :customerId";
    $stmtOrders = $pdo->prepare($sqlOrders);
    $stmtOrders->bindParam(':customerId', $customerId, PDO::PARAM_INT);
    $stmtOrders->execute();
    $totalOrders = $stmtOrders->fetchColumn();

    // Total Sales for Customer
    $sqlSales = "SELECT SUM(orders.Quantity*products.ProductPrice) AS total_sales FROM orders JOIN products ON products.id = orders.ProductId WHERE CustomerId = :customerId";
    $stmtSales = $pdo->prepare($sqlSales);
    $stmtSales->bindParam(':customerId', $customerId, PDO::PARAM_INT);
    $stmtSales->execute();
    $totalCustomerSales = $stmtSales->fetchColumn();

    // Last Order Details
    $sqlLastOrder = "SELECT InvoiceNumber, InvoiceGenDate FROM orders WHERE CustomerId = :customerId ORDER BY InvoiceGenDate DESC LIMIT 1";
    $stmtLastOrder = $pdo->prepare($sqlLastOrder);
    $stmtLastOrder->bindParam(':customerId', $customerId, PDO::PARAM_INT);
    $stmtLastOrder->execute();
    $lastOrder = $stmtLastOrder->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Handle exception
    die("Database error: " . $e->getMessage());
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard | Echochick</title>
    <link rel="stylesheet" href="../chattels/css/style.css">
    <link rel="icon" type="image/x-icon" href="../chattels/images/farmimages/favicon1.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
      /* Profile Image Styling */
      .profile-image {width: 100px; /* Adjust the width as necessary */height: 100px; /* Adjust the height as necessary */border-radius: 50%; /* Circular image */object-fit: cover; /* Ensures the image covers the area without being stretched */border: 3px solid #fff; /* White border around the image */box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Subtle shadow for depth */margin-bottom: 20px; /* Space below the image */}
      /* Container for the search form to position it to the right */
      .search-container {float: right; /* Aligns the container to the right */padding: 10px; /* Adds some padding around the container */}.search-form input[type="text"] {padding: 10px;margin: 5px;border: 1px solid #ccc;border-radius: 4px;}.search-form button {padding: 10px 20px;background-color: #007BFF;color: white;border: none; border-radius: 4px;cursor: pointer;}

    </style>
</head>
<body>
<header>
    <?php include("../includes/custheader.php"); ?>
</header>
<h1>Customer Dashboard</h1>
<div class="search-container">
    <form class="search-form" action="customerdashboard.php" method="GET">
        <input type="text" name="search_query" placeholder="Search orders, products..." required>
        <button type="submit">Search</button>
    </form>
</div>


<div class="cards-container d-flex flex-wrap justify-content-between">
    <!-- Customer Profile Details -->
    <div class="card bg-gradient-danger card-img-holder text-white" style="height: 300px;">
      <div class="profile-details">
        <img src="../chattels/images/Profiles/<?php echo $profileDetails['Photo']; ?>" alt="Profile Photo" class="profile-image">
        <h3><?php echo htmlentities($profileDetails['FirstName']) . ' ' . htmlentities($profileDetails['LastName']); ?></h3>
        <p>Email: <?php echo htmlentities($profileDetails['Email']); ?></p>
        <p>Phone: <?php echo htmlentities($profileDetails['Telephone']); ?></p>
        <p>Address: <?php echo htmlentities($profileDetails['Address']); ?></p>
      </div>
    </div>
    <div class="card bg-gradient-white card-img-holder text-white" style="height: 300px;">
      <canvas id="myChart"></canvas>
    </div>
    <!-- Recent News -->
    <div class="card bg-gradient-primary card-img-holder text-white" style="height: 300px;">
      <h4>Latest News</h4>
      <?php foreach ($newsArticles as $article) { ?>
      <div class="news-article">
        <h5><?php echo htmlentities($article['title']); ?></h5>
        <p><?php echo substr(htmlentities($article['content']), 0, 100) . '...'; ?></p>
        <small>Published on: <?php echo date('d-m-Y', strtotime($article['published_date'])); ?></small>
      </div>
      <?php } ?>
    </div>
    
</div>

<div class="cards-container d-flex flex-wrap justify-content-between">
  <div class="card bg-gradient-info card-img-holder text-white" style="height: 150px;">
    <div class="card-body">
      <h4 class="font-weight-normal mb-3">Registered Customers</h4>
      <h2 class="mb-5"><?php echo htmlentities($registeredCustomersCount); ?></h2>
    </div>
  </div>

  <div class="card bg-gradient-warning card-img-holder text-white" style="height: 150px;">
    <div class="card-body">
      <h4 class="font-weight-normal mb-3">Products Purchased</h4>
      <h2 class="mb-5"><?php echo $totalProducts; ?></h2>
    </div>
  </div>

  <div class="card bg-gradient-success card-img-holder text-white" style="height: 150px;">
    <div class="card-body">
      <h4 class="font-weight-normal mb-3">Total Orders</h4>
      <h2 class="mb-5"><?php echo $totalOrders; ?></h2>
    </div>
  </div>
  <div class="card bg-gradient-success card-img-holder text-white" style="height: 150px;">
    <div class="card-body">
      <h4 class="font-weight-normal mb-3">Pending Orders</h4>
      <h2 class="mb-5"><?php echo $totalOrders; ?></h2>
    </div>
  </div>
  <div class="card bg-gradient-primary card-img-holder text-white" style="height: 150px;">
    <div class="card-body">
      <h4 class="font-weight-normal mb-3">Total Spending</h4>
      <h2 class="mb-5"><?php echo number_format($totalCustomerSales, 2); ?></h2>
    </div>
  </div>

  <div class="card bg-gradient-danger card-img-holder text-white" style="height: 150px;">
    <div class="card-body">
      <h4 class="font-weight-normal mb-3">Last Order</h4>
      <h2 class="mb-5"><?php echo htmlentities($lastOrder['InvoiceNumber']) . ' - ' . htmlentities(date("d-m-Y", strtotime($lastOrder['InvoiceGenDate']))); ?></h2>
    </div>
  </div>
</div>

<div class="main-panel">
<div class="">
  <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        
          <h5 class="modal-title" style="float: left;">Recent Orders</h5>
        <div class="card-body table-responsive p-3">
          <table class="table align-items-center table-flush table-hover" id="dataTableHover">
            <thead>
             <tr>
                <th>#</th>
                <th>Invoice Number</th>
                <th>Order Date</th>
                <th>Total Amount</th>
              </tr>
            </thead>
            <tbody>
  <?php
  // Corrected SQL query with the right column name
  $sql = "SELECT InvoiceNumber, InvoiceGenDate, SUM(orders.Quantity*products.ProductPrice) AS OrderTotal FROM orders JOIN products ON products.id = orders.ProductId WHERE CustomerId = :customerId GROUP BY InvoiceNumber ORDER BY InvoiceGenDate DESC";
  $query = $pdo->prepare($sql);
  $query->bindParam(':customerId', $customerId, PDO::PARAM_INT);
  $query->execute();
  $results = $query->fetchAll(PDO::FETCH_OBJ);
  $cnt = 1;
  if ($query->rowCount() > 0) {
      foreach ($results as $result) {
          ?>
          <tr>
              <td><?php echo $cnt; ?></td>
              <td><?php echo htmlentities($result->InvoiceNumber); ?></td>
              <td><?php echo htmlentities(date("d-m-Y", strtotime($result->InvoiceGenDate))); ?></td>
              <td><?php echo number_format($result->OrderTotal, 2); ?></td>
          </tr>
          <?php
          $cnt++;
      }
  }
  ?>
</tbody>
                 
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  var ctx = document.getElementById('myChart').getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['chicks', 'Layers', 'Broilers', 'White Meat', 'Eggs', 'Sausages'],
      datasets: [{
        label: 'Farm Products',
        data: [12, 19, 3, 5, 2, 3],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
          'rgba(255, 159, 64, 0.2)'
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
          'rgba(255, 159, 64, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>

</body>
</html>
