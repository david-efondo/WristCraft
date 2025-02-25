<?php
require_once './data/dbconnect.php';
include './ultility/userultilities.php';
include './ultility/orderultilities.php';
include './ultility/productultilities.php';
session_start();

function getCountProducts() {
    if (isset($_SESSION['GuestCarts'])) {
        return count($_SESSION['GuestCarts']);
    } else {
        return 0;
    }
}

$order = '';
if (isset($_GET['id'])) {
    $order = getOrderById($con, $_GET['id']);
    if ($order != NULL) {

    }
    else {
        header("Location: 404.php");
    }
}
else {
    header("Location: 404.php");
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Order Details</title>
        <link href="styles/site.css" rel="stylesheet" type="text/css">
        <link href="styles/usercontrolGuest.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="javascript.js"></script>
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

            <div class="divWrapper_2">

                    <div class="divMain">
                        <article class="articleContent">
                            <p class="pPageTitle">Order Details<?php $order['Id']; ?></p>

                            <section>
                                <form id="formUpdateUserInfors" action="" method="post">
                                    <table>
                                        <tr>
                                            <td width="100px"><b>Order Code:</b></td>
                                            <td><?php echo $order['Id']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Order Time:</b></td>
                                            <td><?php echo $order['CreateTime']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Status:</b></td>
                                            <td><?php echo getOrderStatus($con, $order['Status']); ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Delivery Address:</b></td>
                                            <td><?php echo $order['Address']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Phone Number:</b></td>
                                            <td><?php echo $order['Phone']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Request:</b></td>
                                            <td><?php echo $order['Request']; ?></td>
                                        </tr>
                                    </table>
                                </form>
                            </section>

                            <p>&nbsp;</p>

                            <section>
                                <table class="tableCart" id="tableCart" border="1" cellpadding="5" cellspacing="0">
                                    <tr id="trTop_TableCart">
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Amount</th>
                                        <th>Money</th>
                                    </tr>
                                    <?php

                                        $lines = $order['lines'];
                                        foreach ($lines as $line) {
                                            $thisWatches = getProductById($con, $line['WatchesId']);

                                            if ($thisWatches != NULL) {
                                                ?>
                                                <tr>
                                                    <td><a class="aContent" href="watches.php?id=<?php echo $thisWatches['Id']; ?>"><?php echo $thisWatches['CodeName']; ?></a></td>
                                                    <td>&#8369; <?php echo number_format(floatval($thisWatches['Price']), 0, ".", ",");?></td>
                                                    <td><?php echo $line['Quantity']; ?></td>
                                                    <td>&#8369;<?php echo number_format(floatval($thisWatches['Price']) * floatval($line['Quantity']), 0, ".", ",");?></td>
                                                </tr>
                                                <?php
                                            }
                                        }

                                    ?>
                                    <tr id="trBottom_TableCart">
                                        <td colspan="3" style="text-align: right">Total: </td>
                                        <td style="font-weight: bold">&#8369; <?php echo $order['total']; ?></td>
                                    </tr>
                                </table>
                            </section>
                        </article>
                        <br><br>
                        <button type="button" style="padding: 10px 20px; background-color: red; color: white; border: none; border-radius: 5px; cursor: pointer;" onclick="window.location.href='ordersmanager.php';">Back</button>
                    </div>
                </div>
            </div>

            <?php include './Templates/footer.php'; ?>
        </div>
    </body>
</html>
