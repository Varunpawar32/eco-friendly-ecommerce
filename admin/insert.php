<?php 


if (isset($_POST['upload'])){
    include'../login/config.php';
    $product_name= $_POST['pname'];
    $product_des= $_POST['pdes'];
    $product_price=$_POST['pprice'];
    $product_image= $_FILES['pimg'];
    $img_location =$_FILES['pimg']['tmp_name'];
    $img_name = $_FILES['pimg']['name'];
    $img_des="uploaded/".$img_name;
    move_uploaded_file($img_location,"uploaded/".$img_name);
    $product_category=$_POST['pcat'];

    // insert product query 
    mysqli_query($con,"INSERT INTO `product`( `pname`, `pdes`, `pprice`, `pimg`, `pcat`)
     VALUES ('$product_name','$product_des','$product_price','$img_des','$product_category')");
     header("location:product.php");

}
?>