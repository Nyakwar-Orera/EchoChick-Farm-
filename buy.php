<?php
session_start();
error_reporting(1);
include('../includes/dbconnect.php');

// Start of Cart Code
if(!empty($_GET["action"])) {
    switch($_GET["action"]) {
      // Adding a product to the cart
      case "add":
        if(!empty($_POST["quantity"])) {
          $pid = intval($_GET["pid"]);  // Ensure that pid is an integer
          $sql = "SELECT * FROM products WHERE id = :pid";
          $stmt = $pdo->prepare($sql);
          $stmt->bindParam(':pid', $pid, PDO::PARAM_INT);
          $stmt->execute();
          $productByCode = $stmt->fetch(PDO::FETCH_ASSOC);
  
          if($productByCode) {
            $itemArray = array($productByCode["id"] => array(
              'catname' => $productByCode["CategoryName"], 
              'farmname' => $productByCode["farmname"], 
              'quantity' => $_POST["quantity"], 
              'pname' => $productByCode["ProductName"],
              'image' => $productByCode["ProductImage"], 
              'price' => $productByCode["ProductPrice"],
              'code' => $productByCode["id"]
            ));
  
            if (!empty($_SESSION["cart_item"])) {
                if (isset($_SESSION["cart_item"][$productByCode["id"]])) {
                  // Update existing item quantity
                  $_SESSION["cart_item"][$productByCode["id"]]["quantity"] += (int) $_POST["quantity"];
                } else {
                  // Add new item
                  $_SESSION["cart_item"][$productByCode["id"]] = $itemArray[$productByCode["id"]];
                }
              } else {
                // Initialize the cart with the first item
                $_SESSION["cart_item"] = $itemArray;
              }
              
        }
        }
        break;
  
      // Removing a product from the cart
      case "remove":
        if(!empty($_SESSION["cart_item"])) {
          foreach($_SESSION["cart_item"] as $k => $v) {
            if($_GET["code"] == $k)
              unset($_SESSION["cart_item"][$k]);              
            if(empty($_SESSION["cart_item"]))
              unset($_SESSION["cart_item"]);
          }
        }
        break;
        // code for if cart is empty
        case "empty":
        unset($_SESSION["cart_item"]);
        break;
    }
  }


// Code for Checkout
if (isset($_POST['checkout'])) {
    if (empty($_POST['customername']) || empty($_POST['mobileno'])) {
        echo '<script>alert("Please fill in all required fields.");</script>';
    } else {
        $invoiceno = mt_rand(100000000, 999999999);
        $cname = $_POST['customername'];
        $cmobileno = $_POST['mobileno'];
        $pmode = $_POST['paymentmode'];
        $cid = $_POST['custid'];

        // Extracting product IDs and quantities from the session cart
        $pid = [];
        $quantity = [];
        if (!empty($_SESSION["cart_item"])) {
            foreach ($_SESSION["cart_item"] as $item) {
                $pid[] = $item["code"];
                $quantity[] = $item["quantity"];
            }
        }

        if (empty($pid) || empty($quantity)) {
            echo '<script>alert("Cart is empty"); window.location.href="cart.php";</script>';
            exit;
        }

        $value = array_combine($pid, $quantity);

        try {
            // Prepare the SQL statement with placeholders
            $sql = "INSERT INTO orders (ProductId, Quantity, InvoiceNumber, CustomerName, CustomerContactNo, PaymentMode, CustomerId) 
                    VALUES (:pid, :qty, :invoice, :cname, :cmobile, :pmode, :cid)";
            $stmt = $pdo->prepare($sql);

            // Begin transaction
            $pdo->beginTransaction();

            // Execute the statement for each item
            foreach ($value as $pdid => $qty) {
                $stmt->execute([
                    ':pid' => $pdid,
                    ':qty' => $qty,
                    ':invoice' => $invoiceno,
                    ':cid' => $cid,
                    ':cname' => $cname,
                    ':cmobile' => $cmobileno,
                    ':pmode' => $pmode
                ]);
            }

            // Commit the transaction
            $pdo->commit();

            // JavaScript for alert and redirection
            echo '<script>alert("Invoice generated successfully. Invoice number is ' . $invoiceno . '");</script>';  
            unset($_SESSION["cart_item"]);
            $_SESSION['invoice'] = $invoiceno;
            echo "<script>window.location.href='invoice.php';</script>";

        } catch (PDOException $e) {
            // Rollback the transaction in case of an error
            $pdo->rollback();
            die('Error executing query: ' . $e->getMessage());
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Buy Products</title>
    <link rel="stylesheet" href="../chattels/css/buy.css">
    <link rel="icon" type="image/x-icon" href="../chattels/images/farmimages/favicon1.png">
</head>
<body>
    <div class="container-scroller">
        <?php include("../includes/custheader.php"); ?>
        <div class="container-fluid">
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="table-responsive p-3">
                                    <table id="datable_1" class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Product Name</th>
                                                <th>Product Category</th>
                                                <th>Pricing</th>
                                                <th>Quantity</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            try {
                                                $stmt = $pdo->prepare("SELECT * FROM products");
                                                $stmt->execute();
                                                $cnt = 1;
                                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            ?>
                                            <form method="post" action="buy.php?action=add&pid=<?php echo htmlspecialchars($row["id"]); ?>">
                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><img src="../chattels/images/products/<?php echo htmlspecialchars($row['ProductImage']); ?>" class="mr-2" alt="image"><?php echo htmlspecialchars($row['ProductName']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['CategoryName']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['ProductPrice']); ?></td>
                                                    <td><input type="text" class="product-quantity" name="quantity" value="1" size="2" /></td>
                                                    <td>
                                                    <input type="submit" value="Add to Cart" class="btn btn-secondary btn-add-to-cart" />

                                                    </td>
                                                </tr>
                                            </form>
                                            <?php
                                                $cnt++;
                                                }
                                            } catch (PDOException $e) {
                                                die("Could not connect to the database $dbname :" . $e->getMessage());
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="table-responsive p-3">
                                    <form class="needs-validation" method="post" novalidate>
                                        <h4>Shopping Cart</h4>
                                        <hr />
                                        <a id="btnEmpty" href="buy.php?action=empty">Empty Cart</a>
                                        <?php
                                        if(isset($_SESSION["cart_item"])) {
                                            $total_quantity = 0;
                                            $total_price = 0;
                                        ?>
                                        <table class="table align-items-center table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Category</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Price</th>
                                                    <th>Remove</th>
                                                </tr>
                                                <?php 
                                                $productid=array();      
                                                foreach ($_SESSION["cart_item"] as $item){
                                                    $item_price = $item["quantity"]*$item["price"];
                                                    array_push($productid,$item['code']);
                                                ?>
                                                <div class="item" data-code="<?php echo htmlspecialchars($item["code"]); ?>">
                                                    <tr>
                                                        <td><img src="../chattels/images/products/<?php echo htmlspecialchars($item["image"]); ?>" class="mr-2" alt="image"><?php echo htmlspecialchars($item["pname"]); ?></td>
                                                        <td><?php echo htmlspecialchars($item["catname"]); ?></td>
                                                        <td><?php echo htmlspecialchars($item["quantity"]); ?></td>
                                                        <td><?php echo htmlspecialchars($item["price"]); ?></td>
                                                        <td><?php echo number_format($item_price, 2); ?></td>
                                                        <td><a href="buy.php?action=remove&code=<?php echo htmlspecialchars($item["code"]); ?>" id="btnRemoveAction" data-code="<?php echo htmlspecialchars($item["code"]); ?>"> <i class="mdi mdi-close-circle" style="font-size: 20px;">Remove</i> </a></td>
                                                    </tr>
                                                </div>
                                                <?php
                                                    $total_quantity += $item["quantity"];
                                                    $total_price += ($item["price"]*$item["quantity"]);
                                                }
                                                $_SESSION['productid']=$productid;
                                                ?>
                                                <tr>
                                                    <td colspan="3" align="right">Total:</td>
                                                    <td colspan="2"><?php echo $total_quantity; ?></td>
                                                    <td colspan="2"><strong><?php echo number_format($total_price, 2); ?></strong></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table> 
                                        <!-- Customer Details Input -->
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
                                        <div class="form-row">
                                            <div class="col-md-6 mb-3">
                                                <label for="CustomerId">Customer Id</label>
                                                <input type="text" class="form-control" name="custid" value="<?php  echo $row->CustomerId;?>" required='true' >   
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="customerName">Customer Name</label>
                                                <input type="text" class="form-control" id="customerName" name="customername" required>
                                                <div class="invalid-feedback">Please provide a valid customer name.</div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="mobileNo">Contact Number</label>
                                                <input type="text" class="form-control" id="mobileNo" name="mobileno" required>
                                                <div class="invalid-feedback">Please provide a valid mobile number.</div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-md-6 mb-10">
                                                <label for="validationCustom03">Payment Mode</label>
                                                <div class="custom-control custom-radio mb-10">
                                                    <input type="radio" class="custom-control-input" id="customControlValidation2" name="paymentmode" value="cash" required>
                                                    <label class="custom-control-label" for="customControlValidation2">Cash</label>
                                                </div>
                                                <div class="custom-control custom-radio mb-10">
                                                    <input type="radio" class="custom-control-input" id="customControlValidation3" name="paymentmode" value="mpesa" required>
                                                    <label class="custom-control-label" for="customControlValidation3">Mpesa</label>
                                                </div>
                                                <div class="custom-control custom-radio mb-10">
                                                    <input type="radio" class="custom-control-input" id="customControlValidation3" name="paymentmode" value="card" required>
                                                    <label class="custom-control-label" for="customControlValidation3">Card</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-4 ">
                                                <button class="btn btn-primary mt-6" type="submit" name="checkout">Checkout</button>
                                            </div>
                                        </div>
                                    </form>
                                    <?php
                                        }
                                    ?>
                                    <?php 
                                        }}
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style type="text/css">
      /* General Table Styling */
.table {width: 100%;margin-bottom: 1rem;color: #212529;border-collapse: collapse;}
.table th,.table td {padding: 0.75rem;vertical-align: top;border-top: 1px solid #dee2e6;}

.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}

.table tbody + tbody {
    border-top: 2px solid #dee2e6;
}

.table-bordered {
    border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}

.table-bordered thead th,
.table-bordered thead td {
    border-bottom-width: 2px;
}

/* Specific Styling for Table */
.table th {
    background-color: #f8f9fa;
    text-align: center;
}

.table td {
    text-align: center;
}

/* Styling for Images within Table */
.table img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 5px;
    margin-right: 10px;
}

/* Styling for Form Elements in Table */
.table .product-quantity {
    width: 60px;
    text-align: center;
}

/* Button Styles */
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

.btn-secondary {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    color: #fff;
    background-color: #5a6268;
    border-color: #545b62;
}

.btn-add-to-cart {
    background-color: #28a745; /* Green background */
    border-color: #28a745; /* Green border */
    color: #fff; /* White text */
}

.btn-add-to-cart:hover {
    background-color: #218838; /* Darker green on hover */
    border-color: #1e7e34; /* Darker green border on hover */
}

.btn-add-to-cart {
    padding: 0.5rem 1rem;
    font-size: 1.125rem;
}

#btnEmpty {
    background-color: #ffffff;
    border: #d00000 1px solid;
    padding: 5px 10px;
    color: #d00000;
    float: right;
    text-decoration: none;
    border-radius: 30px;
    margin: 10px 10px;
}

#btnRemoveAction {
    background-color: white;
    border: #d00000 1px solid;
    padding: 5px 5px;
    color: green;
    text-decoration: none;
    border-radius: 30px;
    margin: 10px 10px;
}

/* Styling the form rows and columns */
.form-row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}

.form-row .col-md-6 {
    position: relative;
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
    flex: 0 0 50%;
    max-width: 50%;
}

.mb-3, .mb-10, .mb-4 {
    margin-bottom: 1rem;
}

.mb-10 {
    margin-bottom: 2.5rem;
}

/* Styling for form labels */
label {
    display: inline-block;
    margin-bottom: 0.5rem;
}

/* Styling for form controls (input fields) */
.form-control {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    color: #495057;
    background-color: #fff;
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* Styling for invalid feedback */
.invalid-feedback {
    display: none;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #dc3545;
}

/* Show invalid feedback when input is invalid */
.was-validated .form-control:invalid ~ .invalid-feedback,
.form-control.is-invalid ~ .invalid-feedback {
    display: block;
}

/* Custom radio buttons */
.custom-control {
    position: relative;
    display: block;
    min-height: 1.5rem;
    padding-left: 1.5rem;
}

.custom-control-input {
    position: absolute;
    z-index: -1;
    opacity: 0;
}

.custom-control-input:checked ~ .custom-control-label::before {color: #fff;border-color: #007bff;background-color: #007bff;}
.custom-control-label {position: relative;margin-bottom: 0;vertical-align: top;}
.custom-control-label::before {position: absolute;top: 0.25rem;left: -1.5rem;display: block;width: 1rem;height: 1rem;pointer-events: none;content: "";background-color: #fff;border: #adb5bd solid 1px;}
.custom-control-label::after {position: absolute;top: 0.25rem;left: -1.5rem;display: block;width: 1rem;height: 1rem;content: "";background: no-repeat 50% / 50% 50%;}
.custom-radio .custom-control-input:checked ~ .custom-control-label::after {background-image: url('data:image/svg+xml,%3csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"%3e%3ccircle cx="8" cy="8" r="3"%3e%3c/circle%3e%3c/svg%3e');}
/* Styling the submit button */
.btn-primary {color: #fff;background-color: #007bff;border-color: #007bff;}.btn-primary:hover {color: #fff;background-color: #0056b3;border-color: #004085;}.btn-primary:focus, .btn-primary.focus {box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5);}.mt-6 {margin-top: 1.5rem;}
</style>

<script type="text/javascript">
document.querySelectorAll('.btnRemoveAction').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const itemCode = this.getAttribute('data-code');
        if (confirm('Are you sure you want to remove this item?')) {
            fetch('buy.php?action=remove&code=' + itemCode)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Find the item element in the DOM
                        const itemElement = document.querySelector(`.item[data-code="${itemCode}"]`);
                        if (itemElement) {
                            // Remove the item element from the DOM
                            itemElement.remove();
                        }
                        console.log('Item removed');
                    } else {
                        console.error('Failed to remove item:', data.message);
                    }
                })
                .catch(error => console.error('Error removing item:', error));
        }
    });
});




// JavaScript for disabling form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
</body>
</html>
