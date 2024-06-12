<?php
session_start();
error_reporting(1);
include('dbconnect.php');

/**
 * Ensures the user is logged in. If not, redirects to the login page.
 */
function check_login()
{
    // Check if 'userid' session variable is set and non-empty
    if (empty($_SESSION['userid'])) {
        // Get the host and URI to form the redirection URL
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = "index.php"; // The page to redirect to if the check fails

        // Optionally, clear the session for security
        $_SESSION = array(); // Clears all session data
        session_destroy(); // Destroys the session

        // Perform the redirection
        header("Location: http://$host$uri/$extra");
        exit; // Important to prevent further script execution after redirection
    }
}

?>
