<?php
    session_start();
    require_once './data/dbconnect.php';
    include './ultility/userultilities.php';

    function getCountProducts() {
    if (isset($_SESSION['GuestCarts'])) {
        return count($_SESSION['GuestCarts']);
    }
    else {
        return 0;
    }
}

    $typeWatches = '';
    if (isset($_GET['type'])) {
        $typeWatches = $_GET['type'];
    }

    $products = array();
    $searchQuery = '';
    
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $searchQuery = mysqli_real_escape_string($con, $_GET['search']);
    }
    
    if ($typeWatches === '' && $searchQuery === '') {
        $sql = "SELECT * FROM `watches` ORDER BY Id ASC";
    } elseif ($typeWatches !== '' && $searchQuery === '') {
        $sql = "SELECT * FROM `watches` WHERE Type='$typeWatches' ORDER BY Id ASC";
    } elseif ($typeWatches === '' && $searchQuery !== '') {
        $sql = "SELECT * FROM `watches` WHERE Name LIKE '%$searchQuery%' ORDER BY Id ASC";
    } else {
        $sql = "SELECT * FROM `watches` WHERE Type='$typeWatches' AND Name LIKE '%$searchQuery%' ORDER BY Id ASC";
    }
    

    $result = mysqli_query($con, $sql);
    $products = mysqli_fetch_assoc($result);
?>

<!doctype html>
<html>
    <!-- Head HTML -->
    <head>
        <meta charset="utf-8">
        <title>Watch Catalog</title>
        <link href="styles/site.css" rel="stylesheet" type="text/css">
        <link href="styles/products.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="javascript.js"></script>
    </head>

    <!-- Body HTML -->
    <body>
        <div class="divContainer">
            <!-- Header -->
            <?php include './Templates/header.php'; ?>

            <!-- Top Menu -->
            <?php include './Templates/topmenu.php'; ?>

                    <!-- Main -->
                    <div class="divMain">
                        <article class="articleContent">
                            <!-- Page Title -->
                            <p class="pPageTitle">Watch Catalog -
                            <?php
                                if ($typeWatches === '') {
                                    echo "All";
                                }
                                else {
                                    echo $typeWatches;
                                }
                            ?>
                            </p>
                            
                            <!-- Search Form -->
                            <form method="GET" action="" class="searchForm">
                                <input type="text" name="search" placeholder="Search products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                <button type="submit">Search</button>
                            </form>

                            <section>
                                <div class="divProducts">
                                    <?php
                                        ///$sql = "SELECT * FROM `watches` WHERE Type='$typeWatches' ORDER BY Id ASC";
                                        $result = mysqli_query($con, $sql);
                                        while($product = mysqli_fetch_array($result)) {

                                    ?>      
                                    <div class="divProduct">
                                        <a href="watches.php?id=<?php echo $product['Id']; ?>">
                                            <img style="border-radius: 15px 15px 0 0;" src="<?php echo $product['Picture']; ?>" width="156px" height="280px"/>
                                        </a>
                                        <div class="divProductDetail">
                                            <a href="watches.php?id=<?php echo $product['Id']; ?>">
                                                <span class="spanProductName"><?php echo $product['Name']; ?></span> <br/>
                                            </a>
                                            <span class="spanProductPriceLabel">Price:</span> <span class="spanProductPrice">&#8369; <?php echo number_format(floatval($product['Price']), 0, ".", ","); ?></span>
                                        </div>
                                    </div>
                                    <?php

                                        }
                                    ?>
                                </div>
                            </section>

                        </article>
                    </div>

            <?php include './Templates/footer.php'; ?>
        </div>
    </body>
</html>
