<?php
include('../includes/confirmlogin1.php');
check_login();
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <title>ECHOCHICK- FARM | Products</title>
    <link rel="stylesheet" href="../chattels/css/style.css">
    <link rel="icon" type="image/x-icon" href="../chattels/images/farmimages/favicon1.png">   
  </head>

<body>
  <div class="container-scroller">
    
    <?php include("../includes/custheader.php");?>
    
    <div class="container-fluid page-body-wrapper">
      

    <div class="modal-header">
                                    <h3 class="modal-title" style="float: left;">Products Available</h3>
                                </div>
  
              <div class="table-responsive p-3">
                <table class="table align-items-center table-flush table-hover table-bordered">
                  <thead>
                    <tr>
                      <th class="text-center">No</th>
                      <th>Product Name</th>
                      <th class="text-center"> Product Category</th>
                      <th class="text-center"> Unit Price</th>
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

<?php include("../includes/footer.php");?>

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