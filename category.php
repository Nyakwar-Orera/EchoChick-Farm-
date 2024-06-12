<?php
include('../includes/confirmlogin.php');
check_login();

$message = ""; // To hold success or error messages

if (isset($_POST['save'])) {
    $category = $_POST['category'];
    $code = $_POST['code'];
    $sql = "INSERT INTO category (CategoryName, CategoryCode) VALUES (:category, :code)";
    $query = $pdo->prepare($sql);
    $query->bindParam(':category', $category, PDO::PARAM_STR);
    $query->bindParam(':code', $code, PDO::PARAM_STR);
    if ($query->execute()) {
        echo "<script>alert('Registered successfully');</script>";
        echo "<script>window.location.href ='category.php'</script>";
    } else {
        echo "<script>alert('Something went wrong. Please try again');</script>";
    }
}

if (isset($_GET['del'])) {
    $cmpid = $_GET['del'];
    $sql = "DELETE FROM category WHERE id = :id";
    $query = $pdo->prepare($sql);
    $query->bindParam(':id', $cmpid, PDO::PARAM_INT);
    try {
        $query->execute();
        echo "<script>alert('Category record deleted.');</script>";   
        echo "<script>window.location.href='category.php'</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ECHOCHICK-FARM | Category</title>
  <link rel="icon" type="image/x-icon" href="../chattels/images/farmimages/favicon1.png"> 
  
  <?php include("../includes/head.php");?>
</head>
<body>
  <header>
    <?php include("../includes/adminheader.php");?>
  </header>

  <div class="container-fluid page-body-wrapper">
    <div class="modal-header">
      <h3 class="modal-title" style="float: left;">Category Register</h3>
      <br>
    </div>
    
    <div class="content-wrapper">
      <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
          <div class="card bg-gradient-info card-img-holder text-white" style="height: 200px; width: 40%">
            <div class="col-md-12 mt-4">
              <form class="forms-sample" method="post" enctype="multipart/form-data">
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="exampleInputName1">Category</label>
                    <input type="text" name="category" class="form-control" id="category" placeholder="Enter Category" required>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="exampleInputName1">Code</label>
                    <input type="text" name="code" class="form-control" id="code" placeholder="Enter Code" required>
                  </div>
                </div>
                <button type="submit" style="float: left;" name="save" class="btn btn-primary mr-2 mb-4">Save</button>
              </form>
            </div>
          </div>
        </div>
        <div class="col-lg-12 grid-margin stretch-card">
            <div id="editData4" class="modal fade">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-body" id="info_update4">
                    <?php include("edit_category.php");?>
                  </div>
                </div>
              </div>
            </div>
            <div id="editData5" class="modal fade">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-body" id="info_update5">
                    <?php include("view_category.php");?>
                  </div>
                </div>
              </div>
            </div>
            </div>
            <div class="table-responsive p-3">
            <table class="table align-items-center table-flush table-hover table-bordered" id="dataTableHover">
                <thead>
                  <tr>
                    <th class="text-center">No</th>
                    <th>Category</th>
                    <th class="text-center">Category Code</th>
                    <th class="text-center">Posting Date</th>
                    <th class="text-center" style="width: 15%;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sql = "SELECT category.id, category.CategoryName, category.CategoryCode, category.PostingDate FROM category ORDER BY id DESC";
                  $query = $pdo->prepare($sql);
                  $query->execute();
                  $results = $query->fetchAll(PDO::FETCH_OBJ);
                  $cnt = 1;
                  if ($query->rowCount() > 0) {
                    foreach ($results as $row) {
                  ?>
                  <tr>
                    <td class="text-center"><?php echo htmlentities($cnt); ?></td>
                    <td><a href="#" class="edit_data5" id="<?php echo ($row->id); ?>"><?php echo htmlentities($row->CategoryName); ?></a></td>
                    <td class="text-center"><?php echo htmlentities($row->CategoryCode); ?></td>
                    <td class="text-center"><?php echo htmlentities(date("d-m-Y", strtotime($row->PostingDate))); ?></td>
                    <td class="text-center action-buttons">
                      <a href="#" class="edit_data4 btn btn-info rounded-circle" id="<?php echo ($row->id); ?>" title="Click to edit"><i class="mdi mdi-pencil-box-outline" aria-hidden="true"></i></a>
                      <a href="#" class="edit_data5 btn btn-secondary rounded-circle" id="<?php echo ($row->id); ?>" title="Click to view"><i class="mdi mdi-eye" aria-hidden="true"></i></a>
                      <a href="category.php?del=<?php echo $row->id; ?>" data-toggle="tooltip" data-original-title="Delete" class="btn btn-danger rounded-circle" onclick="return confirm('Do you really want to delete?');"><i class="mdi mdi-delete"></i></a>
                    </td>
                  </tr>
                  <?php
                    $cnt++;
                    }
                  } ?>
                </tbody>
              </table>
           
          </div>
        </div>
      </div>
    </div>
  </div>
 <style>
/* Card Styling */
.card { background: linear-gradient(to right, #1e3c72, #2a5298); border-radius: 10px; padding: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); margin-top: 20px;}
/* Form Styling */
.forms-sample { margin-top: 10px;}

/* Form Group Styling */
.form-group { margin-bottom: 15px;display: flex;flex-direction: column;align-items: flex-start;}

/* Input Field Styling */
.form-control { border-radius: 5px;border: 1px solid #ccc; padding: 10px; font-size: 14px;width: 80%; /* Ensure the input fields take full width */}

/* Label Styling */
label {font-weight: bold; margin-bottom: 5px;display: block;text-align: left; /* Align label text to the left */}

/* Button Styling */
.btn-primary {background-color: #007bff; border-color: #007bff; color: white; padding: 10px 20px; border-radius: 5px;transition: background-color 0.3s, border-color 0.3s;}
.btn-primary:hover { background-color: #0056b3;border-color: #0056b3;}
 </style>
  <script type="text/javascript">
    // Event handler for viewing category details
  $(document).ready(function(){
    $(document).on('click','.edit_data4',function(){
      var edit_id4=$(this).attr('id');
      $.ajax({
        url:"edit_category.php",
        type:"post",
        data:{edit_id4:edit_id4},
        success:function(data){
          $("#info_update4").html(data);
          $("#editData4").modal('show');
        }
      });
    });
  });
</script>
<script type="text/javascript">
  // Event handler for editing category details
  $(document).ready(function(){
    $(document).on('click','.edit_data5',function(){
      var edit_id5=$(this).attr('id');
      $.ajax({
        url:"view_category.php",
        type:"post",
        data:{edit_id5:edit_id5},
        success:function(data){
          $("#info_update5").html(data);
          $("#editData5").modal('show');
        }
      });
    });
  });
</script>
<script type="text/javascript">
   // DataTable initialization for category listing
   $('#dataTableHover').DataTable();

// Tooltips initialization
$('[data-toggle="tooltip"]').tooltip();
</script>
</body>
</html>
