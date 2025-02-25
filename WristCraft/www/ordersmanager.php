<?php
require_once './data/dbconnect.php';
include './ultility/userultilities.php';
include './ultility/orderultilities.php';
session_start();

function getCountProducts() {
    if (isset($_SESSION['GuestCarts'])) {
        return count($_SESSION['GuestCarts']);
    } else {
        return 0;
    }
}

/// Get admin's infors
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

/// Update user's infos
$result = NULL;
if (isset($_SESSION['userId'])) {
    if (isset($_POST['txtAddress'])) {
        $userId = $user['Id'];
        $userSex = $_POST['txtSex'];
        $userDoB = $_POST['txtDoB'];
        $userAddress = $_POST['txtAddress'];
        $userPhone = $_POST['txtPhone'];

        $sql = "UPDATE `users` SET `Sex`='$userSex', `DoB`='$userDoB', `Address`='$userAddress',`Phone`='$userPhone' WHERE Id='$userId'";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $user = getUserById($con, $_SESSION['userId']);
        }
    }
}

?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Order Management</title>
        <link href="styles/site.css" rel="stylesheet" type="text/css">
        <link href="styles/usercontrolGuest.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="javascript.js"></script>
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
    /* Centering the wrapper on the page */
    .divWrapper_2 {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh; /* Full height of the viewport */
        margin: 0;
    }

    /* Wrapping the main content within the center */
    .divMain {
        width: 80%; /* Adjust width as per your preference */
        max-width: 1200px; /* Limit the width */
        background-color: #fff; /* White background for the content */
        padding: 20px;
        border-radius: 10px;
        position: initial;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add shadow to the content */
    }

    /* Optional: Make the article content a bit more styled */
    .articleContent {
        padding: 20px;
    }

    /* Styling for tables */
    .tableCart {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .tableCart th, .tableCart td {
        padding: 12px;
        text-align: left;
    }

    .tableCart th {
        background-color: #333;
        color: #fff;
    }

    .tableCart td {
        background-color: #f4f4f4;
    }

    /* Styling the links inside the table */
    .aContent {
        text-decoration: none;
        color: #007BFF;
    }

    .aContent:hover {
        color: #0056b3;
    }

    .spanProductPrice {
        color: green;
        font-weight: bold;
    }
</style>

    </head>

    <body>
        <div class="divContainer">
            <?php include './Templates/adminheader.php'; ?>

    <div style="background-color: #333; overflow: hidden; display: flex; justify-content: center; align-items: center; padding: 10px 0;">
        <a href="ordersmanager.php" style="color: white; padding: 14px 20px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Order Management</a>
        <a href="Crud.php" style="color: white; padding: 14px 20px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Manage Products</a>
        <a href="usersmanager.php" style="color: white; padding: 14px 20px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">User Account Management</a>
        <a href="feedbacksmanager.php" style="color: white; padding: 14px 20px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Feedback Management</a>
    </div>

    <div class="divWrapper_2">
    <article class="articleContent">    
        <p class="pPageTitle">List of Orders</p>
        <section>
            <table class="tableCart" id="tableCart" border="1" cellpadding="5" cellspacing="0">
                <tr id="trTop_TableCart">
                    <th width="60px">Code</th>
                    <th width="110px">Set Time</th>
                    <th width="160px">Customer</th>
                    <th width="140px">Total</th>
                    <th width="150px">Status</th>
                    <!-- New column for Action Buttons -->
                    <th width="150px">Actions</th>
                </tr>

                <?php
                    $sql = "SELECT * FROM `orders` WHERE Status <> 4 AND Status <> 5 ORDER BY Id DESC";
                    $result = mysqli_query($con, $sql);
                    if (mysqli_num_rows($result) >= 1) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $order = getOrderById($con, $row['Id']);
                ?>
                <tr>
                    <td>
                        <a class="aContent" href="adminorderdetails.php?id=<?php echo $order['Id']; ?>"><?php echo $order['Id']; ?></a>
                    </td>
                    <td>
                        <?php echo $order['CreateTime']; ?>
                    </td>
                    <td>
                        <?php
                        if ($order['UserId'] != NULL) {
                        ?>
                        <a class="aContent" href="userdetails.php?id=<?php echo $order['UserId']; ?>"><?php echo $order['Name']; ?></a>
                        <?php
                        } else {
                           echo $order['Name'];
                        }
                        ?>
                    </td>
                    <td>
                        <span class="spanProductPrice">
                        <?php echo getOrderTotal($con, $order['Id'])." PHP"; ?>
                        </span>
                    </td>
                    <td>
                        <?php echo getOrderStatus($con, $row['Status']); ?>
                    </td>
                    <!-- Action Buttons -->
                    <td>
                        <!-- Example buttons: Edit and Delete -->
                        <a href="incrementOrder.php?id=<?php echo $order['Id']; ?>" class="actionButton">Update Status</a>
                        <a href="cancelOrder.php?id=<?php echo $order['Id']; ?>" class="actionButton" onclick="return confirm('Are you sure you want to cancel this order?');">Cancel</a>
                    </td>
                </tr>
                <?php
                        }
                    }
                ?>
            </table>
        </section>


                            <p class="pPageTitle">List of Closed Orders</p>
                            <section>
                                <table class="tableCart" id="tableCart" border="1" cellpadding="5" cellspacing="0">
                                    <tr id="trTop_TableCart">
                                      <th width="60px">Code</th>
                                      <th width="110px">Set Time</th>
                                      <th width="160px">Customer</th>
                                      <th width="140px">Total</th>
                                      <th width="150px">Status</th>
                                    </tr>
                                    <?php
                                        $sql = "SELECT * FROM `orders` WHERE Status = 4 OR Status = 5 ORDER BY Id DESC";
                                        $result = mysqli_query($con, $sql);
                                        if (mysqli_num_rows($result) >= 1) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $order = getOrderById($con, $row['Id']);
                                    ?>
                                    <tr>
                                        <td>
                                            <a class="aContent" href="adminorderdetails.php?id=<?php echo $order['Id']; ?>"><?php echo $order['Id']; ?></a>
                                        </td>
                                        <td>
                                            <?php echo $order['CreateTime']; ?>
                                        </td>
                                        <td>
                                            <?php echo $order['Name']; ?>
                                        </td>
                                        <td>
                                            <span class="spanProductPrice">
                                            <?php echo getOrderTotal($con, $order['Id'])." PHP"; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo getOrderStatus($con, $order['Status']); ?>
                                        </td>
                                    </tr>
                                    <?php
                                            }
                                        }
                                    ?>
                                </table>
                            </section>
                        </article>
                    </div>
                </div>
            </div>

            <?php include './Templates/footer.php'; ?>
        </div>
    </body>
</html>
