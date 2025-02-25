<header>
    <div href="#">
        <img src="styles/Banner.png" alt="Watch Store" height="50px" id="imgBanner" />
    </div>

    <div class="divGuestCart">
        <?php
        if (isset($_SESSION['userId']) && $_SESSION['userId'] != '') {
            if (getUserById($con, $_SESSION['userId'])['Admin'] != 1) {
                ?>
                <a href="usercontrolGuest.php">Hello <span id="txtGuestName"><?php echo $_SESSION['name']; ?></span></a>
                <?php
            } else {
                ?>
                <a href="admincontrol.php">Hello <span id="txtGuestName"><?php echo $_SESSION['name']; ?></span></a>
                <?php
            }
            ?>

            <a href="logout.php">(Log out)</a> <br>

            <?php
        } else {
            ?>
            <a href="login.php" target="_self">Log In</a> <span style="color:#FFF">|</span>
            <a href="register.php" target="_self">Register</a> <br>
            <?php
        }
        ?>
<hr>
        <a href="guestcart.php">Your Cart: <span id="txtCountGuestCart"><?php echo getCountProducts(); ?></span> Product/s</a>
    </div>
</header>
