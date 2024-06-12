<?php
include('../includes/confirmlogin.php');
check_login();
if(isset($_POST['submit']))
{
  $adminid=$_SESSION['adminid'];
  $AName=$_POST['username'];
  $fName=$_POST['firstname'];
  $lName=$_POST['lastname'];
  $mobno=$_POST['telephone'];
  $email=$_POST['email'];

  $sql="update admin set UserName=:adminname,FirstName=:firstname,LastName=:lastname,Telephone=:telephone,Email=:email where ID=:aid";
  $query = $pdo->prepare($sql);
  $query->bindParam(':adminname',$AName,PDO::PARAM_STR);
  $query->bindParam(':firstname',$fName,PDO::PARAM_STR);
  $query->bindParam(':lastname',$lName,PDO::PARAM_STR);
  $query->bindParam(':email',$email,PDO::PARAM_STR);
  $query->bindParam(':telephone',$mobno,PDO::PARAM_STR);
  $query->bindParam(':aid',$adminid,PDO::PARAM_STR);
  $query->execute();
  echo '<script>alert("Profile has been updated")</script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ECHOCHICK-FARM | Admin Profile</title>
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
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                    $adminid=$_SESSION['adminid'];
                                    $sql="SELECT * from  admin where ID=:aid";
                                    $query = $pdo -> prepare($sql);
                                    $query->bindParam(':aid',$adminid,PDO::PARAM_STR);
                                    $query->execute();
                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt=1;
                                    if($query->rowCount() > 0)
                                    {
                                        foreach($results as $row)
                                        {  
                                            ?>
                                            <form method="post">
                                                <div class="form-group row">
                                                    <label class="col-12" for="register1-username">Permision:</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" name="role" value="<?php  echo $row->role;?>" readonly="true">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="register1-email">User Name:</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" name="username" value="<?php  echo $row->UserName;?>" required='true' >
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="register1-email">First Name:
                                                    </label>
                                                    <div class="col-12">
                                                     <input type="text" class="form-control" name="firstname" value="<?php  echo $row->FirstName;?>" required='true' >
                                                 </div>
                                             </div>
                                             <div class="form-group row">
                                                <label class="col-12" for="register1-email">Last Name:</label>
                                                <div class="col-12">
                                                   <input type="text" class="form-control" name="lastname" value="<?php  echo $row->LastName;?>" required='true' >
                                               </div>
                                           </div>
                                           <div class="form-group row">
                                            <label class="col-12" for="register1-password">Email:</label>
                                            <div class="col-12">
                                              <input type="email" class="form-control" name="email" value="<?php  echo $row->Email;?>" required='true' >
                                          </div>
                                      </div>
                                      <div class="form-group row">
                                        <label class="col-12" for="register1-password">Contact Number:</label>
                                        <div class="col-12">
                                         <input type="text" class="form-control" name="telephone" value="0<?php  echo $row->Telephone;?>" required='true' maxlength='10'>
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                  <label class="col-12" for="register1-password">Registration Date:</label>
                                  <div class="col-12">
                                   <input type="text" class="form-control" id="" name="" value="<?php  echo $row->AdminRegdate;?>" readonly="true">
                               </div>
                           </div>
                           <div class="control-group">
                            <label class="control-label" for="basicinput">Profile Image</label>
                            <div class="controls">
                              <?php if($row->Photo=="avatar.jpg"){ ?>
                               <img class="" src="../chattels/images/profiles/custimages/avatar.jpg" alt="" width="100" height="100">
                               <?php 
                           } else { ?>
                              <img src="../chattels/images/profiles/<?php  echo $row->Photo;?>" width="150" height="auto">
                              <?php 
                          } ?>  
                          <a href="update_image.php?id=<?php echo $adminid;?>">Change Image</a>
                      </div>
                  </div>       
                  <?php 
              }
          } ?>
          <br>
          <button type="submit" name="submit" class="btn btn-primary btn-fw mr-2" style="float: left;">update</button>
      </form>
  </div>
</div>
</div>
</div>
</div>

</div>

</div>
</div>
</body>
</html>