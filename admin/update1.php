<?php
if (isset($_POST['update'])) {
    include '../login/config.php';

    // Get form data
    $id = $_POST['id'];
    $product_name = $_POST['pname'];
    $product_des = $_POST['pdes'];
    $product_price = $_POST['pprice'];
    $product_image = $_FILES['pimg'];
    $img_location = $_FILES['pimg']['tmp_name'];
    $img_name = $_FILES['pimg']['name'];
    $img_des = "../uploaded/" . $img_name;
    move_uploaded_file($img_location, "uploaded/" . $img_name);
    $product_category = $_POST['pcat'];

    // Update product in the database
    $sql = "UPDATE products 
            SET `name`='$product_name', `description`='$product_des', `price`='$product_price', `image`='$img_des', `category`='$product_category' 
            WHERE product_id=$id";

    if (mysqli_query($con, $sql)) {
        header("location: product.php"); // Redirect to product page after successful update
    } else {
        echo "Error updating product: " . mysqli_error($con);
    }
}
?>
