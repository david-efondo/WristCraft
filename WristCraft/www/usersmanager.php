<?php
session_start();
require_once './data/dbconnect.php';
include './ultility/userultilities.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch all data from the "user" table
$sql = "SELECT * FROM `users` ORDER BY Id ASC";
$result = mysqli_query($con, $sql);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        /* For screens wider than 768px (like tablets and desktops) */
    @media (min-width: 768px) {
    .container {
        width: 70%;
    }
    }

    /* For screens smaller than 768px (like phones) */
    @media (max-width: 767px) {
    .container {
        width: 100%;
    }
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: black; /* Black background */
        color: white; /* White font color */
    }
    th, td {
        padding: 10px;
        text-align: left;
        border: 1px solid white; /* White border for table cells */
    }
    th {
        background-color: #333; /* Darker background for table headers */
    }
    td img {
        max-width: 100px; /* Optional: limit image size */
        max-height: 100px;
    }
</style>

    <link href="styles/site.css" rel="stylesheet" type="text/css">
    <link href="styles/products.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="javascript.js"></script>
</head>

<body>
<div class="divContainer">
    <!-- Header -->
    <?php include './Templates/adminheader.php'; ?>

    <div style="background-color: #333; overflow: hidden; display: flex; justify-content: center; align-items: center; padding: 10px 0;">
        <a href="ordersmanager.php" style="color: white; padding: 14px 20px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Order Management</a>
        <a href="Crud.php" style="color: white; padding: 14px 20px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Manage Products</a>
        <a href="usersmanager.php" style="color: white; padding: 14px 20px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">User Account Management</a>
        <a href="feedbacksmanager.php" style="color: white; padding: 14px 20px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Feedback Management</a>
    </div>
    <h1>User Data</h1>
    <table>
        <tr>
            <th>Id</th>
            <th>Email</th>
            <th>CreateTime</th>
            <th>Admin</th>
            <th>Name</th>
            <th>Sex</th>
            <th>DoB</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php
        // Loop through all fetched products and display them
        $result = mysqli_query($con, $sql);
        while($user = mysqli_fetch_array($result)) {
        ?>
            <tr>
             <td><?= $user['Id']; ?></td>
            <td><?= $user['Email'];  ?></td>
            <td><?= $user['CreateTime']; ?></td>
            <td><?=  ($user['Admin'] == 1 ? 'Yes' : 'No'); ?></td>
            <td><?=  $user['Name']; ?></td>
            <td><?=  ($user['Sex'] == 0 ? 'Male' : 'Female'); ?> </td>
            <td><?=  $user['DoB']; ?> </td>
            <td><?=  $user['Address']; ?> </td>
            <td><?=  $user['Phone']; ?> </td>
            
            <td><a href="edituser.php?Id=<?= $user['Id']; ?> "style="color: Blue; font-size: 15px; text-decoration: none; background-color: none;">Edit</a></td>
            <td><a href="?delete=<?= $user['Id']; ?>" onclick="return confirm('Are you sure?')  "style="color: Red; font-size: 15px; text-decoration: none; background-color: none;">Delete</a></td>
        </tr>
            <?php
        }
        ?>
    </table>
    <?php include './Templates/footer.php'; ?>
</body>
</html>
<?php
// Close the database connection
$con->close();
?>
