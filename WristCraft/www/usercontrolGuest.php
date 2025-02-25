<?php
require_once './data/dbconnect.php';
include './ultility/userultilities.php';
include './ultility/orderultilities.php';
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

/// Get user's infors
$user = '';
if (isset($_SESSION['userId']))
{
    $user = getUserById($con, $_SESSION['userId']);
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
        <title>Customer Information</title>
        <link href="styles/site.css" rel="stylesheet" type="text/css">
        <link href="styles/usercontrolGuest.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="javascript.js"></script>
    </head>

    <body>
        <div class="divContainer">
            <?php include './Templates/header.php'; ?>

            <?php include './Templates/topmenu.php'; ?>

            <div class="divWrapper_2">
                <div class="divWrapper_1">

                    <div class="divMain">
                        <article class="articleContent">
                            <p class="pPageTitle">Customer Information</p>
                            <section>
                                <form id="formUpdateUserInfors" action="" method="post">

                                    <table>
                                        <tr>
                                            <td width="160px">Email: </td>
                                            <td><?php echo $user['Email']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Full Name: </td>
                                            <td><?php echo $user['Name']; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Gender: </td>
                                            <td>
                                                <input type="radio" name="txtSex" value="0" <?php if (intval($user['Sex'])===0) {echo "checked=''";} ?>/> Male &nbsp;
                                                <input type="radio" name="txtSex" value="1" <?php if (intval($user['Sex'])!=0) {echo "checked=''";} ?>/> Female
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Birthday: </td>
                                            <td>
                                                <input type="date" name="txtDoB" value="<?php echo $user['DoB']; ?>" required="" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Address: </td>
                                            <td><input type="text" name="txtAddress" value="<?php echo $user['Address']; ?>" size="30" required="" /></td>
                                        </tr>
                                        <tr>
                                            <td>Phone Number: </td>
                                            <td><input type="text" name="txtPhone" value="<?php echo $user['Phone']; ?>" size="30" required="" /></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td><input type="submit" name="btnSubmit" value="Update" /> <input type="reset" name="btnReset" value="Re-enter" /></td>
                                        </tr>
                                    </table>
                                </form>

                                <?php
                                if (isset($_SESSION['userId'])) {
                                    if (isset($_POST['txtAddress'])) {
                                        if ($result) {
                                ?>
                                    <p class="pResultLogin">Update information successful.</p>
                                <?php
                                        } else {
                                ?>
                                    <p class="pResultLogin">Update information failed.</p>
                                <?php
                                        }
                                    }
                                }
                                ?>
                            </section>

                            <!-- User's orders -->
                            <p class="pPageTitle">Order Management</p>
                            <section>
                                <table class="tableCart" id="tableCart" border="1" cellpadding="5" cellspacing="0">
                                    <!-- Top Table -->
                                    <tr id="trTop_TableCart">
                                      <th width="60px">Code</th>
                                      <th width="110px">Set Time</th>
                                      <th width="160px">Customer</th>
                                      <th width="140px">Total</th>
                                      <th width="150px">Status</th>
                                    </tr>

                                    <?php
                                    if (isset($_SESSION['userId'])) {
                                        $sql = "SELECT * FROM `orders` WHERE UserId = ".$user['Id']." ORDER BY Id DESC";
                                        $result = mysqli_query($con, $sql);
                                        if (mysqli_num_rows($result) >= 1) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                // Move the table row inside the loop
                                                ?>
                                                <tr>
                                                    <td>
                                                        <a class="aContent" href="orderdetails.php?id=<?php echo $row['Id']; ?>"><?php echo $row['Id']; ?></a>
                                                    </td>
                                                    <td>
                                                        <?php echo $row['CreateTime']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $user['Name']; ?>
                                                    </td>
                                                    <td>
                                                        <span class="spanProductPrice">
                                                        <?php echo getOrderTotal($con, $row['Id'])." PHP"; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php echo getOrderStatus($con, $row['Status']); ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            // Handle the case where no orders are found
                                            ?>
                                            <tr>
                                                <td colspan="5" style="text-align: center;">No orders found.</td>
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

            <!-- Footer -->
            <?php include './Templates/footer.php'; ?>
        </div>
    </body>
</html>
