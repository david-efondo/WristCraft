<?php
session_start();
require_once './data/dbconnect.php';

// Check if the 'id' parameter is passed in the URL
if (isset($_GET['Id'])) {
    $productId = $_GET['Id'];

    // Fetch the product details from the database
    $sql = "SELECT * FROM `watches` WHERE Id = '$productId'";
    $result = mysqli_query($con, $sql);
    
    if (!$result || mysqli_num_rows($result) == 0) {
        die("Product not found.");
    }

    $product = mysqli_fetch_assoc($result);
}

// Handle the form submission for updating the product
if (isset($_POST['update'])) {
    $codeName = mysqli_real_escape_string($con, $_POST['CodeName']);
    $name = mysqli_real_escape_string($con, $_POST['Name']);
    $type = mysqli_real_escape_string($con, $_POST['Type']);
    $origin = mysqli_real_escape_string($con, $_POST['Origin']);
    $details = mysqli_real_escape_string($con, $_POST['Details']);
    $price = $_POST['Price'];

    // Handle the file upload for the new product image
    $image = $product['Picture']; // Retain the current image if not updated
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageName = $_FILES['image']['name'];
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageExt = pathinfo($imageName, PATHINFO_EXTENSION);
        $imageNewName = 'images/' . uniqid() . '.' . $imageExt;
        
        // Move the uploaded file to the desired location
        if (move_uploaded_file($imageTmp, $imageNewName)) {
            $image = $imageNewName; // Use the new image if uploaded
        }
    }

    // Update the product details in the database
    $sqlUpdate = "UPDATE `watches` SET 
                    CodeName = '$codeName', 
                    Name = '$name', 
                    Type = '$type', 
                    Origin = '$origin', 
                    Details = '$details', 
                    Price = '$price', 
                    Picture = '$image' 
                  WHERE Id = '$productId'";

    if (mysqli_query($con, $sqlUpdate)) {
        echo "Product updated successfully!";
        header("Location: Crud.php"); // Redirect after successful update
        exit;
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="styles/site.css" rel="stylesheet" type="text/css">
    <link href="styles/products.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="javascript.js"></script>
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
    /* General body styling for dark mode */
    body {
        font-family: Arial, sans-serif;
        background-color: #121212; /* Dark background */
        color: #E0E0E0; /* Light text color */
        margin: 0;
        padding: 0;
    }

    /* Container to center content */
    .container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
        background-color: #1E1E1E; /* Darker container background */
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow for contrast */
    }

    /* Title styling */
    h2 {
        text-align: center;
        color: white; /* Green for the title */
    }

    /* Form styling */
    form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    label {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 8px;
        color: #E0E0E0; /* Light text for labels */
    }

    input[type="text"], input[type="number"], textarea, input[type="file"] {
        padding: 10px;
        font-size: 16px;
        border: 1px solid #333; /* Dark borders */
        border-radius: 5px;
        width: 100%;
        background-color: #333; /* Dark background for inputs */
        color: #E0E0E0; /* Light text for inputs */
        box-sizing: border-box;
    }

    textarea {
        resize: vertical;
        height: 150px;
    }

    input[type="submit"] {
        padding: 10px;
        background-color: #4CAF50; /* Green button */
        color: white;
        border: none;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    input[type="submit"]:hover {
        background-color: #45a049; /* Darker green on hover */
    }

    /* Image styling */
    img {
        border-radius: 5px;
        margin-top: 10px;
    }

    /* Make the form responsive */
    @media (max-width: 768px) {
        .container {
            width: 90%;
        }

        label, input[type="text"], input[type="number"], textarea {
            font-size: 14px;
        }
    }
</style>


</head>

<body>

    <h2>Edit Product</h2>

    <!-- Edit Product Form -->
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="CodeName">Product Code:</label><br>
        <input type="text" name="CodeName" value="<?= $product['CodeName']; ?>" required><br><br>

        <label for="Name">Product Name:</label><br>
        <input type="text" name="Name" value="<?= $product['Name']; ?>" required><br><br>

        <label for="Type">Product Brand:</label><br>
        <input type="text" name="Type" value="<?= $product['Type']; ?>" required><br><br>

        <label for="Origin">Product Origin:</label><br>
        <input type="text" name="Origin" value="<?= $product['Origin']; ?>" required><br><br>

        <label for="Details">Description:</label><br>
        <textarea name="Details" required><?= $product['Details']; ?></textarea><br><br>

        <label for="Price">Price:</label><br>
        <input type="number" name="Price" step="0.01" value="<?= $product['Price']; ?>" required><br><br>

        <label for="image">Product Image:</label><br>
        <input type="file" name="image" accept="image/*"><br><br>

        <img src="images/<?php echo $product['Picture']; ?>" width="156px" height="280px"><br><br> <!-- Display current image -->

        <input type="submit" name="update" value="Update Product">
    </form>
</div>

<?php include './Templates/footer.php'; ?>
</body>
</html>

<?php
// Close the database connection
mysqli_close($con);
?>
