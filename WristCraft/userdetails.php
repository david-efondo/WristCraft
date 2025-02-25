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

/// Get user's infors
$admin = '';
$account = '';
if (isset($_SESSION['userId']))
{
    $admin = getUserById($con, $_SESSION['userId']);

    if (isset($_GET['id']) && intval($admin['Admin']) === 1) {
        $account = getUserById($con, $_GET['id']);
    }
    else {
        header("Location: 404.php");
    }
}
else {
    header("Location: login.php");
}

$resultUpdate = NULL;
if (isset($_SESSION['userId'])) {
    if (isset($_POST['txtAddress'])) {
        $accountId = $account['Id'];
        $accountSex = $_POST['txtSex'];
        $accountDoB = $_POST['txtDoB'];
        $accountIdCard = $_POST['txtIdCard'];
        $accountAddress = $_POST['txtAddress'];
        $accountPhone = $_POST['txtPhone'];

        $sql = "UPDATE `users` SET `Sex`='$accountSex', "
                . "`DoB`='$accountDoB', `IdCard`='$accountIdCard', "
                . "`Address`='$accountAddress',`Phone`='$accountPhone' "
                . "WHERE Id='$accountId'";
        $resultUpdate = mysqli_query($con, $sql);

        if ($resultUpdate) {
            $account = getUserById($con, $accountId);
        }
    }
}

?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Account Information</title>
        <link href="styles/site.css" rel="stylesheet" type="text/css">
        <link href="styles/usercontrolGuest.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="javascript.js"></script>
    </head>

    <body>
        <div class="divContainer">
            <?php include './Templates/header.php'; ?>

            <?php include './Templates/topmenu.php'; ?>

            <div class="divWrapper_2" style="overflow: hidden">
                <div class="divWrapper_1">

                    <div class="divMain">
                        <article class="articleContent">
                        
                            <!-- User's infos -->
                            <p class="pPageTitle">Account Information</p>
                            <section>
                                <form id="formUpdateUserInfors" action="" method="post">
                                        
                                    <table>
                                        <tr>
                                            <td width="100px"><b>Email:</b> </td>
                                            <td><?php echo $account['Email']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Full Name:</b> </td>
                                            <td><?php echo $account['Name']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Gender:</b> </td>
                                            <td>
                                                <?php echo $account['Sex'] == 0 ? 'Male' : 'Female'; ?> 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Birthday:</b> </td>
                                            <td>
                                                <?php echo $account['DoB']; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Address:</b> </td>
                                            <td><?php echo $account['Address']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Phone Number:</b> </td>
                                            <td><?php echo $account['Phone']; ?></td>
                                        </tr>
                                    </table>
                                </form>
                            </section>

                            <!-- User's orders -->
                            <p class="pPageTitle">User Orders</p>
                            <section>
                                <table class="tableCart" id="tableCart" border="1" cellpadding="5" cellspacing="0">
                                    <!-- Top Table -->
                                    <tr id="trTop_TableCart">
                                      <th>Code</th>
                                      <th>Set Time</th>
                                      <th>Total</th>
                                      <th>Status</th>
                                    </tr>
                                    <?php

                                        $sql = "SELECT * FROM `orders` WHERE UserId = ".$account['Id']." ORDER BY Id DESC";
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
                                            <span class="spanProductPrice">
                                            &#8369; <?php echo getOrderTotal($con, $order['Id']); ?>
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
                        <br><br>
                        <button type="button" style="padding: 10px 20px; background-color: red; color: white; border: none; border-radius: 5px; cursor: pointer;" onclick="window.location.href='ordersmanager.php';">Back</button>
                    </div>
                </div>
            </div>

            <?php include './Templates/footer.php'; ?>
        </div>
    </body>
</html>
