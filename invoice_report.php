<?php
include('../includes/confirmlogin.php');
check_login();
?>
<!DOCTYPE html>
<html lang="en">
<div class="container-scroller">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ECHOCHICK-FARM | Invoices Reports</title>
  <link rel="icon" type="image/x-icon" href="../chattels/images/farmimages/favicon1.png">
  
  <?php include("../includes/head.php"); ?>
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
</head>
<body>
<header>
    <?php include("../includes/adminheader.php"); ?>
</header>

<div class="container-fluid page-body-wrapper">
    <div class="modal-header">
        <h3 class="modal-title" style="float: left;">Invoices Report</h3>
    </div>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
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
                                $rno = mt_rand(10000, 99999); 
                                $sql = "SELECT DISTINCT InvoiceNumber, CustomerName, CustomerContactNo, PaymentMode, InvoiceGenDate FROM orders ORDER BY id DESC";
                                $query = $pdo->prepare($sql);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;
                                if ($query->rowCount() > 0) {
                                    foreach ($results as $row) {  
                                ?>
                                <tr>
                                    <td><?php echo $cnt; ?></td>
                                    <td class="font-w600"><?php echo htmlentities($row->InvoiceNumber); ?></td>
                                    <td class="font-w600"><?php echo htmlentities($row->CustomerName); ?></td>
                                    <td class="font-w600">0<?php echo htmlentities($row->CustomerContactNo); ?></td>
                                    <td class="font-w600"><?php echo htmlentities($row->PaymentMode); ?></td>
                                    <td class="font-w600"><?php echo htmlentities(date("d-m-Y", strtotime($row->InvoiceGenDate))); ?></td>
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
</div>

<!-- jQuery and DataTables JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#dataTableHover').DataTable({
        "searching": true,
        "paging": true,
        "info": true
    });
});
</script>

<?php include("../includes/foot.php");?>

</body>
</html>
