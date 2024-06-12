<?php
include('../includes/dbconnect.php');
include('../includes/confirmlogin.php');
check_login();

// Fetch customer details
$id = $_GET['id'];
$sql = "SELECT * FROM customer WHERE id = :id";
$query = $pdo->prepare($sql);
$query->bindParam(':id', $id, PDO::PARAM_INT);
$query->execute();
$customer = $query->fetch(PDO::FETCH_OBJ);

if (!$customer) {
    echo "<script>alert('Customer not found.');window.location.href='manage_customers.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Customers</title>
    <?php include("../includes/head.php");?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <style>
        .container {max-width: 800px;margin: 50px auto;padding: 20px;background-color: #ffffff;box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);border-radius: 8px;}
        h2 {text-align: left;color: #333333;margin-bottom: 20px;}
        .form-row {display: flex;flex-wrap: wrap;justify-content: space-between;margin-bottom: 10px;}
        .form-section {margin-bottom: 15px;}
        .form-section label {display: block;margin-bottom: 5px;font-weight: bold;color: #333333;}.form-section input[type="text"],
        .form-section input[type="email"],.form-section input[type="tel"],.form-section input[type="date"],.form-section input[type="file"],.form-section select {width: 100%;padding: 10px;border: 1px solid #dddddd;border-radius: 4px;box-sizing: border-box;}
        .form-section input[type="text"]:focus,
        .form-section input[type="email"]:focus,.form-section input[type="tel"]:focus,
        .form-section input[type="date"]:focus,.form-section input[type="file"]:focus,.form-section select:focus {border-color: #007bff;outline: none;box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);}
        .btn-group {display: flex;justify-content: space-between;align-items: center;margin-top: 20px;}
        .btn {padding: 10px 20px;border: none;border-radius: 4px;cursor: pointer;font-size: 16px;}
        .btn-danger {background-color: #dc3545;color: #ffffff;}
        .btn-danger:hover {background-color: #c82333;}.btn-secondary {background-color: #6c757d;color: #ffffff;}.btn-secondary:hover {background-color: #5a6268;}
    </style>
</head>
<body>
<header>
    <?php include("../includes/adminheader.php");?>
</header>
    <div class="container">
        <h2>View Customer</h2>
        <div class="form-section">
            <label for="FirstName">First Name:</label>
            <p><?= $customer->FirstName; ?></p>
        </div>
        <div class="form-section">
            <label for="LastName">Last Name:</label>
            <p><?= $customer->LastName; ?></p>
        </div>
        <div class="form-section">
            <label for="Telephone">Telephone:</label>
            <p><?= $customer->Telephone; ?></p>
        </div>
        <div class="form-section">
            <label for="Email">Email:</label>
            <p><?= $customer->Email; ?></p>
        </div>
        <div class="form-section">
            <label for="Gender">Gender:</label>
            <p><?= $customer->Gender; ?></p>
        </div>
        <div class="form-section">
            <label for="DOB">Date of Birth:</label>
            <p><?= $customer->DOB; ?></p>
        </div>
        <div class="form-section">
            <label for="Address">Address:</label>
            <p><?= $customer->Address; ?></p>
        </div>
        <div class="form-section">
            <label for="Security1">Security Question 1:</label>
            <p><?= $customer->Security1; ?></p>
        </div>
        <div class="form-section">
            <label for="Answer1">Answer 1:</label>
            <p><?= $customer->Answer1; ?></p>
        </div>
        <div class="form-section">
            <label for="Security2">Security Question 2:</label>
            <p><?= $customer->Security2; ?></p>
        </div>
        <div class="form-section">
            <label for="Answer2">Answer 2:</label>
            <p><?= $customer->Answer2; ?></p>
        </div>
        <div class="form-section">
            <label for="Photo">Photo:</label>
            <img src="../chattels/images/profiles/custimages/<?= $customer->Photo; ?>" alt="Customer Photo" width="150">
        </div>
        <div class="btn-group">
            <button type="button" class="btn btn-secondary" onclick="window.location.href='manage_users.php';">Back</button>
        </div>
    </div>
</body>
</html>
