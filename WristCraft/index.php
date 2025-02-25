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
        <title>Home Page</title>
        <link href="styles/site.css" rel="stylesheet" type="text/css">
        <link href="styles/products.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="javascript.js"></script>
        <style>
        .carousel {
            width: 100%;
            margin: auto;
            overflow: hidden;
            position: relative;
        }

        .carousel::before{
            content: '';
            position: absolute;
            top: 0;
            width: 15%;
            height: 300px;
            background: linear-gradient(to left, rgba(255, 255, 255, 0), silver);
            z-index: 2;
        }
        
        .carousel::after {
            content: '';
            position: absolute;
            width: 15%;
            height: 300px;
            background: linear-gradient(to right, rgba(255, 255, 255, 0), silver);
            z-index: 2;
        }

        .carousel::before {
            left: 0;
            top: 0;
        }
        .carousel::after {
            right: 0;
            top: 0;
        }


        

        .carousel-track {
            display: flex;
            width: calc(150px * 18); /* Double the width to accommodate duplicates */
            animation: scroll 20s linear infinite;
        }

        .carousel-item {
            width: 20%; /* Adjust based on the number of items */
            box-sizing: border-box;
            perspective: 500px;
        }

        .carousel-item img {
            margin: 10px;
            border-radius: 15px;
            width: 156px;
            height: 280px;
            display: block;
            transition: transform 1%;
        }

        .carousel-item img:hover {
            transform: translateZ(50px);
        }

        @keyframes scroll {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%); /* Move half the width to create a loop */
            }
        }
        </style>
    </head>

    <body>
        <div class="divContainer">
        <img src="styles/Banner.png" alt="Watch Store" width="100%" id="imgBanner" />
            <?php include './Templates/indextopmenu.php'; ?>
                    <div class="divMain">
                    <h1><?php
                        if (isset($_SESSION['userId']) && $_SESSION['userId'] != '') {
                            if (getUserById($con, $_SESSION['userId'])['Admin'] != 1) {
                                ?>
                                <a target="_self">Hello, <span id="txtGuestName"><?php echo $_SESSION['name']; ?>!</span></a>
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
                            <a>Hello, Guest!</a>
                            <?php
                        }
                        ?>
                        </h1>
                        <div class="carousel">
                            <div class="carousel-track">
                                <?php
                                // Directory containing the images
                                $imageDirectory = 'images/'; // Update this path to your image directory
                                // Get all image files from the directory
                                $images = glob($imageDirectory . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);

                                // Loop through the images and create carousel items
                                foreach ($images as $image) {
                                    echo '<div class="carousel-item"><img src="' . $image . '" alt="' . basename($image) . '"></div>';
                                }
                                // Duplicate items to create a seamless loop
                                foreach ($images as $image) {
                                    echo '<div class="carousel-item"><img src="' . $image . '" alt="' . basename($image) . '"></div>';
                                }
                                ?>
                            </div>
                        </div>




                        <article class="articleContent">
                            <!-- Search Form -->
                            <form method="GET" action="" class="searchForm">
                                <input type="text" name="search" placeholder="Search products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                <button type="submit">Search</button>
                            </form>

                            <section>
                                <div class="divProducts">
                                    <?php
                                        $search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
                                        $sql = "SELECT * FROM `watches`";
                                        
                                        if (!empty($search)) {
                                            $sql .= " WHERE Name LIKE '%$search%'";
                                        }
                                        
                                        $sql .= " ORDER BY Id ASC";
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

            <?php include './Templates/footer.php'; ?>
        </div>
    </body>
</html>