<div class="divGuestCart">
    <?php
        if (isset($_SESSION['userId']) && $_SESSION['userId'] != '')
        {
    ?>

        <?php
            if (getUserById($con, $_SESSION['userId'])['Admin'] != 1)
            {
        ?>
            <a href="usercontrolGuest.php">Hello <span id="txtGuestName"><?php echo $_SESSION['name']; ?></span></a>
        <?php
            }
            else
            {
        ?>
            <a href="admincontrol.php">Hello <span id="txtGuestName"><?php echo $_SESSION['name']; ?></span></a>
        <?php
            }
        ?>
            <a href="logout.php">(Log off)</a> <br>

    <?php
        }
        else
        {
    ?>
        <a href="login.php">Log In</a> <span style="color:#FFF">|</span>
        <a href="register.php">Register</a> <br>
    <?php
        }
    ?>

    <a href="guestcart.php">Your cart: <span id="txtCountGuestCart"><?php echo getCountProducts(); ?></span> product</a>
</div>
