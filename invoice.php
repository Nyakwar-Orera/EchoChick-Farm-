<?php
include('../includes/confirmlogin1.php');
check_login();
?>
<!DOCTYPE html>
<html lang="en">

<body>
<head>
    <title>Buy Products</title>
    <link rel="stylesheet" href="../chattels/css/style.css">
    <link rel="icon" type="image/x-icon" href="../chattels/images/farmimages/favicon1.png">   
  </head>
  <div class="container-scroller">
    
    <?php include("../includes/custheader.php");?>
    
    <div class="container-fluid page-body-wrapper">
      
      
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="modal-header">
                  <h5 class="modal-title" style="float: left;">View Invoices</h5>
                </div>
                
                <div class="card-body ">
                  <section class="hk-sec-wrapper hk-invoice-wrap pa-35">
                    <div class="invoice-from-wrap">
                      <div class="row">
                        <div class="col-md-7 mb-20">
                         <img style="height: 125px;" class="img-avatar mb-3" src="../chattels/images/farmimages/favicn.jpeg" alt="">
                          
                        </div>
                        <?php 
                        //Consumer Details
                        $inid = $_SESSION['invoice'];

                        // Prepare a SQL statement
                        $sql = "SELECT DISTINCT InvoiceNumber, CustomerName, CustomerContactNo, PaymentMode, InvoiceGenDate FROM orders WHERE InvoiceNumber = :inid";
                        $stmt = $pdo->prepare($sql);

                        // Bind parameters to statement variables
                        $stmt->bindParam(':inid', $inid, PDO::PARAM_STR);

                        // Execute the statement
                        $stmt->execute();

                        $cnt = 1;
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
                        {    
                          ?>
                          <div class="col-md-5 mb-20">
                            <h4 class="mb-35 font-weight-600">Invoice / Receipt</h4>
                            <table  border="0" >
                              <tr>
                                <td><strong >Date:</strong></td>
                                <td></td>
                                <td><?php  echo htmlentities(date("d-m-Y", strtotime($row['InvoiceGenDate'])));?></td>
                              </tr>
                              <tr>
                                <td><strong >Invoice / Receipt:</strong></td>
                                <td>&nbsp;</td>
                                <td><?php echo $row['InvoiceNumber'];?></td>
                              </tr>
                              <tr>
                                <td><strong >Customer:</strong></td>
                                <td></td>
                                <td><?php echo $row['CustomerName'];?></td>
                              </tr>
                              <tr>
                                <td><strong >Customer Mobile No:</strong></td>
                                <td></td>
                                <td>0<?php echo $row['CustomerContactNo'];?></td>
                              </tr>
                              <tr>
                                <td><strong >Payment Mode:</strong></td>
                                <td></td>
                                <td><?php echo $row['PaymentMode'];?></td>
                              </tr>
                            </table>
                          </div>
                          <?php
                        } ?>
                      </div>
                    </div>
                    <dir>&nbsp;</dir>
                    <div class="row">
                      <div class="card-body table-responsive p-3">
                        <div class="table-wrap">
                          <table  class="table align-items-center table-bordered " id="dataTableHover">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th >Product Name</th>
                                <th>Category</th>
                                <th >Quantity</th>
                                <th >Unit Price</th>
                                <th >Price</th>

                              </tr>
                            </thead>
                            <tbody>
                            <?php
// $pdo is your PDO connection variable
$inid = $_SESSION['invoice'];

// Prepare the SQL statement
$sql = "SELECT p.CategoryName, p.ProductName, p.ProductImage, p.ProductPrice, o.Quantity 
        FROM orders o 
        JOIN products p ON p.id = o.ProductId 
        WHERE o.InvoiceNumber = :inid";
$stmt = $pdo->prepare($sql);

// Bind parameters
$stmt->bindParam(':inid', $inid, PDO::PARAM_STR);

// Execute the query
$stmt->execute();

$cnt = 1;
$grandtotal = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $qty = $row['Quantity'];
    $ppu = $row['ProductPrice'];
    $subtotal = $ppu * $qty;
    ?>
    <tr>
        <td><?php echo $cnt; ?></td>
        <td><img src="../chattels/images/products/<?php echo htmlspecialchars($row['ProductImage']); ?>" class="mr-2" alt="image"><?php echo htmlspecialchars($row['ProductName']); ?></td>
        <td><?php echo htmlspecialchars($row['CategoryName']); ?></td>
        <td><?php echo $qty; ?></td>
        <td><?php echo $ppu; ?></td>
        <td><?php echo $subtotal; ?></td>
    </tr>
    <?php
    $grandtotal += $subtotal;
    $cnt++;
}
?>
<tr>
    <th colspan="5" style="text-align:center; font-size:20px;">Total</th> 
    <th style="text-align:left; font-size:20px;"><?php echo number_format($grandtotal, 0); ?></th>   
</tr>
                                      
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </section>
                </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
      
    </div>
    
  </div>
  
</body>
</html>
