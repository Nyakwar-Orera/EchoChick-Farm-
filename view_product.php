<?php
session_start();
error_reporting(1);
include('../includes/dbconnect.php');
?>
<div class="card-body">
  <?php
  $eid=$_POST['edit_id5'];
  $sql="SELECT products.id,products.CategoryName,products.ProductName,products.PostingDate,products.ProductPrice,products.ProductImage from products  where products.id=:eid";
  $query = $pdo -> prepare($sql);
  $query-> bindParam(':eid', $eid, PDO::PARAM_STR);
  $query->execute();
  $results=$query->fetchAll(PDO::FETCH_OBJ);
  if($query->rowCount() > 0)
  {
    foreach($results as $row)
      {?>

        <h4 style="color: blue">Product Information</h4>
        <table border="1" class="table table-bordered">
          <tr>
            <th>Product Name</th>
            <td>
              <img src="../chattels/images/products/<?php  echo $row->ProductImage;?>" class="mr-2" alt="image">
              <?php  echo $row->ProductName;?>
              </td>
          </tr>
          <tr>
            <th>Category Name</th>
            <td><?php  echo $row->CategoryName;?></td>
          </tr>
          <tr>
            <th>Product Price</th>
            <td><b>KES</b>&nbsp;<?php  echo $row->ProductPrice;?></td>
          </tr>
          <tr>
            <th>Posting Date</th>
            <td><?php  echo htmlentities(date("d-m-Y", strtotime($row->PostingDate)));?></td>
          </tr>
        </table> 
        <?php 
      }
    } ?>
  </div>