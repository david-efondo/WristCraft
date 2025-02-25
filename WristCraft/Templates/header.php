<header>
    <div href="#">
        <img src="styles/Banner.png" alt="Watch Store" height="100px" id="imgBanner" />
    </div>

    <div class="divGuestCart">
        <?php
        if (isset($_SESSION['userId']) && $_SESSION['userId'] != '') {
            if (getUserById($con, $_SESSION['userId'])['Admin'] != 1) {
                ?>
                <a href="usercontrolGuest.php" target="_self">Hello <span id="txtGuestName"><?php echo $_SESSION['name']; ?></span></a>
                <?php
            } else {
                ?>
                <a href="admincontrol.php" target="_self">Hello <span id="txtGuestName"><?php echo $_SESSION['name']; ?></span></a>
                <?php
            }
            ?>
 <br>

            <?php
        } else {
            ?>
            <a href="login.php" target="_self">Log In</a> <span style="color:#FFF">|</span>
            <a href="register.php" target="_self">Register</a> <br>
            <?php
        }
        ?>
<hr>
        <a href="guestcart.php" target="_self">Your Cart: <span id="txtCountGuestCart"><?php echo getCountProducts(); ?></span> Product/s</a>
    </div>
</header>
