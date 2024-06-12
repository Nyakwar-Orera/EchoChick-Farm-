<?php
include('../includes/confirmlogin.php');
check_login();

include('../includes/dbconnect.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" type="image/x-icon" href="../chattels/images/farmimages/favicon1.png">
    <?php include("../includes/head.php"); ?>
</head>
<body>
<header>
    <?php include("../includes/adminheader.php"); ?>
</header>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="modal-header">
                  <h5 class="modal-title" style="float: left;">View Invoices</h5>
                </div>
                
                <div class="card-body">
                  <div class="text-right mb-3">
                    <button id="downloadPdf" class="btn btn-success">Download as PDF</button>
                    <button id="printPage" class="btn btn-primary">Print Page</button>
                  </div>
                  <section class="hk-sec-wrapper hk-invoice-wrap pa-35" id="invoice">
                    <div class="invoice-from-wrap">
                      <div class="row">
                        <div class="col-md-7 mb-20">
                          <img style="height: 125px;" class="img-avatar mb-3" src="assets/img/companyimages/logo.jpg" alt="">
                        </div>
                        <?php
                        $grandtotal = 0;
                        $inid = isset($_GET['invid']) ? substr(base64_decode($_GET['invid']), 0, -5) : '';
                        $stmt = $pdo->prepare("SELECT DISTINCT InvoiceNumber, CustomerName, CustomerContactNo, PaymentMode, InvoiceGenDate FROM orders WHERE InvoiceNumber=?");
                        $stmt->execute([$inid]);
                        while ($row = $stmt->fetch()) {
                        ?>
                          <div class="col-md-5 mb-20">
                            <h4 class="mb-35 font-weight-600">Invoice / Receipt</h4>
                            <table border="0">
                              <tr>
                                <td><strong>Date:</strong></td>
                                <td><?php echo htmlentities(date("d-m-Y", strtotime($row['InvoiceGenDate']))); ?></td>
                              </tr>
                              <tr>
                                <td><strong>Invoice / Receipt:</strong></td>
                                <td><?php echo $row['InvoiceNumber']; ?></td>
                              </tr>
                              <tr>
                                <td><strong>Customer:</strong></td>
                                <td><?php echo $row['CustomerName']; ?></td>
                              </tr>
                              <tr>
                                <td><strong>Customer Mobile No:</strong></td>
                                <td>0<?php echo $row['CustomerContactNo']; ?></td>
                              </tr>
                              <tr>
                                <td><strong>Payment Mode:</strong></td>
                                <td><?php echo $row['PaymentMode']; ?></td>
                              </tr>
                            </table>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                    <div>&nbsp;</div>
                    <div class="row">
                      <div class="card-body table-responsive p-3">
                        <div class="table-wrap">
                          <table class="table align-items-center table-bordered" id="dataTableHover">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Price</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $stmt = $pdo->prepare("SELECT products.CategoryName, products.ProductName, products.ProductImage, products.ProductPrice, orders.Quantity FROM orders JOIN products ON products.id = orders.ProductId WHERE orders.InvoiceNumber=?");
                              $stmt->execute([$inid]);
                              $cnt = 1;
                              while ($row = $stmt->fetch()) {
                                $qty = $row['Quantity'];
                                $ppu = $row['ProductPrice'];
                                $subtotal = ($ppu * $qty);
                                ?>
                                <tr>
                                  <td><?php echo $cnt; ?></td>
                                  <td><img src="../chattels/images/products/<?php echo $row['ProductImage']; ?>" class="mr-2" alt="image"><?php echo $row['ProductName']; ?></td>
                                  <td><?php echo $row['CategoryName']; ?></td>
                                  <td><?php echo $qty; ?></td>
                                  <td><?php echo $ppu; ?></td>
                                </tr>
                                <?php
                                $grandtotal += $subtotal;
                                $cnt++;
                              }?>
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
  
<script>
    $(document).ready(function() {
        $("#customCancelBtn").click(function() {
            $(this).closest('.modal').modal('hide');
        });

        // Function to download PDF
        $('#downloadPdf').click(function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            html2canvas(document.querySelector("#invoice")).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const imgProps= doc.getImageProperties(imgData);
                const pdfWidth = doc.internal.pageSize.getWidth();
                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
                doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                doc.save("invoice.pdf");
            });
        });

        // Function to print the page
        $('#printPage').click(function() {
            var invoiceContent = document.getElementById('invoice').innerHTML;
            var originalContent = document.body.innerHTML;

            document.body.innerHTML = invoiceContent;
            window.print();
            document.body.innerHTML = originalContent;
        });
    });
</script>
    </script>
</body>
</html>
