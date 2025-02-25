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

    // Check if the user selected "Other" and provided a new brand
    if ($type === "Other" && isset($_POST['OtherBrand']) && !empty($_POST['OtherBrand'])) {
        $newBrand = mysqli_real_escape_string($con, $_POST['OtherBrand']);

        // Check if the new brand already exists in the "types" table
        $checkQuery = "SELECT * FROM `types` WHERE Name = '$newBrand'";
        $checkResult = mysqli_query($con, $checkQuery);

        if (mysqli_num_rows($checkResult) == 0) {
            // If not, insert the new brand into the "types" table
            $insertTypeQuery = "INSERT INTO `types` (Name) VALUES ('$newBrand')";
            mysqli_query($con, $insertTypeQuery);
        }

        // Set the product type to the new brand
        $type = $newBrand;
    }

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
                  VALUES ('$codeName', '$name', '$type', '$origin', '$details', '$price', '$image')";

    if (mysqli_query($con, $sqlInsert)) {
        echo "<script>alert('Product added successfully!'); window.location.href='Crud.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
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

/* Responsive Table Wrapper */
.table-wrapper {
    width: 100%;
    overflow-x: auto; /* Enables horizontal scrolling */
    display: flex;
}

/* Table Styling */
table {
    margin: 10px;
    background-color: silver;
    width: max-content; /* Prevents the table from shrinking */
    border-collapse: collapse;
    min-width: 900px; /* Ensures a minimum width */
}

@media (max-width: 767px) {
    .table-wrapper {
        width: 100%;
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
}

.table-wrapper::-webkit-scrollbar {
    height: 10px;
}

.table-wrapper::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}
th, td {
    padding: 10px;
    text-align: left;
    border: 1px solid black;
}

th {
    text-align: center;
    background-color: grey;
}

td img {
    max-width: 100px;
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

    <div style="background-color: black; overflow: hidden; display: flex; flex-wrap: wrap; justify-content: center; align-items: center; padding: 10px 0;">
        <a href="ordersmanager.php" style="color: white; padding: 10px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Orders</a>
        <a href="Crud.php" style="color: white; padding: 10px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Products</a>
        <a href="usersmanager.php" style="color: white; padding: 10px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">User Accounts</a>
        <a href="feedbacksmanager.php" style="color: white; padding: 10px; text-align: center; text-decoration: none; font-size: 18px; transition: background-color 0.3s;">Feedbacks</a>
    </div>

    <h1 style="text-align: center; color: white;">Manage Products</h1>

    <!-- Create Product Form -->
    
    <form method="POST" action="" enctype="multipart/form-data" style="margin: 10px; padding: 20px; background-color: silver; color: black; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <h2 style="text-align: center; color: black;">Add Product</h2>
    <label for="CodeName" style="display: block; font-weight: bold; margin-bottom: 8px; font-size: 16px; color: black;">Product Code:</label>
    <input type="text" name="CodeName" required style="width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid black; border-radius: 4px; box-sizing: border-box; font-size: 16px;">

    <label for="Name" style="display: block; font-weight: bold; margin-bottom: 8px; font-size: 16px; color: black;">Product Name:</label>
    <input type="text" name="Name" required style="width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid black; border-radius: 4px; box-sizing: border-box; font-size: 16px;">

    <label for="Type" style="display: block; font-weight: bold; margin-bottom: 8px; font-size: 16px; color: black;">Product Brand:</label>
    <select name="Type" required style="width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid black; border-radius: 4px; box-sizing: border-box; font-size: 16px;">
        <option disabled selected>Select a Brand</option>
        <?php
        $typeQuery = "SELECT DISTINCT Type FROM `watches`";
        $typeResult = mysqli_query($con, $typeQuery);
        while ($typeRow = mysqli_fetch_assoc($typeResult)) {
            echo '<option value="' . $typeRow['Type'] . '">' . $typeRow['Type'] . '</option>';
        }
        ?>
        <option value="Other">Other Brand...</option>            
    </select>

    <script>
            document.querySelector('select[name="Type"]').addEventListener('change', function() {
                if (this.value === 'Other') {
                    var otherBrandInput = document.createElement('input');
                    otherBrandInput.type = 'text';
                    otherBrandInput.name = 'OtherBrand';
                    otherBrandInput.placeholder = 'Enter other brand';
                    otherBrandInput.required = true;
                    otherBrandInput.style.width = '100%';
                    otherBrandInput.style.padding = '12px';
                    otherBrandInput.style.marginTop = '15px';
                    otherBrandInput.style.border = '1px solid black';
                    otherBrandInput.style.borderRadius = '4px';
                    otherBrandInput.style.boxSizing = 'border-box';
                    otherBrandInput.style.fontSize = '16px';
                    
                    this.parentNode.insertBefore(otherBrandInput, this.nextSibling);
                } else {
                    var otherBrandInput = document.querySelector('input[name="OtherBrand"]');
                    if (otherBrandInput) {
                        otherBrandInput.remove();
                    }
                }
            });
    </script>

    <label for="Origin" style="display: block; font-weight: bold; margin-bottom: 8px; font-size: 16px; color: black;">Product Origin:</label>
    <input type="text" name="Origin" required style="width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid black; border-radius: 4px; box-sizing: border-box; font-size: 16px;">

    <label for="Details" style="display: block; font-weight: bold; margin-bottom: 8px; font-size: 16px; color: black;">Description:</label>
    <textarea name="Details" required style="width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid black; border-radius: 4px; box-sizing: border-box; font-size: 16px; resize: vertical; height: 150px;"></textarea>

    <label for="Price" style="display: block; font-weight: bold; margin-bottom: 8px; font-size: 16px; color: black;">Price:</label>
    <input type="number" name="Price" step="0.01" required style="width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid black; border-radius: 4px; box-sizing: border-box; font-size: 16px;">

    <label for="image" style="display: block; font-weight: bold; margin-bottom: 8px; font-size: 16px; color: black;">Product Image:</label>
    <input type="file" name="image" accept="image/*" style="padding: 15px; margin-bottom: 15px;">

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
    <h3 style="text-align: center; color: white;">Product List</h3>
    <div class="table-wrapper">
    <table border="1">
        <tr>
            <th>Actions</th>
            <th>Product Name</th>
            <th>Picture</th>
            <th>Product Brand</th>
            <th>Product Origin</th>
            <th>Description</th>
            <th>Price</th>
        </tr>

        <?php
        // Loop through all fetched products and display them
        $result = mysqli_query($con, $sql);
        while($product = mysqli_fetch_array($result)) {
        ?>
        <tr>
            <td>
                <!-- Edit Button -->
                <form action="editproducts.php" method="GET" style="display:inline;">
                    <input type="hidden" name="Id" value="<?= $product['Id']; ?>">
                    <button type="submit" style="color: white; font-size: 15px; text-decoration: none; background-color: blue; padding: 5px 10px; border-radius: 4px; margin-right: 5px; border: none; cursor: pointer;">Edit</button>
                </form>
                <!-- Delete Button -->
                <form action="" method="GET" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this product?')">
                    <input type="hidden" name="delete" value="<?= $product['Id']; ?>">
                    <button type="submit" style="color: white; font-size: 15px; text-decoration: none; background-color: red; padding: 5px 10px; border-radius: 4px; margin-top: 10px; border: none; cursor: pointer;">Delete</button>
                </form>
            </td>

            <td><?= $product['Name']; ?></td>
            <td><img src="images/<?php echo $product['Picture']; ?>" width="156px" height="280px"></td>
            <td><?= $product['Type']; ?></td>
            <td><?= $product['Origin']; ?></td>
            <td><?= $product['Details']; ?></td>
            <td>₱<?= $product['Price']; ?></td>
            
        </tr>
        <?php
        }
        ?>
    </table>
</div>
</div>

<?php include './Templates/footer.php'; ?>
</body>
</html>

<?php
// Close the database connection
mysqli_close($con);
?>
