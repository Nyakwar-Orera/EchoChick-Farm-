<?php
include("../includes/confirmlogin.php");
// Include your database connection

// Initialize variables for each field
$firstName = $lastName = $telephone = $email = $gender = $customerId = $password = $dob = $address = $security1 = $answer1 = $security2 = $answer2 = "";
$photo = "avatar.jpg"; // Default photo

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customerId = $_POST['CustomerId'];
    $firstName = $_POST['FirstName'];
    $lastName = $_POST['LastName'];
    $telephone = $_POST['Telephone'];
    $email = $_POST['Email'];
    $gender = $_POST['Gender'];
    $password = password_hash($_POST['Password'], PASSWORD_DEFAULT); // Hash the password for security
    $dob = $_POST['DOB'];
    $address = $_POST['Address'];
    $security1 = $_POST['Security1'];
    $answer1 = $_POST['Answer1'];
    $security2 = $_POST['Security2'];
    $answer2 = $_POST['Answer2'];

    // Check if email already exists
    $emailCheckSql = "SELECT * FROM customer WHERE Email = :Email";
    $stmt = $pdo->prepare($emailCheckSql);
    $stmt->bindParam(':Email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Email already registered. Please use a different email.');window.location.href='user_rigster.php';</script>";
    } else {
        // Process photo upload if a file is provided
        if (!empty($_FILES['Photo']['name'])) {
            $target_dir = "chattels/images/profiles/custimages/"; // Storing customer images
            $target_file = $target_dir . basename($_FILES["Photo"]["name"]);
            if (move_uploaded_file($_FILES["Photo"]["tmp_name"], $target_file)) {
                $photo = basename($_FILES["Photo"]["name"]); // Use uploaded file name
            }
        }

        // Insert into database using PDO
        $sql = "INSERT INTO customer (CustomerId, FirstName, LastName, Telephone, Email, Photo, Gender, Password, DOB, Address, Security1, Answer1, Security2, Answer2, role) VALUES (:CustomerId, :FirstName, :LastName, :Telephone, :Email, :Photo, :Gender, :Password, :DOB, :Address, :Security1, :Answer1, :Security2, :Answer2, 'Customer')";

        try {
            $stmt = $pdo->prepare($sql);
            // Bind parameters
            $stmt->bindParam(':CustomerId', $customerId);
            $stmt->bindParam(':FirstName', $firstName);
            $stmt->bindParam(':LastName', $lastName);
            $stmt->bindParam(':Telephone', $telephone);
            $stmt->bindParam(':Email', $email);
            $stmt->bindParam(':Photo', $photo);
            $stmt->bindParam(':Gender', $gender);
            $stmt->bindParam(':Password', $password);
            $stmt->bindParam(':DOB', $dob);
            $stmt->bindParam(':Address', $address);
            $stmt->bindParam(':Security1', $security1);
            $stmt->bindParam(':Answer1', $answer1);
            $stmt->bindParam(':Security2', $security2);
            $stmt->bindParam(':Answer2', $answer2);

            // Execute the statement
            $stmt->execute();
            // Redirect to login page after successful registration
            echo "<script>alert('Registration successful!');</script>";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ECHOCHICK-FARM | Users Registration</title>
  <link rel="icon" type="image/x-icon" href="../chattels/images/farmimages/favicon1.png">
  
  <?php include("../includes/head.php"); ?>
</head>
<body>
<header>
    <?php include("../includes/adminheader.php"); ?>
</header>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ECHOCHICK-FARM | Registration</title>
    <link rel="icon" type="image/x-icon" href="../chattels/images/farmimages/favicon1.png">
    <style>
        .auth-form-light {
            background: white;
            padding: 20px;
            margin: 50px auto;
            width: 70%;
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }
        .form-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .form-section {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        .form-section label {
            width: 180px;
            padding-right: 10px;
            font-weight: bold;
        }
        .form-control {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
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
        .btn-group {
            display: flex;
            justify-content: flex-end;
        }
        .btn-group input[type="submit"], .btn-group button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            margin-left: 10px;
        }
        .btn-danger {
            background-color: #d9534f;
            color: white;
        }
        .btn-secondary {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

    <div class="auth-form-light">
        <h2 class="text-center">Register Here</h2>
        <form id="registrationForm" action="user_rigster.php" method="post" enctype="multipart/form-data" novalidate>
            <div class="form-row">
                <div class="form-section">
                    <label for="FirstName">First Name:</label>
                    <input type="text" class="form-control" name="FirstName" id="FirstName" placeholder="First Name" required pattern="[A-Za-z]+" title="First name should not contain numbers">
                    <div class="invalid-feedback">Please enter a valid first name without numbers.</div>
                </div>
                <div class="form-section">
                    <label for="LastName">Last Name:</label>
                    <input type="text" class="form-control" name="LastName" id="LastName" placeholder="Last Name" required pattern="[A-Za-z]+" title="Last name should not contain numbers">
                    <div class="invalid-feedback">Please enter a valid last name without numbers.</div>
                </div>
                <div class="form-section">
                    <label for="CustomerId">National ID/Passport:</label>
                    <input type="text" class="form-control" name="CustomerId" id="CustomerId" placeholder="National ID/Passport" required>
                    <div class="invalid-feedback">Please enter a valid National ID/Passport.</div>
                </div>
                <div class="form-section">
                    <label for="Telephone">Telephone:</label>
                    <input type="tel" class="form-control" name="Telephone" id="Telephone" placeholder="Telephone" required pattern="^\d{10}$" title="Telephone number must be 10 digits">
                    <div class="invalid-feedback">Please enter a valid telephone number.</div>
                </div>
                <div class="form-section">
                    <label for="Email">Email:</label>
                    <input type="email" class="form-control" name="Email" id="Email" placeholder="Email" required>
                    <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>
                <div class="form-section">
                    <label for="Gender">Gender:</label>
                    <select name="Gender" class="form-control" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                    <div class="invalid-feedback">Please select your gender.</div>
                </div>
                <div class="form-row">
                    <div class="form-section">
                        <label for="Password">Password:</label>
                        <input type="password" class="form-control" id="Password" name="Password" placeholder="Password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
                        <div class="invalid-feedback">Password must contain at least one number, one uppercase and lowercase letter, and at least 8 or more characters.</div>
                    </div>
                    <div class="form-section">
                        <label for="confirmPassword">Confirm Password:</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required>
                        <div class="invalid-feedback">Passwords do not match.</div>
                    </div>
                </div>
                <div class="form-section">
                    <label for="DOB">Date of Birth:</label>
                    <input type="date" class="form-control" name="DOB" required>
                    <div class="invalid-feedback">Please enter your date of birth.</div>
                </div>
                <div class="form-section">
                    <label for="Address">Address:</label>
                    <input type="text" class="form-control" name="Address" placeholder="Address" required>
                    <div class="invalid-feedback">Please enter your address.</div>
                </div>
                <div class="form-section">
                    <label for="Security1">Security Question 1:</label>
                    <select name="Security1" class="form-control" required>
                        <option value="">Select a Security Question</option>
                        <option value="pet">What was the name of your first pet?</option>
                        <option value="mother">What's your mother's maiden name?</option>
                        <option value="birthCity">In what city were you born?</option>
                    </select>
                    <div class="invalid-feedback">Please select a security question.</div>
                </div>
                <div class="form-section">
                    <label for="Answer1">Security Answer 1:</label>
                    <input type="text" class="form-control" name="Answer1" placeholder="Security Answer" required>
                    <div class="invalid-feedback">Please enter your answer.</div>
                </div>
                <div class="form-section">
                    <label for="Security2">Security Question 2:</label>
                    <select name="Security2" class="form-control" required>
                        <option value="">Select a Security Question</option>
                        <option value="firstSchool">What is the name of your first school?</option>
                        <option value="favoriteFood">What is your favorite food?</option>
                        <option value="childhoodHero">Who was your childhood hero?</option>
                    </select>
                    <div class="invalid-feedback">Please select a security question.</div>
                </div>
                <div class="form-section">
                    <label for="Answer2">Security Answer 2:</label>
                    <input type="text" class="form-control" name="Answer2" placeholder="Answer 2" required>
                    <div class="invalid-feedback">Please enter your answer.</div>
                </div>
                <div class="form-section">
                    <label for="Photo">Photo (optional):</label>
                    <input type="file" class="form-control" name="Photo">
                </div>
                <div class="btn-group">
                    <input type="submit" class="btn btn-danger" value="Register">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='manage_users.php';">Cancel</button>
                </div>
            </div>
        </form>
    </div>
    <script>
        document.getElementById("registrationForm").addEventListener("submit", function(event){
            var form = event.target;
            var password = document.getElementById("Password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;

            if (password !== confirmPassword) {
                document.getElementById("confirmPassword").setCustomValidity("Passwords do not match");
                form.classList.add('was-validated');
                event.preventDefault(); // Prevent form submission
            } else {
                document.getElementById("confirmPassword").setCustomValidity("");
            }

            form.classList.add('was-validated');
        });

        document.querySelectorAll("input[pattern]").forEach(input => {
            input.addEventListener("input", function() {
                this.setCustomValidity("");
                if (!this.checkValidity()) {
                    this.setCustomValidity(this.title);
                }
            });
        });
    </script>
</body>
</html>