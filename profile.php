<?php
include('../includes/confirmlogin1.php');
check_login();
if(isset($_POST['submit']))
{
  $userid=$_SESSION['userid'];
  $CustId=$_POST['custid'];
  $fName=$_POST['firstname'];
  $lName=$_POST['lastname'];
  $mobno=$_POST['telephone'];
  $email=$_POST['email'];
  $dob=$_POST['dob'];
  $add=$_POST['add'];
  $gender=$_POST['gender'];

  $sql="update customer set CustomerId=:custid,FirstName=:firstname,LastName=:lastname,Telephone=:telephone,Email=:email,Gender=:gender, DOB=:dob,Address=:add where id=:aid";
  $query = $pdo->prepare($sql);
  $query->bindParam(':custid',$CustId,PDO::PARAM_STR);
  $query->bindParam(':firstname',$fName,PDO::PARAM_STR);
  $query->bindParam(':lastname',$lName,PDO::PARAM_STR);
  $query->bindParam(':email',$email,PDO::PARAM_STR);
  $query->bindParam(':telephone',$mobno,PDO::PARAM_STR);
  $query->bindParam(':add',$add,PDO::PARAM_STR);
  $query->bindParam(':gender',$gender,PDO::PARAM_STR);
  $query->bindParam(':dob',$dob,PDO::PARAM_STR);
  $query->bindParam(':aid',$userid,PDO::PARAM_STR);
  $query->execute();
  echo '<script>alert("Profile has been updated")</script>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Customer Profile</title>
  <link rel="stylesheet" href="../chattels/css/style.css">

  <link rel="icon" type="image/x-icon" href="../chattels/images/farmimages/favicon1.png"> 
  </head>
<body>
    <div class="container-scroller">
        
        <?php include("../includes/custheader.php");?>
        
        <div class="container-fluid page-body-wrapper">
            
            
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                    $userid=$_SESSION['userid'];
                                    $sql="SELECT * from  customer where id=:aid";
                                    $query = $pdo -> prepare($sql);
                                    $query->bindParam(':aid',$userid,PDO::PARAM_STR);
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
                                                    <label class="col-12" for="register1-username">Role:</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" name="role" value="<?php  echo $row->role;?>" readonly="true">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-12" for="register1-email">Customer Id:</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" name="custid" value="<?php  echo $row->CustomerId;?>" required='true' >
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
                                                    <label class="col-12" for="register1-email">Gender:</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" name="gender" value="<?php  echo $row->Gender;?>" required='true' >
                                                    </div>
                                                </div>
                                           <div class="form-group row">
                                                    <label class="col-12" for="register1-email">Date of Birth:</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" name="dob" value="<?php  echo $row->DOB;?>" required='true' >
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
                                                    <label class="col-12" for="register1-email">Address:</label>
                                                    <div class="col-12">
                                                        <input type="text" class="form-control" name="add" value="<?php  echo $row->Address;?>" required='true' >
                                                    </div>
                                                </div>
                                 <div class="form-group row">
                                  <label class="col-12" for="register1-password">Registration Date:</label>
                                  <div class="col-12">
                                   <input type="text" class="form-control" id="" name="" value="<?php  echo $row->CustomerRegdate;?>" readonly="true">
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
                          <a href="update_image.php?id=<?php echo $userid;?>">Change Image</a>
                      </div>
                  </div>       
                  <?php 
              }
          } ?>
          <br>
          <button type="submit" name="submit" class="btn btn-primary btn-fw mr-2 custom-button">Update</button>
      </form>
  </div>
</div>
</div>
</div>
</div>

</div>

</div>

</div>
<style type="text/css">
   /* Basic button styling */
.btn {
    display: inline-block;
    font-weight: 400;
    text-align: center;
    vertical-align: middle;
    user-select: none;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

/* Primary button styling */
.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    color: #fff;
    background-color: #0056b3;
    border-color: #004085;
}

.btn-primary:focus, .btn-primary.focus {
    box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5);
}

/* Full-width button */
.btn-fw {
    width: 100px;
}

/* Margin-right spacing */
.mr-2 {
    margin-right: 0.5rem;
}

/* Custom button-specific styles */
.custom-button {
    float: left;
    padding: 0.5rem 1.5rem; /* Larger padding for a more prominent button */
    font-size: 1.125rem; /* Slightly larger text */
    border-radius: 0.3rem; /* Slightly rounded corners */
}

/* Adjustments for small screens */
@media (max-width: 576px) {
    .custom-button {
        width: 100%; /* Full-width on small screens */
        float: none; /* Remove float on small screens */
        margin-bottom: 1rem; /* Add margin below for spacing */
    }
}
</script> 

</body>
</html>