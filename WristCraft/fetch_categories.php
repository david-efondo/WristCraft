<?php
require_once './data/dbconnect.php';

$sql = "SELECT DISTINCT Name FROM types";
$result = mysqli_query($con, $sql);

$categories = array();
while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}

echo json_encode($categories);

mysqli_close($con);
?>
