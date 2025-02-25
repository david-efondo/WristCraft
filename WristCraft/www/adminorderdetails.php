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
            <link href="styles/guestcart.css" rel="stylesheet" type="text/css">
            <script type="text/javascript" src="javascript.js"></script>
            <style>
.divWrapper_2 {
    display: flex;
    justify-content: center;  /* Centers horizontally */
    align-items: center;      /* Centers vertically */
    height: 100vh;            /* Makes the container fill the full viewport height */
    padding: 20px;            /* Optional: Adds some padding around the content */
    box-sizing: border-box;   /* Ensures padding is included in the height calculation */
}

/* Centering the .divMain (main content area) */
.divMain {
    float: left;
    padding-top: 0px;
    padding-bottom: 10px;
    padding-left: 10px;
    margin-bottom: 10px;
    margin-left: 1px;
    position: relative;
    left: 50px;
}

/* Optional: Styling the articleContent for spacing and text alignment */
.articleContent {
    text-align: left;         /* Ensures text is left-aligned */
}

/* Optional: Table styling */
.tableCart {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.tableCart th, .tableCart td {
    padding: 10px;
    text-align: center;
}

.tableCart th {
    background-color: #f4f4f4;
}

#trTop_TableCart {
    background-color: #f0f0f0;
}

#trBottom_TableCart {
    background-color: #f9f9f9;
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

                        <div class="divMain">
                            <article class="articleContent">
                                <p class="pPageTitle">Order Details<?php $order['Id']; ?></p>

                                <section>
                                    <p>Order Code: <?php echo $order['Id']; ?> <br/>
                                    Order Time: <?php echo $order['CreateTime']; ?><br/>
                                    Status: <?php echo getOrderStatus($con, $order['Status']); ?><br/>
                                    Delivery Address: <?php echo $order['Address']; ?><br/>
                                    Phone Number: <?php echo $order['Phone']; ?><br/>
                                    Request: <?php echo $order['Request']; ?><br/>
                                    </p>
                                </section>

                                <p>&nbsp;</p>

                                <section>
                                    <table class="tableCart" id="tableCart" border="1" cellpadding="5" cellspacing="0">
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
                            </article>
                        </div>
                    </div>
                </div>

                <?php include './Templates/footer.php'; ?>
            </div>
        </body>
    </html>
