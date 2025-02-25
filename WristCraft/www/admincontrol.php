<?php
require_once './data/dbconnect.php';
include 'ultility/userultilities.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getCountProducts() {
    if (isset($_SESSION['GuestCarts'])) {
        return count($_SESSION['GuestCarts']);
    }
    else {
        return 0;
    }
}

$user = '';
if (isset($_SESSION['userId']))
{
    $user = getUserById($con, $_SESSION['userId']);
}
else {
    header("Location: login.php");
}

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
        <title>Management Page</title>
        <link href="styles/site.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="javascript.js"></script>
    </head>

    <body>
        <div class="divContainer">
            <?php include './Templates/header.php'; ?>
            <?php include './Templates/topmenu.php'; ?>

            <div class="divWrapper_2">
                <div class="divWrapper_1">
                    <?php include './Templates/leftmenu.php'; ?>

                    <div class="divMain">
                        <article class="articleContent">
                            <p class="pPageTitle">Management Page</p>
                            <a class="aContent" href="ordersmanager.php">Order Management</a> &nbsp;
                            <a class="aContent" href="Crud.php">Product Management</a> &nbsp;
                            <a class="aContent" href="usersmanager.php">User Account Management</a> &nbsp;
                            <a class="aContent" href="feedbacksmanager.php">Feedback Management</a> &nbsp;



                        </article>
                    </div>
                </div>
            </div>

            <?php include './Templates/footer.php'; ?>
        </div>
    </body>
</html>
