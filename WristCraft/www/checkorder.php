<?php
    session_start();
    require_once './data/dbconnect.php';
    include './ultility/userultilities.php';
    include './ultility/orderultilities.php';
    include './ultility/productultilities.php';

    function getCountProducts() {
        if (isset($_SESSION['GuestCarts'])) {
            return count($_SESSION['GuestCarts']);
        }
        else {
            return 0;
        }
    }

    define('CHECK_DIDNOTCHECK', 0);
    define('CHECK_NOTFOUND', 1);
    define('CHECK_FOUND', 2);
    $checkResult = CHECK_DIDNOTCHECK;
    $order = null;

    if (isset($_GET['txtOrderId'])) {
        $order = getOrderById($con, $_GET['txtOrderId']);
        if ($order != NULL)
        {
            $checkResult = CHECK_FOUND;
        }
        else
        {
            $checkResult = CHECK_NOTFOUND;
        }
    }

?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Check Orders</title>
        <link href="styles/site.css" rel="stylesheet" type="text/css">
        <link href="styles/guestcart.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="javascript.js"></script>
    </head>

    <body>
        <?php include './Templates/header.php'; ?>
        <?php include './Templates/topmenu.php'; ?>

        <div class="divContainer">

            <div class="divWrapper_2">
                <div class="divWrapper_1">

                    <div class="divMain">
                        <article class="articleContent">
                            <p class="pPageTitle">Check Orders</p>

                            <section>
                                <form id="formSearch" action="#" method="get">
                                    <p>Order Code: <input type="text" name="txtOrderId" size="40" required=""/>
                                    <input type="submit" id="btnSubmit" value="Order Code"/>
                                    <input type="reset" id="btnReset" value="Re-enter"/></p>
                                </form>
                            </section>

                            <p>&nbsp;</p>

                            <section>
                                <?php
                                    if ($checkResult != CHECK_DIDNOTCHECK) {
                                        if ($checkResult === CHECK_FOUND) {
                                        ?>
                            <section>
                                <p class="pPageTitle">Order Information</p>
                                <p>Order Code: <?php echo $order['Id']; ?> <br/>
                                Order Time: <?php echo $order['CreateTime']; ?><br/>
                                Condition: <?php echo getOrderStatus($con, $order['Status']); ?><br/>
                                Delivery Address: <?php echo $order['Address']; ?><br/>
                                Phone Number: <?php echo $order['Phone']; ?><br/>
                                Order Request: <?php echo $order['Request']; ?><br/>
                                </p>
                            </section>

                            <p>&nbsp;</p>

                            <section>
                                <table class="tableCart" id="tableCart" border="1" cellpadding="5" cellspacing="0">
                                    <!-- Top Table -->
                                    <tr id="trTop_TableCart">
                                        <th width="200px">Product</th>
                                        <th width="140px">Price</th>
                                        <th width="100px">Amount</th>
                                        <th width="140px">Money</th>
                                    </tr>
                                    <?php

                                        $lines = $order['lines'];
                                        foreach ($lines as $line) {
                                            $thisWatches = getProductById($con, $line['WatchesId']);

                                            if ($thisWatches != NULL) {
                                                ?>
                                                <tr>
                                                    <td><a class="aContent" href="watches.php?id=<?php echo $thisWatches['Id']; ?>"><?php echo $thisWatches['CodeName']; ?></a></td>
                                                    <td><?php echo number_format(floatval($thisWatches['Price']), 0, ".", ",") . " PHP"; ?></td>
                                                    <td><?php echo $line['Quantity']; ?></td>
                                                    <td><?php echo number_format(floatval($thisWatches['Price']) * floatval($line['Quantity']), 0, ".", ",") . " PHP"; ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }

                                    ?>
                                    <tr id="trBottom_TableCart">
                                        <td colspan="3" style="text-align: right">Total: </td>
                                        <td style="font-weight: bold"><?php echo $order['total']; ?> PHP</td>
                                    </tr>
                                </table>
                            </section>
                                <?php
                                        }
                                        else {
                                        ?>
                                <span class="pResultLogin">No orders can be found.</span>
                                <?php
                                        }
                                    }
                                ?>
                            </section>

                        </article>
                    </div>
                </div>
            </div>
        </div>

        <?php include './Templates/footer.php'; ?>
    </body>
</html>
