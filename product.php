<?php
include('../includes/confirmlogin.php');
check_login();
if(isset($_POST['save']))
{
  $category=$_POST['category'];
  $product=$_POST['product'];
  $price=$_POST['price'];
  $image=$_FILES["productimage"]["name"];
  move_uploaded_file($_FILES["productimage"]["tmp_name"],"../chattels/images/products/".$_FILES["productimage"]["name"]);
  $sql="insert into products(CategoryName,ProductName,ProductPrice,ProductImage)values(:category,:product,:price,:image)";
  $query=$pdo->prepare($sql);
  $query->bindParam(':category',$category,PDO::PARAM_STR);
  $query->bindParam(':product',$product,PDO::PARAM_STR);
  $query->bindParam(':price',$price,PDO::PARAM_STR);
  $query->bindParam(':image',$image,PDO::PARAM_STR);
  $query->execute();
  $LastInsertId=$pdo->lastInsertId();
  if ($LastInsertId>0) 
  {
    echo '<script>alert("Registered successfully")</script>';
    echo "<script>window.location.href ='product.php'</script>";
  }
  else
  {
    echo '<script>alert("Something Went Wrong. Please try again")</script>';
  }
}
if (isset($_GET['del'])) {
  $cmpid = $_GET['del'];
  $sql = "DELETE FROM products WHERE id = :id";
  $query = $pdo->prepare($sql);
  $query->bindParam(':id', $cmpid, PDO::PARAM_INT);
  try {
      $query->execute();
      echo "<script>alert('Product record deleted.');</script>";   
      echo "<script>window.location.href='product.php'</script>";
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
    <title>ECHOCHICK- FARM | Products</title>
    <link rel="icon" type="image/x-icon" href="../chattels/images/farmimages/favicon1.png"> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  
    <?php include("../includes/head.php");?>
  </head>

<body>
  <header>
      <?php include("../includes/adminheader.php");?>
  </header>
  <div class="container-fluid page-body-wrapper">
      <div class="modal-header">
        <h3 class="modal-title" style="float: left;">Register Product</h3>
      <br>
    </div>
        
    <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card bg-gradient-info card-img-holder text-white" style="height: 350px; width: 40%">
              <div class="col-md-12 mt-4">
                <form class="forms-sample" method="post" enctype="multipart/form-data" class="form-horizontal">
                  <div class="row ">
                    <div class="form-group col-md-6 ">
                      <label for="exampleInputPassword1">Product Category</label>
                      <select  name="category"  class="form-control" required>
                        <option value="">Select Category</option>
                        <?php
                        $sql="SELECT * from  category";
                        $query = $pdo -> prepare($sql);
                        $query->execute();
                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                        if($query->rowCount() > 0)
                        {
                          foreach($results as $row)
                          {
                            ?> 
                            <option value="<?php  echo $row->CategoryName;?>"><?php  echo $row->CategoryName;?></option>
                            <?php 
                          }
                        } ?>
                      </select>
                    </div>
                    <div class="form-group col-md-6">
                      <label for="exampleInputName1">Product Name </label>
                      <input type="text" name="product" class="form-control" value="" id="product" placeholder="Enter Product" required>
                    </div>
                  </div>
                  <div class="row ">
                    <div class="form-group col-md-6">
                      <label for="exampleInputName1">Product Price</label>
                      <input type="text" name="price" value="" placeholder="Enter Price" class="form-control" id="price"required>
                    </div>
                    <div class="form-group col-md-6 ">
                      <label class="col-sm-12 pl-0 pr-0 ">Attach Product Photo</label>
                      <div class="col-sm-12 pl-0 pr-0">
                        <input type="file" name="productimage" class="file-upload-default">
                        </div>
                      </div>
                    </div> 
                  </div>
                  <button type="submit" style="float: left;" name="save" class="btn btn-primary  mr-2 mb-4">Save</button>
                </form>
              </div>
            </div>
          </div>

          <div class="col-lg-12 grid-margin stretch-card">
              <div id="editData4" class="modal fade">
                <div class="modal-dialog modal-md">
                  <div class="modal-content">
                    <div class="modal-body" id="info_update4">
                      <?php include("edit_product.php");?>
                    </div>
                  </div> 
                </div>  
              </div>
              
              <div id="editData5" class="modal fade">
                <div class="modal-dialog modal-md">
                  <div class="modal-content">
                    <div class="modal-body" id="info_update5">
                      <?php include("view_product.php");?>
                    </div> 
                  </div> 
                </div> 
              </div>
              
              <div class="table-responsive p-3">
                <table class="table align-items-center table-flush table-hover table-bordered" id="dataTableHover">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>Product Name</th>
                      <th class="text-center"> Product Category</th>
                      <th class="text-center"> Product Unit Price</th>
                      <th class="text-center">Posting Date</th>
                      <th class=" Text-center" style="width: 15%;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql="SELECT products.id,products.CategoryName,products.ProductName,products.PostingDate,products.ProductPrice,products.ProductImage from products ORDER BY id DESC";
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
                          <td class="text-center"><?php echo htmlentities($cnt);?></td>
                          <td>
                            <img src="../chattels/images/products/<?php  echo $row->ProductImage;?>" class="mr-2" alt="image"><a href="#"class=" edit_data5" id="<?php echo  ($row->id); ?>" ><?php  echo htmlentities($row->ProductName);?></a>
                          </td>
                          <td class="text-center"><?php  echo htmlentities($row->CategoryName);?></td>
                          <td class="text-center"><?php  echo htmlentities($row->ProductPrice);?></td>
                          <td class="text-center"><?php  echo htmlentities(date("d-m-Y", strtotime($row->PostingDate)));?></td>
                          <td class=" text-center p-0"><a href="#" class=" edit_data4 rounded-circle btn btn-info " id="<?php echo  ($row->id); ?>" title="click to edit"><i class="mdi mdi-pencil-box-outline" aria-hidden="true"></i></a>
                            <a href="#"  class=" edit_data5 rounded-circle btn btn-secondary " id="<?php echo  ($row->id); ?>" title="click to view">&nbsp;<i class="mdi mdi-eye" aria-hidden="true"></i></a>
                            <a href="product.php?del=<?php echo ($row->id);?>" data-toggle="tooltip" data-original-title="Delete" class="rounded-circle btn btn-danger" onclick="return confirm('Do you really want to delete?');"> <i class="mdi mdi-delete"></i> </a>
                          </td>
                        </tr>
                        <?php 
                        $cnt=$cnt+1;
                      }
                    } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      
    </div>
    
  </div>
  
</div>

<?php include("../includes/foot.php");?>

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
  $(document).ready(function(){
    $(document).on('click','.edit_data4',function(){
      var edit_id4=$(this).attr('id');
      $.ajax({
        url:"edit_product.php",
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
  $(document).ready(function(){
    $(document).on('click','.edit_data5',function(){
      var edit_id5=$(this).attr('id');
      $.ajax({
        url:"view_product.php",
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

<script>
$(document).ready(function() {
    $('#customersTable').DataTable();
});
</script>
</body>
</html>