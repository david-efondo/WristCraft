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

    body {
        background-color: #42413C;
    }

    table {
        border-collapse: collapse;
        background-color: silver;
    }

    @media (max-width: 767px) {
    table {
        width: 100%;
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
    }

    .table::-webkit-scrollbar {
    height: 10px;
    }

    .table::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border: 1px solid black; /* White border for table cells */
    }
    th {
        text-align: center;
        background-color: gray; /* Darker background for table headers */
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

    <div style="background-color: black; overflow: hidden; display: flex; flex-wrap: wrap; justify-content: center; align-items: center; padding: 10px 0;">
        <a href="ordersmanager.php" style="color: white; padding: 10px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Orders</a>
        <a href="Crud.php" style="color: white; padding: 10px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Products</a>
        <a href="usersmanager.php" style="color: white; padding: 10px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">User Accounts</a>
        <a href="feedbacksmanager.php" style="color: white; padding: 10px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Feedbacks</a>
    </div>
    <h1 style="text-align: center; color: white;">User Accounts</h1>
    <div style="text-align: center; margin-bottom: 20px;">
        <form action="usersmanager.php" method="GET">
            <input type="text" name="search" placeholder="Search by name or email" style="padding: 10px; width: 200px; border-radius: 4px; border: 1px solid #ccc;">
            <button type="submit" style="padding: 10px 20px; border-radius: 4px; border: none; background-color: green; color: white; cursor: pointer;">Search</button>
        </form>
    </div>

    <?php
    if (isset($_GET['search'])) {
        $search = mysqli_real_escape_string($con, $_GET['search']);
        $sql = "SELECT * FROM `users` WHERE `Name` LIKE '%$search%' OR `Email` LIKE '%$search%' ORDER BY Id ASC";
        $result = mysqli_query($con, $sql);
    }
    ?>
    <table>
        <tr>
            <th>Actions</th>
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>CreateTime</th>
            <th>Sex</th>
            <th>DoB</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Admin</th>
        </tr>
        <?php
        // Loop through all fetched products and display them
        $result = mysqli_query($con, $sql);
        while($user = mysqli_fetch_array($result)) {
        ?>
        <tr>
            <td>               
                <!-- Edit Button -->
                <form action="edituser.php" method="GET" style="display:inline;">
                    <input type="hidden" name="Id" value="<?= $user['Id']; ?>">
                    <button type="submit" style="color: white; font-size: 15px; text-decoration: none; background-color: blue; padding: 5px 10px; border-radius: 4px; margin-right: 5px; border: none; cursor: pointer;">Edit</button>
                </form>
                <!-- Delete Button -->
                <form action="" method="GET" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?')">
                    <input type="hidden" name="delete" value="<?= $user['Id']; ?>">
                    <button type="submit" style="color: white; font-size: 15px; text-decoration: none; background-color: red; padding: 5px 10px; border-radius: 4px; margin-top: 10px; border: none; cursor: pointer;">Delete</button>
                </form>
            </td>

            <td><?= $user['Id']; ?></td>
            <td><?=  $user['Name']; ?></td>
            <td><?= $user['Email'];  ?></td>
            <td><?= $user['CreateTime']; ?></td>
            <td><?=  ($user['Sex'] == 0 ? 'Male' : 'Female'); ?> </td>
            <td><?=  $user['DoB']; ?> </td>
            <td><?=  $user['Address']; ?> </td>
            <td><?=  $user['Phone']; ?> </td>
            <td><?=  ($user['Admin'] == 1 ? 'Yes' : 'No'); ?></td>
            
            
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
