<?php 
include("../includes/confirmlogin.php");

// Fetch Registered Admins
$sql ="SELECT ID from admin where Status='1'";
$query = $pdo -> prepare($sql);
$query->execute();
$registeredUsersCount=$query->rowCount();

// Counting registered customers
$sqlCustomers ="SELECT id FROM customer";
$queryCustomers = $pdo->prepare($sqlCustomers);
$queryCustomers->execute();
$registeredCustomersCount = $queryCustomers->rowCount();

// Total Products
$sql2 = $pdo->prepare("SELECT COUNT(id) AS total_products FROM products");
$sql2->execute();
$totalProducts = $sql2->fetchColumn();

// Total Sales Till Date
$sql3 = $pdo->prepare("SELECT SUM(orders.Quantity*products.ProductPrice) AS total_sales FROM orders JOIN products ON products.id = orders.ProductId");
$sql3->execute();
$totalSalesTillDate = $sql3->fetchColumn();

// Weekly Sales
$sql4 = $pdo->prepare("SELECT SUM(orders.Quantity*products.ProductPrice) AS weekly_sales FROM orders JOIN products ON products.id = orders.ProductId WHERE date(orders.InvoiceGenDate) >= (DATE(NOW()) - INTERVAL 7 DAY)");
$sql4->execute();
$weeklySales = $sql4->fetchColumn();

// Yesterday  Total Sales
$qurys = $pdo->prepare("SELECT SUM(orders.Quantity*products.ProductPrice) AS yesterday FROM orders JOIN products ON products.id = orders.ProductId WHERE date(orders.InvoiceGenDate) = CURDATE() - 1");
$qurys->execute();
$yesterdaySales = $qurys->fetchColumn();

// Today's Total Sales
$querybb = $pdo->prepare("SELECT SUM(orders.Quantity*products.ProductPrice) AS today FROM orders JOIN products ON products.id = orders.ProductId WHERE date(orders.InvoiceGenDate) = CURDATE()");
$querybb->execute();
$todaySales = $querybb->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Echochick Farm</title>
    <link rel="icon" type="image/x-icon" href="../chattels/images/farmimages/favicon1.png"> <!-- Corrected the file extension -->
    
    <?php include("../includes/head.php");?>
</head>

<body>
<header>
<?php include("../includes/adminheader.php"); ?>
</header>
<h2> Admin Dashboard</h2>

<div class="cards-container d-flex flex-wrap justify-content-between">
  <div class="card bg-gradient-info card-img-holder text-white" style="height: 150px;">
    <div class="card-body">
      <h4 class="font-weight-normal mb-3">Registered Admins</h4>
      <h2 class="mb-5"><?php echo htmlentities($registeredUsersCount); ?></h2>
    </div>
  </div>

  <div class="card bg-gradient-danger card-img-holder text-white" style="height: 150px;">
    <div class="card-body">
      <h4 class="font-weight-normal mb-3">Registered Customers</h4>
      <h2 class="mb-5"><?php echo htmlentities($registeredCustomersCount); ?></h2>
    </div>
  </div>

  <div class="card bg-gradient-warning card-img-holder text-white" style="height: 150px;">
    <div class="card-body">
      <h4 class="font-weight-normal mb-3">Total Products</h4>
      <h2 class="mb-5"><?php echo $totalProducts; ?></h2>
    </div>
  </div>

  <div class="card bg-gradient-success card-img-holder text-white" style="height: 150px;">
    <div class="card-body">
      <h4 class="font-weight-normal mb-3">Total Sales Till Date</h4>
      <h2 class="mb-5"><?php echo number_format($totalSalesTillDate, 2); ?></h2>
    </div>
  </div>

  <div class="card bg-gradient-primary card-img-holder text-white" style="height: 150px;">
    <div class="card-body">
      <h4 class="font-weight-normal mb-3">Weekly Sales</h4>
      <h2 class="mb-5"><?php echo number_format($weeklySales, 2); ?></h2>
    </div>
  </div>

  <div class="card bg-gradient-danger card-img-holder text-white" style="height: 150px;">
    <div class="card-body">
      <h4 class="font-weight-normal mb-3">Yesterday Total Sales</h4>
      <h2 class="mb-5"><?php echo number_format($yesterdaySales, 2); ?></h2>
    </div>
  </div>

  <div class="card bg-gradient-danger card-img-holder text-white" style="height: 150px;">
    <div class="card-body">
      <h4 class="font-weight-normal mb-3">Today's Total Sales</h4>
      <h2 class="mb-5"><?php echo number_format($todaySales, 2); ?></h2>
    </div>
  </div>
</div>
</div>

<div class="col-md-6">
  <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
    <?php
      $farmname=$_SESSION['farmname'];
      $sql="SELECT * from  farm where developer='Patrick_Ogonyo'";
      $query = $pdo -> prepare($sql);
      // $query->bindParam(':aid',$farmname,PDO::PARAM_STR);
      $query->execute();
      $results=$query->fetchAll(PDO::FETCH_OBJ);
      $cnt=1;
      if($query->rowCount() > 0)
      {
        foreach($results as $row)
          {  
            if($row->logo=="poultry.png")
              { 
              ?>
              <a class="navbar-brand brand-logo " href="admindashboard.php"><img class="img-avatar" style="height: 60px; width: 60px;" src="../chattels/images/farmimages/logo.jpg" alt=""></a>
              <a class="navbar-brand brand-logo-mini" href="admindashboard.php"><img style="height: 30px; width: 30px;" src="../chattels/images/farmimages/logo.jpg" alt="logo" /></a>
              <?php 
                } else { ?>
                <a class="navbar-brand brand-logo " href="admindashboard.php"><img class="img-avatar" style="height: auto; width: 400px;" src="../chattels/images/farmimages/<?php  echo $row->farmlogo;?>" alt=""></a>
                      
                <?php 
                } ?>
                <?php
                }
                }
                  ?>
                </div>
                <div id="piechart" style="width: 100%; height: 500px;"></div>
            </div>
        </div>
    </div>
</div>
<div class="main-panel">
<div class="">
  <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="modal-header">
          <h5 class="modal-title" style="float: left;">Recent Invoice</h5>
          </div>
            <div class="card-body table-responsive p-3">
              <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                <thead>
                 <tr>
                    <th>#</th>
                      <th>Invoice Number</th>
                      <th>Customer Name</th>
                      <th>Customer Contact no.</th>
                      <th>Payment Mode</th>
                      <th>Payment Date</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $rno=mt_rand(10000,99999); 
                    $sql="select distinct InvoiceNumber,CustomerName,CustomerContactNo,PaymentMode,InvoiceGenDate    from orders ORDER BY id DESC";
                    $query = $pdo -> prepare($sql);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    $cnt=1;
                    if($query->rowCount() > 0)
                    {
                      foreach($results as $row)
                      {  
                        ?>
                        <tr>
                          <td><?php echo $cnt;?></td>

                          <td class="font-w600"><?php  echo htmlentities($row->InvoiceNumber);?></td>
                          <td class="font-w600"><?php  echo htmlentities($row->CustomerName);?></td>
                          <td class="font-w600">0<?php  echo htmlentities($row->CustomerContactNo);?></td>
                          <td class="font-w600"><?php  echo htmlentities($row->PaymentMode);?></td>
                          <td class="font-w600"><?php  echo htmlentities(date("d-m-Y", strtotime($row->InvoiceGenDate)));?></td>
                          
                        </tr>

                        <?php 
                        $cnt++;
                      }
                    }?>
                  </tbody>                  
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php include("../includes/foot.php"); ?>

<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Chik',     50],
          ['Birds',      14],
          ['Meat',  17],
          ['Egg', 20]
        ]);

        var options = {
          title: 'Farm Store'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
<script >
  $(document).ready(function(){
    $.ajax({
      url: "data.php",
      method: "GET",
      success: function(data){
        console.log(data);
        var name = [];
        var marks = [];

        for (var i in data){
          name.push(data[i].Sector);

          marks.push(data[i].total);
        }
        var chartdata = {
          labels: name,
          datasets: [{
            label: 'student marks',
            backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
            borderColor: 'rgba(134, 159, 152, 1)',
            hoverBackgroundColor: 'rgba(230, 236, 235, 0.75)',
            hoverBorderColor: 'rgba(230, 236, 235, 0.75)',
            data: marks

          }]
        };
        var graphTarget = $("#graphCanvas");
        var barGraph = new Chart(graphTarget, {
          type: 'bar',
          data: chartdata,
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true
                }
              }]
            }
          }
        });
      },
      error: function(data) {
        console.log(data);
      }

    });
  });
</body>
</html>
