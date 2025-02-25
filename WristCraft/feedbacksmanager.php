<?php
session_start();

require_once './data/dbconnect.php';
include './ultility/userultilities.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Retrieve data from the "feedbacks" table
$sql = "SELECT * FROM `feedbacks` ORDER BY CreateTime DESC";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <style>
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

    <div style="background-color: black; overflow: hidden; display: flex; flex-wrap: wrap; justify-content: center; align-items: center; padding: 10px 0;">
        <a href="ordersmanager.php" style="color: white; padding: 10px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Orders</a>
        <a href="Crud.php" style="color: white; padding: 10px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Products</a>
        <a href="usersmanager.php" style="color: white; padding: 10px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">User Accounts</a>
        <a href="feedbacksmanager.php" style="color: white; padding: 10px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Feedbacks</a>
    </div>

    <h1 style="text-align: center; color: white;">Feedbacks</h1>
    <table>
    <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Title</th>
    <th>Details</th>
    <th>CreateTime</th>
    </tr>

    <?php
    $result = mysqli_query($con, $sql);
    while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
        <td><?= $row["Name"]; ?></td>
        <td><?= $row["Email"]; ?></td>
        <td><?= $row["Title"]; ?></td>
        <td><?= $row["Details"]; ?></td>
        <td><?= $row["CreateTime"]; ?></td>
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