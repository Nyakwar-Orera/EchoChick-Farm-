
<?php
session_start();
include('../includes/dbconnect.php');

// Check if admin is logged in
if (strlen($_SESSION['adminid']) == 0) {
    header('location:logout.php');
    exit; // Stop the script after redirect
}

if (isset($_POST['submit'])) {
    $adminid = $_SESSION['odmsaid'];
    $cpassword = $_POST['currentpassword']; // Keep as plain text for password_verify
    $newpassword = $_POST['newpassword']; // New password in plain text for password_hash

    // Prepare SQL to fetch the current hashed password from the database
    $sql = "SELECT Password FROM admin WHERE ID=:adminid";
    $query = $pdo->prepare($sql);
    $query->bindParam(':adminid', $adminid, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($query->rowCount() > 0) {
        // Verify the current password with the hash stored in the database
        if (password_verify($cpassword, $result['Password'])) {
            // Hash the new password
            $newpasswordHash = password_hash($newpassword, PASSWORD_DEFAULT);

            // Update the database with the new hashed password
            $con = "UPDATE admin SET Password=:newpassword WHERE ID=:adminid";
            $chngpwd1 = $pdo->prepare($con);
            $chngpwd1->bindParam(':adminid', $adminid, PDO::PARAM_STR);
            $chngpwd1->bindParam(':newpassword', $newpasswordHash, PDO::PARAM_STR);
            $chngpwd1->execute();

            echo '<script>alert("Your password successfully changed");</script>';
        } else {
            echo '<script>alert("Your current password is wrong");</script>';
        }
    } else {
        // Handle case where the admin ID does not exist or other errors
        echo '<script>alert("An unexpected error occurred. Please try again.");</script>';
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<script type="text/javascript">
function checkpass()
{
if(document.changepassword.newpassword.value!=document.changepassword.confirmpassword.value)
{
alert('New Password and Confirm Password field does not match');
document.changepassword.confirmpassword.focus();
return false;
}
return true;
}   

</script>
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
                      <form method="post" onsubmit="return checkpass();" name="changepassword">
                          <div class="form-group row">
                              <label class="col-12" for="register1-username">Current Password:</label>
                              <div class="col-12">
                                  <input type="password" class="form-control" name="currentpassword" id="currentpassword"required='true'>
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-12" for="register1-email">New Password:</label>
                              <div class="col-12">
                                   <input type="password" class="form-control" name="newpassword"  class="form-control"  required="true">
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-12" for="register1-password">Confirm Password:</label>
                              <div class="col-12">
                                  <input type="password" class="form-control"  name="confirmpassword" id="confirmpassword" required='true'>
                              </div>
                          </div>
                        
                          <div class="form-group row">
                              <div class="col-12">
                                  <button type="submit" class="btn btn-primary" name="submit">
                                      <i class="fa fa-plus "></i> Change
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
  </body>
</html>