<?php
include('../includes/confirmlogin.php');
check_login();

// Initialize variables
$id = $_GET['id'];
$firstName = $lastName = $telephone = $email = $gender = $dob = $address = $security1 = $answer1 = $security2 = $answer2 = $photo = "";

// Fetch customer details
$sql = "SELECT * FROM customer WHERE id = :id";
$query = $pdo->prepare($sql);
$query->bindParam(':id', $id, PDO::PARAM_INT);
$query->execute();
$customer = $query->fetch(PDO::FETCH_OBJ);

if (!$customer) {
    echo "<script>alert('Customer not found.');window.location.href='manage_customers.php';</script>";
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $telephone = $_POST['Telephone'];
    $email = $_POST['Email'];
    $gender = $_POST['Gender'];
    $dob = $_POST['DOB'];
    $address = $_POST['Address'];
    $security1 = $_POST['Security1'];
    $answer1 = $_POST['Answer1'];
    $security2 = $_POST['Security2'];
    $answer2 = $_POST['Answer2'];
    $photo = $customer->Photo; // Default photo to existing

    // Process photo upload if a file is provided
    if (!empty($_FILES['Photo']['name'])) {
        $target_dir = "chattels/images/profiles/custimages/";
        $target_file = $target_dir . basename($_FILES["Photo"]["name"]);
        if (move_uploaded_file($_FILES["Photo"]["tmp_name"], $target_file)) {
            $photo = basename($_FILES["Photo"]["name"]);
        }
    }

    // Update customer details
    $sql = "UPDATE customer SET FirstName = :FirstName, LastName = :LastName, Telephone = :Telephone, Email = :Email, Photo = :Photo, Gender = :Gender, DOB = :DOB, Address = :Address, Security1 = :Security1, Answer1 = :Answer1, Security2 = :Security2, Answer2 = :Answer2 WHERE id = :id";
    $query = $pdo->prepare($sql);

    // Bind parameters
    $query->bindParam(':FirstName', $firstName);
    $query->bindParam(':LastName', $lastName);
    $query->bindParam(':Telephone', $telephone);
    $query->bindParam(':Email', $email);
    $query->bindParam(':Photo', $photo);
    $query->bindParam(':Gender', $gender);
    $query->bindParam(':DOB', $dob);
    $query->bindParam(':Address', $address);
    $query->bindParam(':Security1', $security1);
    $query->bindParam(':Answer1', $answer1);
    $query->bindParam(':Security2', $security2);
    $query->bindParam(':Answer2', $answer2);
    $query->bindParam(':id', $id);

    // Execute the statement
    try {
        $query->execute();
        echo "<script>alert('Customer details updated successfully.');window.location.href='manage_users.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customers</title>
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
        <h2>Edit Customer</h2>
        <form action="edit_customer.php?id=<?= $customer->id; ?>" method="post" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-section">
                <label for="FirstName">First Name:</label>
                <input type="text" class="form-control" name="FirstName" value="<?= $customer->FirstName; ?>" required>
            </div>
            <div class="form-section">
                <label for="LastName">Last Name:</label>
                <input type="text" class="form-control" name="LastName" value="<?= $customer->LastName; ?>" required>
            </div>
            <div class="form-section">
                <label for="Telephone">Telephone:</label>
                <input type="tel" class="form-control" name="Telephone" value="<?= $customer->Telephone; ?>" required>
            </div>
            <div class="form-section">
                <label for="Email">Email:</label>
                <input type="email" class="form-control" name="Email" value="<?= $customer->Email; ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-section">
                <label for="Gender">Gender:</label>
                <select name="Gender" class="form-control" required>
                    <option value="Male" <?= $customer->Gender == 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?= $customer->Gender == 'Female' ? 'selected' : ''; ?>>Female</option>
                    <option value="Other" <?= $customer->Gender == 'Other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>
            <div class="form-section">
                <label for="DOB">Date of Birth:</label>
                <input type="date" class="form-control" name="DOB" value="<?= $customer->DOB; ?>" required>
            </div>
            <div class="form-section">
                <label for="Address">Address:</label>
                <input type="text" class="form-control" name="Address" value="<?= $customer->Address; ?>" required>
            </div>
           </div>
            <div class="form-row">
            <div class="form-section">
                <label for="Security1">Security Question 1:</label>
                <input type="text" class="form-control" name="Security1" value="<?= $customer->Security1; ?>" required>
            </div>
            <div class="form-section">
                <label for="Answer1">Answer 1:</label>
                <input type="text" class="form-control" name="Answer1" value="<?= $customer->Answer1; ?>" required>
            </div>
            <div class="form-section">
                <label for="Security2">Security Question 2:</label>
                <input type="text" class="form-control" name="Security2" value="<?= $customer->Security2; ?>" required>
            </div>
            <div class="form-section">
                <label for="Answer2">Answer 2:</label>
                <input type="text" class="form-control" name="Answer2" value="<?= $customer->Answer2; ?>" required>
            </div>
            </div>
            <div class="form-row">
            <div class="form-section">
                <label for="Photo">Photo (optional):</label>
                <input type="file" class="form-control" name="Photo">
            </div>
            <div class="btn-group">
                <input type="submit" class="btn btn-danger" value="Update">
                <button type="button" class="btn btn-secondary" onclick="window.location.href='manage_users.php';">Cancel</button>
            </div>
        </div>
        </form>
    </div>
</body>
</html>
