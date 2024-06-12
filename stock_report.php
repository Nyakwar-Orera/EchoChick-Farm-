<?php
include('../includes/confirmlogin.php');
check_login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ECHOCHICK-FARM | Current Stock</title>
  <link rel="icon" type="image/x-icon" href="../chattels/images/farmimages/favicon1.png">
  <?php include("../includes/head.php"); ?>
</head>
<body>
<header>
    <?php include("../includes/adminheader.php"); ?>
</header>

<h3 class="modal-title" style="float: left;">Current Stock Report</h3>    
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper">
        <div class="card-body table-responsive p-3">
          <button id="downloadPdf" class="btn btn-success">Download as PDF</button>
            <button id="printPage" class="btn btn-primary">Print Page</button>
            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                <thead>
                    <tr>
                        <th class="text-center">Date</th>
                        <th class="text-center">Item</th>
                        <th class="text-center">Qty Remaining</th>
                        <th class="text-center">Rate</th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql="SELECT * from store_stock where status='1'";
                    $query = $pdo -> prepare($sql);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    if($query->rowCount() > 0) {
                        foreach($results as $row) {
                            $total = $row->quantity_remaining * $row->rate;  
                    ?>
                            <tr>
                                <td class="font-w600"><?php echo htmlentities(date("d-m-Y", strtotime($row->date)));?></td>
                                <td class="font-w600"><?php echo htmlentities($row->item);?></td>
                                <td class="font-w600"><?php echo htmlentities($row->quantity_remaining);?></td>
                                <td class="font-w600"><?php echo htmlentities(number_format($row->rate, 0, '.', ','));?></td>
                                <td class="font-w600"><?php echo htmlentities(number_format($total, 0, '.', ','));?></td>
                            </tr>
                    <?php 
                        }
                    } ?>
                </tbody>                  
            </table>
        </div>
    </div>
</div>
<?php include("../includes/foot.php"); ?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script>
    $(document).ready(function() {
        $('#downloadPdf').click(function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            html2canvas(document.querySelector("#dataTableHover")).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                const imgProps= doc.getImageProperties(imgData);
                const pdfWidth = doc.internal.pageSize.getWidth();
                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
                doc.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                doc.save("current-stock-report.pdf");
            });
        });

        $('#printPage').click(function() {
            var originalContent = document.body.innerHTML;
            var printContent = document.getElementById('dataTableHover').outerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
        });
    });
</script>

</body>
</html>
