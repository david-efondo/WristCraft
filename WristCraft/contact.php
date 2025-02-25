<?php

require_once './data/dbconnect.php';
include 'ultility/userultilities.php';
session_start();

function getCountProducts() {
    return isset($_SESSION['GuestCarts']) ? count($_SESSION['GuestCarts']) : 0;
}

// Default values for guest users
$user = [
    'Name' => '',
    'Email' => ''
];

// If logged in, fetch user data
if (isset($_SESSION['userId']) && is_numeric($_SESSION['userId'])) {
    $user = getUserById($con, $_SESSION['userId']);
}

// Default values for feedback form
$name = $user['Name'] ?? "";
$email = $user['Email'] ?? "";
$title = "";
$details = "";

// Handling form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['txtName'])) {
    $name = $_POST['txtName'];
    $email = $_POST['txtEmail'];
    $title = $_POST['txtTitle'];
    $details = $_POST['txtDetails'];

    // Secure SQL statement (Prevents SQL Injection)
    $stmt = mysqli_prepare($con, "INSERT INTO feedbacks (`Name`, `Email`, `Title`, `Details`, `CreateTime`) VALUES (?, ?, ?, ?, CURRENT_TIME())");
    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $title, $details);
    
    if (mysqli_stmt_execute($stmt)) {
        $message = "Feedback response successful.";
    } else {
        $message = "Feedback response failed.";
    }
    mysqli_stmt_close($stmt);
}

?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Contact</title>
        <link href="styles/site.css" rel="stylesheet" type="text/css">
        <link href="styles/contact.css" rel="stylesheet" type="text/css">
        <script type="text/javascript" src="javascript.js"></script>
    </head>

    <body>
        <?php include './Templates/header.php'; ?>
        <?php include './Templates/topmenu.php'; ?>
       
        <div class="divContainer">
            <div class="divWrapper_2">
                <div class="divMain">
                    <article class="articleContent">
                        <p class="pPageTitle">Contact</p>

                        <script language="javascript">
                            ShowContact();
                        </script>

                        <section id="Contact">
                            <p>Company Name: <span id="CompanyName"></span> </p>
                            <p>Address: <span id="Address"></span></p>
                            <p>Phone Number: <span id="PhoneNumber"></span></p>
                            <p>Email: <span id="Email"></span></p>
                        </section>

                        <section id="sectionFeedback">
                            <p class="pPageTitle">Feedback</p>

                            <?php if (isset($message)) : ?>
                                <p class="pResultFeedback"><?= htmlspecialchars($message) ?></p>
                            <?php else : ?>
                                <form id="form1" name="formFeedback" method="post" action="#">
                                    <table class="tableFeedback" cellpadding="4">
                                        <tr>
                                            <th style="text-align: left" scope="row">Name:</th>
                                            <td style="text-align: left">
                                                <input name="txtName" type="text" value="<?= htmlspecialchars($name) ?>" id="txtName" size="30" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="text-align: left" scope="row">Email:</th>
                                            <td style="text-align: left">
                                                <input name="txtEmail" type="email" value="<?= htmlspecialchars($email) ?>" id="txtEmail" size="30" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="text-align: left" scope="row">Title:</th>
                                            <td style="text-align: left">
                                                <input name="txtTitle" type="text" id="txtTitle" size="30" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="text-align: left" scope="row" valign="top">Content</th>
                                            <td>
                                                <textarea name="txtDetails" cols="30" rows="7" id="txtDetails" required></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <td>
                                                <input type="submit" value="Send Feedback"/>
                                                &nbsp;
                                                <input type="reset" value="Clear"/>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            <?php endif; ?>
                        </section>

                    </article>
                </div>
            </div>
        </div>

        <?php include './Templates/footer.php'; ?>
    </body>
</html>
