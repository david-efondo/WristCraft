<?php
session_start();
require_once './data/dbconnect.php';
include './ultility/userultilities.php';

// Get the count of products in the cart
function getCountProducts() {
    if (isset($_SESSION['GuestCarts'])) {
        return count($_SESSION['GuestCarts']);
    } else {
        return 0;
    }
}
if (isset($_POST['create'])) {
    $codeName = mysqli_real_escape_string($con, $_POST['CodeName']);
    $name = mysqli_real_escape_string($con, $_POST['Name']);
    $type = mysqli_real_escape_string($con, $_POST['Type']);
    $origin = mysqli_real_escape_string($con, $_POST['Origin']);
    $details = mysqli_real_escape_string($con, $_POST['Details']);
    $price = $_POST['Price'];

    // Handle the file upload for the product image
    $image = '';
    if (isset($_FILES['Picture']) && $_FILES['Picture']['error'] == 0) {
        $imageName = $_FILES['Picture']['name'];
        $imageTmp = $_FILES['Picture']['tmp_name'];
        $imageExt = pathinfo($imageName, PATHINFO_EXTENSION);
        $imageNewName = 'images/' . uniqid() . '.' . $imageExt;
        
        // Move the uploaded file to the desired location
        if (move_uploaded_file($imageTmp, $imageNewName)) {
            $image = $imageNewName;
        }
    }

    // Insert the product details into the database
    $sqlInsert = "INSERT INTO `watches` (CodeName, Name, Type, Origin, Details, Price, Picture) 
                  VALUES ('$codeName', '$name', '$type', '$origin', '$details', '$price', '$picture')";

    if (mysqli_query($con, $sqlInsert)) {
        echo "Product added successfully!";
        header("Location: Crud.php"); // Redirect after successful insert
        exit;
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

if (isset($_GET['delete'])) {
    $productId = $_GET['delete'];

    // Delete the product from the database
    $sqlDelete = "DELETE FROM `watches` WHERE Id = '$productId'";

    if (mysqli_query($con, $sqlDelete)) {
        echo "Product deleted successfully!";
        header("Location: Crud.php"); // Redirect to the product management page after deletion
        exit;
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// Handle the watch type filter (if any)
$typeWatches = '';
if (isset($_GET['type'])) {
    $typeWatches = $_GET['type'];
}

$products = array();

// If no type is specified, fetch all products
if ($typeWatches === '') {
    $sql = "SELECT * FROM `watches` ORDER BY Id ASC";
} else {
    $sql = "SELECT * FROM `watches` WHERE Type='$typeWatches' ORDER BY Id ASC";
}

// Execute the query to fetch the products
$result = mysqli_query($con, $sql);

// Check if the query was successful
if (!$result) {
    die("Error fetching products: " . mysqli_error($con));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <style>
    /* For screens wider than 768px (like tablets and desktops) */
    @media (min-width: 768px) {
    .container {
        width: 70%;
    }
    }

    /* For screens smaller than 768px (like phones) */
    @media (max-width: 767px) {
    .container {
        width: 100%;
    }
    }

    table {
        width: 70%;
        margin: 0 auto;
        border-collapse: collapse;
        background-color: black; /* Black background */
        color: white; /* White font color */
    }
    th, td {
        padding: 10px;
        text-align: left;
        border: 1px solid white; /* White border for table cells */
    }
    th {
        background-color: #333; /* Darker background for table headers */
    }
    td img {
        max-width: 100px; /* Optional: limit image size */
        max-height: 100px;
    }
    </style>

    <link href="styles/site.css" rel="stylesheet" type="text/css">
    <link href="styles/products.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="javascript.js"></script>
</head>

<body>
<div class="divContainer">
    <!-- Header -->
    <?php include './Templates/adminheader.php'; ?>

    <div style="background-color: #333; overflow: hidden; display: flex; justify-content: center; align-items: center; padding: 10px 0;">
        <a href="ordersmanager.php" style="color: white; padding: 14px 20px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Order Management</a>
        <a href="Crud.php" style="color: white; padding: 14px 20px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Manage Products</a>
        <a href="usersmanager.php" style="color: white; padding: 14px 20px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">User Account Management</a>
        <a href="feedbacksmanager.php" style="color: white; padding: 14px 20px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Feedback Management</a>
    </div>

    <h1 style="text-align: center; color: white;">Manage Products</h1>

    <!-- Create Product Form -->
    <h2 style="text-align: center; color: white;">Add Product</h2>
    <form method="POST" action="" enctype="multipart/form-data" style="max-width: 600px; margin: 30px auto; padding: 20px; background-color: gray; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <label for="CodeName" style="display: block; font-weight: bold; margin-bottom: 8px; font-size: 16px; color: white;">Product Code:</label>
    <input type="text" name="CodeName" required style="width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px;">

    <label for="Name" style="display: block; font-weight: bold; margin-bottom: 8px; font-size: 16px; color: white;">Product Name:</label>
    <input type="text" name="Name" required style="width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px;">

    <label for="Type" style="display: block; font-weight: bold; margin-bottom: 8px; font-size: 16px; color: white;">Product Brand:</label>
    <input type="text" name="Type" required style="width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px;">

    <label for="Origin" style="display: block; font-weight: bold; margin-bottom: 8px; font-size: 16px; color: white;">Product Origin:</label>
    <input type="text" name="Origin" required style="width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px;">

    <label for="Details" style="display: block; font-weight: bold; margin-bottom: 8px; font-size: 16px; color: white;">Description:</label>
    <textarea name="Details" required style="width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px; resize: vertical; height: 150px;"></textarea>

    <label for="Price" style="display: block; font-weight: bold; margin-bottom: 8px; font-size: 16px; color: white;">Price:</label>
    <input type="number" name="Price" step="0.01" required style="width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 16px;">

    <label for="image" style="display: block; font-weight: bold; margin-bottom: 8px; font-size: 16px; color: white;">Product Image:</label>
    <input type="file" name="image" accept="image/*" style="padding: 8px; margin-bottom: 15px;">

    <input type="submit" name="create" value="Create Product" style="background-color: #007BFF; color: white; border: none; padding: 12px 20px; font-size: 16px; border-radius: 4px; cursor: pointer; width: 100%; transition: background-color 0.3s;">
</form>

<!-- Hover Effect for Submit Button -->
<script>
    document.querySelector('input[type="submit"]').addEventListener('mouseover', function() {
        this.style.backgroundColor = '#0056b3';
    });
    document.querySelector('input[type="submit"]').addEventListener('mouseout', function() {
        this.style.backgroundColor = '#007BFF';
    });
</script>

    <!-- Display Products -->
    <h3>Product List</h3>
    <table border="1">
        <tr>
            <th>Product Code</th>
            <th>Picture</th>
            <th>Product Name</th>
            <th>Product Brand</th>
            <th>Product Origin</th>
            <th>Description</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>

        <?php
        // Loop through all fetched products and display them
        $result = mysqli_query($con, $sql);
        while($product = mysqli_fetch_array($result)) {
        ?>
        <tr>
            <td><?= $product['CodeName']; ?></td>
            <td><img src="images/<?php echo $product['Picture']; ?>" width="156px" height="280px"></td>
            <td><?= $product['Name']; ?></td>
            <td><?= $product['Type']; ?></td>
            <td><?= $product['Origin']; ?></td>
            <td><?= $product['Details']; ?></td>
            <td>₱<?= $product['Price']; ?></td>
            <td>
                <!-- Edit Link -->
                <a href="editproducts.php?Id=<?= $product['Id']; ?> "style="color: Blue; font-size: 15px; text-decoration: none; background-color: none;">Edit</a>
                <!-- Delete Link -->
                <a href="?delete=<?= $product['Id']; ?>" onclick="return confirm('Are you sure?')" style="color: red; font-size: 15px; text-decoration: none; background-color: none;">Delete</a>
            </td>
        </tr>
        <?php
        }
        ?>
    </table>
</div>

<?php include './Templates/footer.php'; ?>
</body>
</html>

<?php
// Close the database connection
mysqli_close($con);
?>
