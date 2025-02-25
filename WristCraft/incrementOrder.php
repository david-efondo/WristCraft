<?php
// Include database connection
require_once './data/dbconnect.php';
include './ultility/userultilities.php';
include './ultility/orderultilities.php';
session_start();

// Get admin's infors
$user = '';
if (isset($_SESSION['userId']))
{
    $user = getUserById($con, $_SESSION['userId']);

    if (intval($user['Admin']) != 1) {
        header("Location: login.php");
    }
}
else {
    header("Location: login.php");
}
// Get the order ID from the URL
if (isset($_GET['id'])) {
    $Id = $_GET['id'];
    
    // Ensure the ID is valid (e.g., an integer)
    if (is_numeric($Id)) {
        // Update the value (e.g., incrementing a specific field in the `orders` table)
        $sql = "UPDATE `orders` SET `Status` = `Status` + 1 WHERE `Id` = ?";
        
        // Prepare the SQL statement
        if ($stmt = mysqli_prepare($con, $sql)) {
            // Bind the orderId parameter to the SQL query
            mysqli_stmt_bind_param($stmt, "i", $Id);
            
            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect back to the orders list page or show a success message
                header("Location: ordersmanager.php"); // Redirect to your orders list page
                exit(); // Always call exit after header redirect
            } else {
                // Error handling
                echo "Error updating record: " . mysqli_error($con);
            }
            
            // Close the statement
            mysqli_stmt_close($stmt);
        }
    } else {
        // If the ID is not valid
        echo "Invalid order ID!";
    }
}

// Close the database connection
mysqli_close($con);
?>
