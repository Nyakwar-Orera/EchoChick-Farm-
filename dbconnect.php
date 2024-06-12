<?php
$host = 'localhost'; // Host name
$dbname = 'poultryfarm'; // Database name
$username = 'root'; // Username for the database, default is 'root' in XAMPP
$password = ''; // Password for the database, default is empty in XAMPP

try {
    // Set DSN (Data Source Name)
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    
    // Create a PDO instance (connect to the database)
    $pdo = new PDO($dsn, $username, $password);
    
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Optional: Set default fetch mode to FETCH_ASSOC for ease of use
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Uncomment the following line if you want to disable emulated prepared statements
    // $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    
    // You can now use $pdo to access your database in your scripts
} catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>
