<?php
include('../includes/confirmlogin.php');
check_login();
// Handle delete operation
if (isset($_GET['del'])) {
    $customerId = $_GET['del'];
    $sql = "DELETE FROM customer WHERE id = :id";
    $query = $pdo->prepare($sql);
    $query->bindParam(':id', $customerId, PDO::PARAM_INT);
    try {
        $query->execute();
        echo "<script>alert('Customer record deleted successfully.');</script>";   
        echo "<script>window.location.href='manage_customers.php'</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}

// Fetch all customer records
$sql = "SELECT * FROM customer ORDER BY id DESC";
$query = $pdo->prepare($sql);
$query->execute();
$customers = $query->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers</title>
    <?php include("../includes/head.php");?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
</head>
<body>
<header>
    <?php include("../includes/adminheader.php");?>
</header>
<div class="container">
    <h2>Manage Customers</h2>
    <table id="customersTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Customer ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer): ?>
            <tr>
                <td><?= $customer->CustomerId; ?></td>
                <td><?= $customer->FirstName; ?></td>
                <td><?= $customer->LastName; ?></td>
                <td><?= $customer->Email; ?></td>
                <td class="text-center p-0">
    <a href="edit_customer.php?id=<?= $customer->id; ?>" class="edit_data4 rounded-circle btn btn-info" id="<?= $customer->id; ?>" title="click to edit">
        <i class="mdi mdi-pencil-box-outline" aria-hidden="true"></i>
    </a>
    <a href="view_customer.php?id=<?= $customer->id; ?>" class="edit_data5 rounded-circle btn btn-secondary" id="<?= $customer->id; ?>" title="click to view">
        <i class="mdi mdi-eye" aria-hidden="true"></i>
    </a>
    <a href="manage_customers.php?del=<?= $customer->id; ?>" data-toggle="tooltip" data-original-title="Delete" class="rounded-circle btn btn-danger" onclick="return confirm('Are you sure you want to delete this customer?');">
        <i class="mdi mdi-delete"></i>
    </a>
</td>

                
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    $('#customersTable').DataTable();
});
</script>

</body>
</html>
