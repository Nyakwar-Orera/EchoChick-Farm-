<?php
session_start();
error_reporting(1);
include('includes/dbconnect.php');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $login_error = '';

    // First, check the customer table
    $sql = "SELECT * FROM customer WHERE Email=:username";
    $query = $pdo->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetch(PDO::FETCH_ASSOC);

    if ($query->rowCount() > 0) {
        // User found in the customer table
        if (password_verify($password, $results['Password'])) {
            // Valid password
            $_SESSION['userid'] = $results['id'];
            $_SESSION['role'] = $results['role'];
            // Redirect based on role
            if ($results['role'] == 'Customer') {
                header('Location: Cust/customerdashboard.php');
                exit;
            }
        } else {
            $login_error = 'Invalid password.';
        }
    } else {
        // Check the admin table
        $sql = "SELECT * FROM admin WHERE Email=:username";
        $query = $pdo->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetch(PDO::FETCH_ASSOC);

        if ($query->rowCount() > 0) {
            // User found in the admin table
            if (password_verify($password, $results['Password'])) {
                // Valid password
                $_SESSION['adminid'] = $results['ID'];
                $_SESSION['role'] = $results['role'];
                // Redirect to admin dashboard
                header('Location: Admin/admindashboard.php');
                exit;
            } else {
                $login_error = 'Invalid password.';
            }
        } else {
            $login_error = 'Invalid email.';
        }
    }

    // If execution reaches here, login failed
    echo "<script>alert('$login_error');</script>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page - Echo Chick Farm</title>
    <link rel="icon" type="image/x-icon" href="chattels/images/farmimages/favicon1.png">
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4; }
        .auth-form-light { background-color: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); width: 300px; margin: auto; }
        .form-control { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; }
        .btn { padding: 10px; border: none; border-radius: 5px; color: white; cursor: pointer; }
        .btn-login { background-color: #007bff; } /* Different color for login */
        .btn-register { background-color: #d62108; } /* Red color for register */
        .text-center { text-align: center; margin-top: 20px; }
        .img-avatar { border-radius: 50%; }
        a, .btn-link { color: #007bff; text-decoration: none; }
        a:hover, .btn-link:hover { text-decoration: underline; }
        .btn-link { background-color: transparent; color: #d62108; padding-left: 0; border: none; cursor: pointer; }
        .company-name { font-size: 24px; color: #333; text-align: center; margin-bottom: 20px; }
        .btn-group { display: flex; justify-content: space-between; }
    </style>
</head>
<body>
    <div id="page">
        <div class="container-scroller">
            <div class="container-fluid page-body-wrapper full-page-wrapper">
                <div class="content-wrapper d-flex align-items-center auth p-0">
                    <div class="row flex-grow p-0">
                        <div class="col-lg-4 mx-auto p-0">
                            <div class="auth-form-light text-left p-5" style="margin-top: 100px;">
                                <div align="center" class="company-name">Echo Chick Farm</div>
                                <div align="center">
                                    <img style="height: 100px;" class="img-avatar mb-3" src="chattels/images/farmimages/logo.jpg" alt="Echo Chick Farm Logo">
                                </div>
                                <form role="form" method="post" enctype="multipart/form-data">  
                                    <div class="form-group first">
                                        <label>Username</label>
                                        <input type="text" class="form-control form-control-lg" name="username" placeholder="Username" required>
                                    </div>
                                    <div class="form-group last mb-4">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
                                    </div>
                                    <centre>
                                    <div class="btn-group">
                                        <button name="login" class="btn btn-login">SIGN IN</button>
                                        <button type="button" class="btn btn-register" onclick="window.location.href='register.php';">Register</button>
                                    </div>

                                    <div class="text-center mt-4 font-weight-light"> 
                                        <a href="password_reset_request.php">Forgot Password?</a>
                                    </div>
</centre>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function onReady(callback) {
            var intervalID = window.setInterval(checkReady, 1000);
            function checkReady() {
                if (document.getElementsByTagName('body')[0] !== undefined) {
                    window.clearInterval(intervalID);
                    callback.call(this);
                }
            }
        }

        function show(id, value) {
            document.getElementById(id).style.display = value ? 'block' : 'none';
        }

        onReady(function () {
            show('page', true);
            show('loading', false);
        });
    </script>
</body>
</html>

