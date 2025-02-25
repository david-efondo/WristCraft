<?php
session_start();
require_once './data/dbconnect.php';
include './ultility/userultilities.php';
include './ultility/orderultilities.php';

// Check if the 'id' parameter is passed in the URL
if (isset($_GET['Id'])) {
    $userId = $_GET['Id'];

    // Fetch the user details from the database using a prepared statement
    $sql = "SELECT * FROM `users` WHERE Id = ?";
    if ($stmt = mysqli_prepare($con, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) == 0) {
            // If no user is found, show an error message
            die("User not found.");
        }

        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
} else {
    die("No user ID provided.");
}

// Handle the form submission for updating the user
if (isset($_POST['update'])) {
    $email = mysqli_real_escape_string($con, $_POST['Email']);
    $admin = isset($_POST['Admin']) ? 1 : 0; // Handle checkbox
    $name = mysqli_real_escape_string($con, $_POST['Name']);
    $sex = mysqli_real_escape_string($con, $_POST['Sex']);
    $dob = mysqli_real_escape_string($con, $_POST['DoB']);
    $address = mysqli_real_escape_string($con, $_POST['Address']);
    $phone = mysqli_real_escape_string($con, $_POST['Phone']);

    // Update the user details in the database using a prepared statement
    $sqlUpdate = "UPDATE `users` SET
                  Email = ?, 
                  Admin = ?, 
                  Name = ?, 
                  Sex = ?, 
                  DoB = ?, 
                  Address = ?, 
                  Phone = ? 
                  WHERE Id = ?";
    
    if ($stmt = mysqli_prepare($con, $sqlUpdate)) {
        mysqli_stmt_bind_param($stmt, "sisssssi", $email, $admin, $name, $sex, $dob, $address, $phone, $userId);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "User updated successfully!";
            header("Location: usersmanager.php"); // Redirect after successful update
            exit;
        } else {
            echo "Error: " . mysqli_error($con);
        }
        
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
        margin: 50px;
        padding: 0;
    }

    /* Container to center content */
    .container {
        margin: 0 auto;
        padding: 20px;
        background-color: solid silver; /* Darker container background */
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow for contrast */
    }

    /* Title styling */
    h2 {
        font-size: 30px;
        text-align: center;
        color: white; /* Green for the title */
    }

    /* Form styling */
    form {
        display: flex;
        flex-wrap: wrap;
        flex-direction: column;
    }

    label {
        font-size: 15px;
        font-weight: bold;
        margin-bottom: 8px;
        color: #E0E0E0; /* Light text for labels */
    }

    input[type="email"],
    input[type="text"],
    input[type="date"],
    textarea,
    input[type="tel"],
    select {
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

    input[type="reset"] {
        margin-top: 10px;
        padding: 10px;
        background-color:blue; /* Green button */
        color: white;
        border: none;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    input[type="submit"]:hover {
        background-color: darkgreen; /* Darker green on hover */
    }

    input[type="reset"]:hover {
        background-color: darkblue; /* Darker green on hover */
    }

    button {
        margin-top: 10px;
        padding: 10px;
        background-color: red; /* Red button */
        color: white;
        border: none;
        font-size: 16px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: darkred;
    }
    </style>
</head>

<body>
    <button type="button" onclick="window.location.href='usersmanager.php';">Back</button>
    <h2>Edit User</h2>

    <?php if (isset($user)): ?>
        <!-- Edit User Form -->
        <form method="POST" action="">
            <label for="Email">Email:</label><br>
            <input type="email" name="Email" value="<?= $user['Email']; ?>" required><br><br>

            <label for="Admin">Admin:</label><br>
            <select name="Admin" required>
                <option value="1" <?= $user['Admin'] ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?= !$user['Admin'] ? 'selected' : ''; ?>>No</option>
            </select><br><br>

            <label for="Name">Name:</label><br>
            <input type="text" name="Name" value="<?= $user['Name']; ?>" required><br><br>

            <label for="Sex">Sex:</label><br>
            <select name="Sex" required>
                <option value="Male" <?= $user['Sex'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?= $user['Sex'] == 'Female' ? 'selected' : ''; ?>>Female</option>
            </select><br><br>

            <label for="DoB">Date of Birth:</label><br>
            <input type="date" name="DoB" value="<?= $user['DoB']; ?>" required><br><br>

            <label for="Address">Address:</label><br>
            <textarea name="Address" required><?= $user['Address']; ?></textarea><br><br>

            <label for="Phone">Phone:</label><br>
            <input type="tel" name="Phone" value="<?= $user['Phone']; ?>" required><br><br>

            <input type="submit" name="update" value="Update User">
            <input type="reset" name="reset" value="Reset Details">
            <button type="button" onclick="window.location.href='usersmanager.php';">Back</button>
        </form>
    <?php else: ?>
        <p>User not found. Please ensure the ID is correct.</p>
    <?php endif; ?>

    <?php include './Templates/footer.php'; ?>

</body>
</html>

<?php
// Close the database connection
mysqli_close($con);
?>