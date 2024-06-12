<?php
session_start();
error_reporting(1);
include('../includes/dbconnect.php');
include('../includes/confirmlogin.php');
check_login();

if (strlen($_SESSION['adminid']) == 0) {
  header('location:logout.php');
} else {
  if (isset($_POST['submit'])) {
    $farmname = $_SESSION['farmname']; // Assuming this is assigned somewhere in your session
    $filename = $_FILES['note']['name'];
    $destination = '../chattels/images/farmimages/' . $filename;
    $file = $_FILES['note']['tmp_name'];

    // move the uploaded (temporary) file to the specified destination
    if (move_uploaded_file($file, $destination)) {
      $sql = "UPDATE farm SET farmlogo=:filename WHERE developer='Patrick_Ogonyo'";
      $query = $pdo->prepare($sql);
      $query->bindParam(':filename', $filename, PDO::PARAM_STR);

      if ($query->execute()) {
        echo '<script>alert("Farm logo updated successfully")</script>';
      } else {
        echo '<script>alert("Update failed! Try again later")</script>';
      }
    }
  }
?>


<!DOCTYPE html>
<html lang="en">
<?php include("../includes/head.php");?>
<body>
  <div class="container-scroller">
    
    <?php include("../includes/adminheader.php");?>
    
    <div class="container-fluid page-body-wrapper">
      
      
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12">
              <div class="card">
               <div class="card-body">
                    <?php // echo htmlentities($_SESSION['msg']);?><?php // echo htmlentities($_SESSION['msg']="");?>
                    
                    <br/>
                    <form class="form-horizontal row-fluid" name="insertproduct" method="post" enctype="multipart/form-data">
                      <?php
                      $sql="SELECT * from  farm where developer='Patrick_Ogonyo'";
                      $query = $pdo -> prepare($sql);
                      $query->execute();
                      $results=$query->fetchAll(PDO::FETCH_OBJ);
                      $cnt=1;
                      if($query->rowCount() > 0)
                      {
                        foreach($results as $row)
                        {  
                          ?>
                          <div class="control-group">
                            <label class="control-label" for="basicinput">Farm Name</label>
                            <div  class="col-6">
                              <input type="text"   class="form-control" name="farmname"  readonly value="<?php  echo $row->farmname;?>" class="span6 tip" readonly>
                            </div>
                          </div>
                          <br>
                          <div class="control-group"> 
                            <label class="control-label" for="basicinput">Current logo</label>
                            <div class="controls">
                              <?php if($row->farmlogo=="avatar.jpg"){ ?>
                                <img class="" src="../chattels/images/farmimages/logo.jpg" alt="" width="100" height="100">
                              <?php } else { ?>
                                <img style="height: 100px; width: 100px;" src="../chattels/images/farmimages/<?php  echo $row->farmlogo;?>" width="180" height="130"> 
                              <?php } ?> 
                            </div>
                          </div>
                          <div class="form-group col-md-6">
                            <label>New logo</label>
                            <input type="file" name="note" id="productimage1" class="file-upload-default">
                            <div class="input-group col-xs-12">
                              <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Logo">
                              <span class="input-group-append">
                                <button class="file-upload-browse btn btn-gradient-primary" type="button">Upload</button>
                              </span>
                            </div>
                          </div>
                        <?php }} ?>
                        <br>
                        <div class="form-group row">
                          <div class="col-12">
                            <button type="submit" class="btn btn-gradient-primary "  name="submit">
                              <i class="fa fa-plus"></i> Update
                            </button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            
          </div>
          
        </div>
        
      </div>
      <?php include("../includes/foot.php");?>
    </body>
    </html>
    <?php }  ?>