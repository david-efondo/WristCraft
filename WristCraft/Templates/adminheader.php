<header>
    <a href="#">
        <img src="styles/Banner.png" alt="Watch Store" height="50px" id="imgBanner" />
    </a>

    <div class="divGuestCart">
        <?php
        if (isset($_SESSION['userId']) && $_SESSION['userId'] != '') {
            if (getUserById($con, $_SESSION['userId'])['Admin'] != 1) {
                ?>
                <a href="usercontrolGuest.php">Hello <span id="txtGuestName"><?php echo $_SESSION['name']; ?></span></a>
                <?php
            } else {
                ?>
                <a href="#">Hello <span id="txtGuestName"><?php echo $_SESSION['name']; ?></span></a>
                <?php
            }
            ?>

            <a href="logout.php">(Log out)</a> <br>

            <?php
        } else {
            ?>
            <a href="login.php">Log In</a> <span style="color:#FFF">|</span>
            <a href="register.php">Register</a> <br>
            <?php
        }
        ?>
    </div>
</header>
