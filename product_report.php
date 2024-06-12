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
if(isset($_GET['del'])){    
  $cmpid=$_GET['del'];
  $query=mysqli_query($con,"delete from products where id='$cmpid'");
  echo "<script>alert('Product record deleted.');</script>";   
  echo "<script>window.location.href='product.php'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ECHOCHICK-FARM | Product Reports</title>
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
              <div id="editData4" class="modal fade">
                <div class="modal-dialog modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                    </div>
                    <div class="modal-body" id="info_update4">
                      <?php include("edit_product.php");?>
                    </div> 
                  </div>
                  
                </div>
                
              </div>
              
              
              <div id="editData5" class="modal fade">
                <div class="modal-dialog modal-md">
                  <div class="modal-content">
                    <div class="modal-header">
                    </div>
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
                      <th class="text-center"> Product Price</th>
                      <th class="text-center">Posting Date</th>
                      
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

</body>
</html>