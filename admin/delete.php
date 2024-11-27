<?php

echo $id=$_GET['ID'];
include'../login/config.php';
mysqli_query($con,"DELETE FROM `products` WHERE product_id=$id");
header("location:product.php");
?>