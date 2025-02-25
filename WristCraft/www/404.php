<?php
    require_once './data/dbconnect.php';
    include 'ultility/userultilities.php';
    session_start();

    function getCountProducts() {
    if (isset($_SESSION['GuestCarts'])) {
        return count($_SESSION['GuestCarts']);
    }
    else {
        return 0;
    }
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Search</title>
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
                            <p class="pPageTitle">Page not found.</p>

                            <section>
                                <span>The page you requested does not exist.</span> <br/>
                              Return to <a class="aContent" href="index.php">Home Page</a>
                            </section>

                        </article>
                    </div>
                </div>
            </div>

            <?php include './Templates/footer.php'; ?>
        </div>
    </body>
</html>
