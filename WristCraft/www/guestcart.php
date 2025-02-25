<?php
require_once './data/dbconnect.php';
include 'ultility/userultilities.php';
include 'ultility/productultilities.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getCountProducts() {
    if (isset($_SESSION['GuestCarts'])) {
        return count($_SESSION['GuestCarts']);
    } else {
        return 0;
    }
}
function deleteProductFromCart($productId) {
    unset($_SESSION['GuestCarts'][$productId]);
}
function EmptyCart() {
    unset($_SESSION['GuestCarts']);
}

$user = '';
if (isset($_SESSION['userId'])) {
    $user = getUserById($con, $_SESSION['userId']);
}

$cartProducts = array();
if (isset($_SESSION['GuestCarts'])) {
    $cartProducts = $_SESSION['GuestCarts'];

    if (isset($_GET['delete'])) {
        deleteProductFromCart($_GET['delete']);
    }
}

$addProductId = '';
$addProductQuantity = 0;
if (isset($_POST['txtProductQuantity'])) {
    $addProductId = $_POST['txtProductId'];
    $addProductQuantity = $_POST['txtProductQuantity'];

    if (!isset($cartProducts[$addProductId])) {
        $cartProducts[$addProductId] = intval($addProductQuantity);
    } else {
        $cartProducts[$addProductId] = $cartProducts[$addProductId] + intval($addProductQuantity);
    }


    $_SESSION['GuestCarts'] = $cartProducts;
}

define('ORDER_FAIL', 3);
define('ORDER_USER_SUCCESS', 1);
define('ORDER_GUEST_SUCCESS', 2);
define('ORDER_DIDNOTORDER', -1);
define('ORDER_EMPTYCART', 4);
$orderResult = ORDER_DIDNOTORDER;
$orderId = '';

if (isset($_POST['btnSubmit'])) {
    if (getCountProducts() > 0)
    {
        if (isset($_SESSION['userId']))
        {
            $userId = $_SESSION['userId'];
            $userAddress = $_POST['txtAddress'];
            $userPhone = $_POST['txtPhone'];
            $userRequest = $_POST['txtRequest'];

            try {
                $sql = "INSERT INTO `orders` (`CreateTime`, `Status`, `UserId`, `Address`, `Phone`, `Request`) VALUES(CURRENT_TIME(), 0, $userId, '$userAddress', '$userPhone', '$userRequest')";
                if (mysqli_query($con, $sql)) {

                    $sql = "SELECT Id FROM `orders` WHERE UserId='$userId' AND Status=0 ORDER BY ID DESC LIMIT 1";
                    $arrayResult = mysqli_fetch_array(mysqli_query($con, $sql));
                    $orderId = $arrayResult[0];

                    foreach ($cartProducts as $productId => $productQuantity) {
                        $thisWatches = getProductById($con, $productId);
                        $thisWatchesId = intval($thisWatches['Id']);
                        $thisWatchesPrice = intval($thisWatches['Price']);
                        $thisWatchesQuantity = intval($productQuantity);

                        $sql = "INSERT INTO `orderlines` (`OrderId`, `WatchesId`, `Quantity`, `Price`) VALUES ($orderId, $thisWatchesId, $thisWatchesQuantity, $thisWatchesPrice)";
                        mysqli_query($con, $sql);
                    }
                }

                EmptyCart();
                $orderResult = ORDER_USER_SUCCESS;
                header("Location: orderdetails.php?id=$orderId");

            }
            catch (Exception $ex) {
                $orderResult = ORDER_FAIL;
            }
        }
        /// Guest order
        else
        {
            $guestName = $_POST['txtName'];
            $guestAddress = $_POST['txtAddress'];
            $guestPhone = $_POST['txtPhone'];
            $guestEmail = $_POST['txtEmail'];
            $guestRequest = $_POST['txtRequest'];

            try {
                $sql = "INSERT INTO `orders` (`CreateTime`, `Status`, `Name`, `Address`, `Phone`, `Email`, `Request`) VALUES(CURRENT_TIME(), 0, '$guestName', '$guestAddress', '$guestPhone', '$guestEmail', '$guestRequest')";

                if (mysqli_query($con, $sql)) {

                    $sql = "SELECT Id FROM `orders` WHERE Name='$guestName' AND Phone='$guestPhone' AND Status=0 ORDER BY Id DESC LIMIT 1";

                    $arrayResult = mysqli_fetch_array(mysqli_query($con, $sql));
                    $orderId = $arrayResult[0];

                    foreach ($cartProducts as $product) {
                        $thisWatches = getProductById($con, array_search($product, $cartProducts));
                        $thisWatchesId = intval($thisWatches['Id']);
                        $thisWatchesPrice = intval($thisWatches['Price']);
                        $thisWatchesQuantity = intval($product);

                        $sql = "INSERT INTO `orderlines` (`OrderId`, `WatchesId`, `Quantity`, `Price`) VALUES ($orderId, $thisWatchesId, $thisWatchesQuantity, $thisWatchesPrice)";

                        mysqli_query($con, $sql);
                    }
                }

                EmptyCart();
                $orderResult = ORDER_GUEST_SUCCESS;
                header("Location: orderdetails.php?id=$orderId");

            }
            catch (Exception $ex) {
                $orderResult = ORDER_FAIL;
            }
        }
    } else {
        $orderResult = ORDER_EMPTYCART;
    }
}
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Your Cart</title>
        <link href="styles/site.css" rel="stylesheet" type="text/css">
        <link href="styles/guestcart.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="javascript.js"></script>
    </head>

    <body>
        <?php include './Templates/header.php'; ?>
        <?php include './Templates/topmenu.php'; ?>

        <div class="divContainer">


            <div class="divWrapper_2" style="overflow: hidden">
                <div class="divWrapper_1">

                    <div class="divMain">
                        <article class="articleContent">
                            <p class="pPageTitle">Your Cart</p>

                            <section>
                                <p>You have <?php echo getCountProducts(); ?> items in your cart. <a class="aContent" href="index.php">Continue to make purchases.</a></p>

                                    <table class="tableCart" id="tableCart" border="1" cellpadding="5" cellspacing="0">
                                        <tr id="trTop_TableCart">
                                            <th width="200px">Product</th>
                                            <th width="140px">Price</th>
                                            <th width="100px">Amount</th>
                                            <th width="140px">Money</th>
                                            <th width="80px">Erase</th>
                                        </tr>
                                        <?php
                                        $total = 0.0;

                                        if (getCountProducts() > 0) {
                                            $products = $_SESSION['GuestCarts'];
                                            foreach ($products as $productId => $productQuantity) {
                                                $thisWatches = getProductById($con, $productId);

                                                if ($thisWatches != '') {
                                                    $total += floatval($thisWatches['Price']) * floatval($productQuantity);
                                                    ?>
                                                    <tr>
                                                        <td><a class="aContent" href="watches.php?id=<?php echo $thisWatches['Id']; ?>"><?php echo $thisWatches['CodeName']; ?></a></td>
                                                        <td><?php echo number_format(floatval($thisWatches['Price']), 0, ".", ",") . " PHP"; ?></td>
                                                        <td><?php echo $productQuantity; ?></td>
                                                        <td><?php echo number_format(floatval($thisWatches['Price']) * floatval($productQuantity), 0, ".", ",") . " PHP"; ?></td>
                                                        <td style="text-align: center"><a class="aContent" href="guestcart.php?delete=<?php echo $thisWatches['Id']; ?>">Delete</a></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                        <tr id="trBottom_TableCart">
                                            <td colspan="3" style="text-align: right">Total: </td>
                                            <td colspan="2" style="font-weight: bold"><?php echo number_format($total, 0, ".", ","); ?> PHP</td>
                                        </tr>
                                    </table>
                            </section>

                            <section>
                                <p class="pPageTitle">Customer Information</p>

                                <form id="formRegister" action="" method="post">
                                <table class="tableRegister">
                                    <tr>
                                        <td width="150px">Full Name: <span class="spanRequiredField">*</span></td>
                                        <td width="330px">
                                            <?php
                                            if ($user === '') {
                                                ?>
                                                <input name="txtName" type="text" size="30" required="" />
                                                <?php
                                            } else {
                                                echo $user['Name'];
                                                ?>
                                                <input name="txtName" type="hidden" value="<?php echo $user['Name']; ?>" />
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Address: <span class="spanRequiredField">*</span></td>
                                        <td>
                                            <?php
                                            if ($user === '') {
                                                ?>
                                                <input name="txtAddress" type="text" size="50" required=""/>
                                                <?php
                                            } else {
                                                ?>
                                                <input name="txtAddress" type="text" size="50" value="<?php echo $user['Address']; ?>" required=""/>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Phone Number: <span class="spanRequiredField">*</span></td>
                                        <td>
                                            <?php
                                            if ($user === '') {
                                                ?>
                                                <input name="txtPhone" type="text" size="50" required=""/>
                                                <?php
                                            } else {
                                                ?>
                                                <input name="txtPhone" type="text" size="50" value="<?php echo $user['Phone']; ?>" required=""/>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Email: </td>
                                        <td>
                                            <?php
                                            if ($user === '') {
                                                ?>
                                                <input name="txtEmail" type="text" size="30" />
                                                <?php
                                            } else {
                                                echo $user['Email'];
                                                ?>
                                                <input name="txtEmail" type="hidden" value="<?php echo $user['Email']; ?>" />
                                                <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <td>Request: </td>
                                        <td>
                                            <textarea name="txtRequest" size="30" cols="40" rows="6" ></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <input class="btnOrder" name="btnSubmit" type="submit" value="ORDER"/>
                                            <input class="btnOrder" name="btnReset" type="reset" value="Clear"/>
                                        </td>
                                    </tr>
                                </table>
                                </form>
                            </section>

                            <?php
                                if ($orderResult === ORDER_FAIL)
                                {
                            ?>
                                <p class="pResultLogin">Order failed, please try again.</p>
                            <?php
                                }
                                if ($orderResult === ORDER_EMPTYCART)
                                {
                            ?>
                                <p class="pResultLogin">Empty Cart.</p>
                            <?php
                                }
                            ?>
                        </article>
                    </div>
                </div>
            </div>
        </div>
        <?php include './Templates/footer.php'; ?>
    </body>
</html>
