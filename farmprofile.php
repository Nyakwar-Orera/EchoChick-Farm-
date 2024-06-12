<?php
// Include the login confirmation script
include('../includes/confirmlogin.php');

// Check if the user is logged in
check_login();

// Check if the form has been submitted
if(isset($_POST['submit'])) {
    // Retrieve form data
    $farmemail = $_POST['farmemail'];
    $farmname = $_POST['farmname'];
    $farmaddress = $_POST['farmaddress'];
    $regno = $_POST['regno'];
    $country = $_POST['country'];
    $farmtelephone = $_POST['farmtelephone'];

    // SQL query to update farm details
    $sql = "update farm set farmaddress=:farmaddress,farmname=:farmname,farmemail=:farmemail,regno=:regno,farmtelephone=:farmtelephone,country=:country";
    $query = $pdo->prepare($sql);
    $query->bindParam(':farmaddress', $farmaddress, PDO::PARAM_STR);
    $query->bindParam(':farmemail', $farmemail, PDO::PARAM_STR);
    $query->bindParam(':regno', $regno, PDO::PARAM_STR);
    $query->bindParam(':country', $country, PDO::PARAM_STR);
    $query->bindParam(':farmtelephone', $farmtelephone, PDO::PARAM_STR);
    $query->bindParam(':farmname', $farmname, PDO::PARAM_STR);
    $query->execute();  // Execute the query

    // Check if the update was successful
    if ($query->execute()) {
        echo '<script>alert("Profile has been updated")</script>';
    } else {
        echo '<script>alert("Update failed! Try again later")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ECHOCHICK-FARM | FarmProfile</title>
    <link rel="icon" type="image/x-icon" href="../chattels/images/farmimages/favicon1.png"> 

    <!-- Include external head components -->
    <?php include("../includes/head.php");?>
</head>
<body>
    <header>
        <!-- Include admin header -->
        <?php include("../includes/adminheader.php");?>
    </header>

    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper">
            <div class="modal-header">
                <h3 class="modal-title" style="float: left;">Farm Details</h3>
            </div>
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                    // SQL query to fetch farm details
                                    $sql = "SELECT * from farm where developer='Patrick_Ogonyo'";
                                    $query = $pdo->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $row) {
                                            ?>
                                            <form method="post">
                                                <!-- Display farm logo -->
                                                <div class="control-group">
                                                    <label class="control-label" for="basicinput">Logo</label>
                                                    <div class="controls">
                                                        <?php
                                                        if ($row->companylogo == "logo.jpg") {
                                                            ?>
                                                            <img class="" src="../chattels/farmimages/logo.jpg" alt="" width="100" height="100">
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <img style="height: 100px; width: auto;" src="../chattels/images/farmimages/<?php echo $row->farmlogo;?>" width="150" height="100">
                                                            <?php
                                                        }
                                                        if ($_SESSION['role'] == "Admin") {
                                                            ?>  
                                                            <a href="update_logo.php?id=<?php echo $farmlogo;?>">Change logo</a>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <!-- Display farm name and registration number -->
                                                <div class="row">
                                                    <div class="form-group row col-md-6">
                                                        <label class="col-12" for="register1-username">Farm Name:</label>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control" name="name" value="<?php echo $row->farmname;?>" >
                                                        </div>
                                                    </div>
                                                    <div class="form-group row col-md-6">
                                                        <label class="col-12" for="register1-email">Farm reg no.:</label>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control" name="regno" value="<?php echo $row->regno;?>" required='true'>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Display farm address, email, and other details -->
                                                <div class="row">
                                                    <div class="form-group row col-md-6">
                                                        <label class="col-12" for="register1-email">Physical Address:</label>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control" name="farmaddress" value="<?php echo $row->farmaddress;?>" required='true'>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row col-md-6">
                                                        <label class="col-12" for="register1-email">Farm Email:</label>
                                                        <div class="col-12">
                                                            <input type of="text" class="form-control" name="farmemail" value="<?php echo $row->farmemail;?>" required='true'>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row"> 
                                                    <div class="form-group row col-md-6">
                                                        <label class="col-12" for="register1-password">Country:</label>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control" name="country" value="<?php echo $row->country;?>" required='true'>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row col-md-6">
                                                        <label class="col-12" for="register1-password">Telephone Number:</label>
                                                        <div class="col-12">
                                                            <input type="text" class="form-control" name="farmtelephone" value="<?php echo $row->farmtelephone;?>" required='true' placeholder="Enter company contact no" maxlength='13'>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Submit button for the form -->
                                                <button type="submit" name="submit" class="btn btn-primary btn-fw mr-2" style="float: left;">Update</button>
                                            </form>
                                            <?php 
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
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
</body>
</html>
