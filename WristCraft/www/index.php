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
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Home Page</title>
        <link href="styles/site.css" rel="stylesheet" type="text/css">
        <link href="styles/products.css" rel="stylesheet" type="text/css">
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
                            <p class="pPageTitle">Home Page</p>

                            <section>
                                <div class="divProducts">
                                    <?php
                                        $sql = "SELECT * FROM `watches` ORDER BY Id ASC";
                                        $result = mysqli_query($con, $sql);
                                        while($product = mysqli_fetch_array($result)) {
                                    ?>
                                    <div class="divProduct">
                                        <a href="watches.php?id=<?php echo $product['Id']; ?>">
                                            <img src="images/<?php echo $product['Picture']; ?>" width="156px" height="280px"/>
                                        </a>
                                        <div class="divProductDetail">
                                            <a href="watches.php?id=<?php echo $product['Id']; ?>">
                                                <span class="spanProductName"><?php echo $product['Name']; ?></span> <br/>
                                            </a>
                                            <span class="spanProductPriceLabel">Price:</span> <span class="spanProductPrice">&#8369; <?php echo $product['Price']; ?></span>
                                        </div>
                                    </div>
                                    <?php
                                        }
                                    ?>
                                </div>
                            </section>

                        </article>
                    </div>
                </div>
            </div>

            <?php include './Templates/footer.php'; ?>
        </div>
    </body>
</html>
