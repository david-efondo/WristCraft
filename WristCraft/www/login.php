<?php
    session_start();
    require_once './data/dbconnect.php';
    include 'ultility/userultilities.php';

    function getCountProducts() {
    if (isset($_SESSION['GuestCarts'])) {
        return count($_SESSION['GuestCarts']);
    }
    else {
        return 0;
    }
}

    define('LOGIN_DIDNOTLOGIN', 2);
    define('LOGIN_SUCCESS', 0);
    define('LOGIN_INCORRECT', 1);
    $loginResult = LOGIN_DIDNOTLOGIN;

    if (isset($_SESSION['userId']) && $_SESSION['userId']!='') {
        header("Location: index.php");
    }
    else {
        $user_Email = "null";
        $user_Password = "null";

        if (isset($_POST['txtEmail'])) {
            $user_Email = $_POST['txtEmail'];
            $user_Password = $_POST['txtPassword'];
            $user_Password = md5($user_Password);

            $sql = "SELECT * FROM `users` WHERE Email='$user_Email' AND Password='$user_Password'";

            $result = mysqli_query($con, $sql);
            $rows = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if (mysqli_num_rows($result) == 1) {
                $_SESSION['userId'] = $rows['Id'];
                $_SESSION['email'] = $rows['Email'];
                $_SESSION['name'] = $rows['Name'];

                if ($_SESSION['name'] == 'admin') {
                    // Redirect to the admin dashboard if the user is an admin
                    header("Location: Crud.php");
                    exit;
                } else {
                    // Redirect to the home page if the user is a regular user
                    header("Location: index.php");
                    exit;
                }
            }   
            else {
                $loginResult = LOGIN_INCORRECT;
            }


        }
    }
?>
<!doctype html>
<html>
    <!-- Head HTML -->
    <head>
        <meta charset="utf-8">
        <title>Log In</title>
        <link href="styles/site.css" rel="stylesheet" type="text/css">
        <link href="styles/login.css" rel="stylesheet" type="text/css">
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
                            <p class="pPageTitle">Log In</p>
                            <section>
                                <form id="formLogin" action="" method="post">
                                <table class="tableRegister">
                                    <tr>
                                        <td width="120px">Email: <span class="spanRequiredField">*</span></td>
                                        <td width="330px"><input id="txtEmail" name="txtEmail" type="text" size="30" required=""/></td>
                                    </tr>
                                    <tr>
                                        <td>Password: <span class="spanRequiredField">*</span></td>
                                        <td><input type="password" id="txtPassword" name="txtPassword" size="30" required=""/></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <input id="btnSubmit" type="submit" value="Log in"/>
                                            <input id="btnReset" type="reset" value="Clear"/>
                                        </td>
                                    </tr>
                                </table>
                                </form>

                                <?php
                                    if ($loginResult===LOGIN_INCORRECT) {
                                ?>
                                    <p class="pResultLogin">Login information is incorrect.</p>
                                <?php
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
