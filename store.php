<?php
include('../includes/confirmlogin.php');
check_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ECHOCHICK-FARM | Store</title>
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
                            <h5 class="modal-title" style="float: left;">Farm Store</h5>
                            <div class="card-tools" style="float: right;">
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#stockin-modal"><i class="fas fa-plus"></i>&nbsp; Stock In</button>
                            </div>
                            
                            <!-- Stock In Modal -->
                            <div class="modal fade" id="stockin-modal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Stock In</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" id="stockin-form-content">
                                          <?php include("newitem-form.php"); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Deleted Items Modal -->
                            <div class="modal fade" id="deleted-items">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Deleted Items</h4>
                                            </div>
                                            <div class="modal-body">
                                                <?php include("deleted-items.php"); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Item Out Modal -->
                                <div id="itemout" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Item Out Form</h5>
                                            </div>
                                            <div class="modal-body" id="info_update2">
                                                <?php include("store_out.php"); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>   
                        </div>
                    </div>
                    <!-- Data Table -->
                    <div class="card-body">
                                    <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Date</th>
                                                <th class="text-center">Item</th>
                                                <th class="text-center">Qty Remaining</th>
                                                <th class="text-center">Rate</th>
                                                <th class="text-center">Total</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * from store_stock where status='1'";
                                            $query = $pdo->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $row) {
                                                    $remaining = $row->quantity_remaining;
                                                    $rate = $row->rate;
                                                    $total = ($remaining * $rate);
                                                    ?>
                                                    <tr>
                                                        <td class="font-w600"><?php echo htmlentities(date("d-m-Y", strtotime($row->date))); ?></td>
                                                        <td class="font-w600"><?php echo htmlentities($row->item); ?></td>
                                                        <td class="font-w600"><?php echo htmlentities($row->quantity_remaining); ?></td>
                                                        <td class="font-w600"><?php echo htmlentities(number_format($row->rate, 0, '.', ',')); ?></td>
                                                        <td class="font-w600"><?php echo htmlentities(number_format($total, 0, '.', ',')); ?></td>
                                                        <td>
                                                            <a class="btn btn-danger btn-xs edit_data" href="store.php?delid=<?php echo ($row->id); ?>" onclick="return confirm('Do you really want to delete?');" title="Delete this item">
                                                                <i class="fa fa-trash fa-delete" style="color: #fff" aria-hidden="true"></i>
                                                            </a>
                                                            <button class="btn btn-danger btn-xs edit_data" id="<?php echo ($row->id); ?>">Item Out</button>
                                                        </td>
                                                    </tr>
                                                    <?php
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
</div>
<?php include("../includes/foot.php"); ?>
<style>
     .modal-header {background: #f5f5f5;border-bottom: 1px solid #ddd;padding: 10px 35px;}
     .modal-title {margin: 0;line-height: 1.5;font-weight: bold;}
     .modal-body {padding: 20px;}
     .btn {padding: 8px 12px;border: none;border-radius: 10px;cursor: pointer;transition: background-color 0.3s ease; /* Smooth transition for hover effects */}
     .btn:hover {opacity: 0.9; /* Slight transparency effect on hover for buttons */}
     .btn-xs {font-size: 0.8em; padding: 5px 10px;}
     .card { background: linear-gradient(to right, #1e3c72, #2a5298); border-radius: 10px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); margin-top: 20px;}/* Card Styling */
     .forms-sample { margin-top: 10px;}/* Form Styling */
     .form-group { margin-bottom: 15px;display: flex;flex-direction: column;align-items: flex-start;}/* Form Group Styling */
     .form-control { border-radius: 5px;border: 1px solid #ccc; padding: 10px; font-size: 14px;width: 80%; /* Ensure the input fields take full width */}/* Input Field Styling */
     label {font-weight: bold; margin-bottom: 5px;display: block;text-align: left; /* Align label text to the left */}
     /* Button Styling */
    .btn-primary {background-color: #007bff; border-color: #007bff; color: white; padding: 10px 20px; border-radius: 5px;transition: background-color 0.3s, border-color 0.3s;}
    
 </style>

<!-- JavaScript for dynamic form loading -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#stockin-modal').on('show.bs.modal', function(e) {
            var loadUrl = "store_stock.php"; // Define the URL to load.
            $('#stockin-form-content').load(loadUrl);
                                });

                                $(document).on('click', '.edit_data', function() {
                                    var edit_id = $(this).attr('id');
                                    $.ajax({
                                        url: "store_out.php",
                                        type: "post",
                                        data: { edit_id: edit_id },
                                        success: function(data) {
                                            $("#info_update2").html(data);
                                            $("#itemout").modal('show');
                                        }
                                    });
                                });
                            });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $("#customCancelBtn").click(function() {
            $(this).closest('.modal').modal('hide');
        });
    });
</script>

</body>
</html>
