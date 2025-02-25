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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['orderId']) && isset($_POST['orderStatus'])) {
    $orderId = mysqli_real_escape_string($con, $_POST['orderId']);
    $orderStatus = mysqli_real_escape_string($con, $_POST['orderStatus']);

    // Update the order status in the database
    $updateQuery = "UPDATE `orders` SET `Status` = '$orderStatus' WHERE `Id` = '$orderId'";
    
    if (mysqli_query($con, $updateQuery)) {
        echo "<script>alert('Order status updated successfully!'); window.location.href = 'ordersmanager.php';</script>";
    } else {
        echo "<script>alert('Error updating order status: " . mysqli_error($con) . "');</script>";
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

    body {
        background-color: #42413C;
    }
    .tableCart {
    width: 100%;
    border-collapse: collapse;
    margin: 10px auto;
}

.tableCart th, .tableCart td {
    font-size: 20px;
    padding: 10px;
    border: 1px solid #ddd;
}

.tableCart th {
    text-align: center ;
    background-color: #333;
    color: #fff;
}

.tableCart td {
    background-color: #f4f4f4;
}

.tableCart::-webkit-scrollbar {
    height: 10px;
}

.tableCart::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

/* Ensure responsiveness */
@media (max-width: 767px) {
    .tableCart {
        width: 100%;
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
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
        font-size: 25px;
        color: blue;
        font-weight: bold;
    }

    form select {
        padding-top: 6px;
        padding-bottom: 6px;
        font-size: 13px;
        border-radius: 4px;
        border: 1px solid #444;
        background-color: darkblue;
        color: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    form select:hover {
        border-color: #007BFF;
        background-color: #555;
    }

    form select:focus {
        outline: none;
        border-color: #0056b3;
        box-shadow: 0 0 5px rgba(0, 91, 255, 0.5);
    }
</style>

    </head>

    <body>
        <div class="divContainer">
            <?php include './Templates/adminheader.php'; ?>

    <div style="background-color: black; overflow: hidden; display: flex; flex-wrap: wrap; justify-content: center; align-items: center; padding: 10px 0;">
        <a href="ordersmanager.php" style="color: white; padding: 10px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Orders</a>
        <a href="Crud.php" style="color: white; padding: 10px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Products</a>
        <a href="usersmanager.php" style="color: white; padding: 10px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">User Accounts</a>
        <a href="feedbacksmanager.php" style="color: white; padding: 10px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Feedbacks</a>
    </div>
<br>
        <p class="pPageTitle" style="text-align: center; color: white;">List of Orders</p>
            <table class="tableCart" id="tableCart" border="1">
                
                <tr>
                    <th>Code</th>
                    <th>Set Time</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <!-- New column for Action Buttons -->
                    <th>Actions</th>
                </tr>

                <?php
                    $sql = "SELECT * FROM `orders` WHERE Status <> 4 AND Status <> 5 ORDER BY CreateTime ASC";
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
                        <a class="aContent" href="adminuserdetails.php?id=<?php echo $order['UserId']; ?>"><?php echo $order['Name']; ?></a>
                        <?php
                        } else {
                           echo $order['Name'];
                        }
                        ?>
                    </td>
                    <td>
                        <span class="spanProductPrice">
                        &#8369; <?php echo getOrderTotal($con, $order['Id']); ?>
                        </span>
                    </td>
                    <td>
                        <?php echo getOrderStatus($con, $row['Status']); ?>
                    </td>
                    <!-- Action Buttons -->
                    <td>
                        <!-- Example buttons: Edit and Delete -->
                        <form action="" method="post" style="display:inline;">
                            <input type="hidden" name="orderId" value="<?php echo $order['Id']; ?>">
                            <select name="orderStatus" onchange="this.form.submit()">
                                <option value="1" <?php if ($order['Status'] == 1) echo 'selected'; ?>>Pending</option>
                                <option value="2" <?php if ($order['Status'] == 2) echo 'selected'; ?>>Preparing</option>
                                <option value="3" <?php if ($order['Status'] == 3) echo 'selected'; ?>>Shipped</option>
                                <option value="4" <?php if ($order['Status'] == 4) echo 'selected'; ?>>Delivered</option>
                                <option value="5" onclick="return confirm('Are you sure you want to cancel this order?');" <?php if ($order['Status'] == 5) echo 'selected'; ?>>Cancelled</option>
                            </select>
                        </form>
                    </td>
                </tr>
                <?php
                        }
                    }
                ?>
            </table>

<br><br>
                            <p class="pPageTitle" style="text-align: center; color: white;">List of Closed Orders</p>
                            <section>
                                <table class="tableCart" id="tableCart" border="1" cellpadding="5" cellspacing="0">
                                    <tr>
                                      <th>Code</th>
                                      <th>Set Time</th>
                                      <th>Customer</th>
                                      <th>Total</th>
                                      <th>Status</t>
                                      <th>Actions</th>
                                    </tr>
                                    <?php
                                        $sql = "SELECT * FROM `orders` WHERE Status = 4 OR Status = 5 ORDER BY CreateTime ASC";
                                        $result = mysqli_query($con, $sql);
                                        if (mysqli_num_rows($result) >= 1) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $order = getOrderById($con, $row['Id']);
                                    ?>
                                    <tr>
                                        <td>
                                            <a class="aContent" href="orderdetails.php?id=<?php echo $order['Id']; ?>"><?php echo $order['Id']; ?></a>
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
                                            <?php echo getOrderStatus($con, $order['Status']); ?>
                                        </td>
                                        <td>
                                        <form action="" method="post" style="display:inline;">
                                            <input type="hidden" name="orderId" value="<?php echo $order['Id']; ?>">
                                            <select name="orderStatus" onchange="this.form.submit()">
                                                <option value="1" <?php if ($order['Status'] == 1) echo 'selected'; ?>>Pending</option>
                                                <option value="2" <?php if ($order['Status'] == 2) echo 'selected'; ?>>Preparing</option>
                                                <option value="3" <?php if ($order['Status'] == 3) echo 'selected'; ?>>Shipped</option>
                                                <option value="4" <?php if ($order['Status'] == 4) echo 'selected'; ?>>Delivered</option>
                                                <option value="5" onclick="return confirm('Are you sure you want to cancel this order?');" <?php if ($order['Status'] == 5) echo 'selected'; ?>>Cancelled</option>
                                            </select>
                                        </form>
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
            <?php include './Templates/footer.php'; ?>
        </div>
    </body>
</html>
