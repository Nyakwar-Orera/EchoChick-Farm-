<?php
session_start();
error_reporting(1);
include('dbconnect.php');
function check_login()
{
    if(strlen($_SESSION['adminid'])==0) // Adjusted to match login.php
    {   
        $host = $_SERVER['HTTP_HOST'];
        $uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra="index.php";       
        $_SESSION["adminid"]=""; // Clear the session if not logged in
        header("Location: http://$host$uri/$extra");
        exit; // Always include exit after header redirection
    }
}

?>