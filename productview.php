<?php
// Include database connection file
include_once 'login/config.php';

// Check if ID is provided in the URL
if(isset($_GET['id'])) {
    // Sanitize the ID to prevent SQL injection
    $id = mysqli_real_escape_string($con, $_GET['id']);
    
    // Fetch product data from the database based on the ID
    $sql = "SELECT * FROM products WHERE product_id = $id";
    $result = mysqli_query($con, $sql);
    $product = mysqli_fetch_assoc($result);

    // Fetch similar products with the same category
    $category = $product['category'];
    $similarProductsSql = "SELECT * FROM products WHERE category = '$category' AND product_id != $id LIMIT 4";
    $similarProductsResult = mysqli_query($con, $similarProductsSql);
    $similarProducts = mysqli_fetch_all($similarProductsResult, MYSQLI_ASSOC);
} else {
    // Redirect to homepage if ID is not provided
    header("Location: login/welcome.php");
    exit();
}

// Close database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']; ?> - Product Detail</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .product-img {
            max-width: 100%;
            height: auto;
        }

        .product-info {
            margin-top: 20px;
        }

        .product-info h2 {
            color: #333;
        }

        .product-info p {
            color: #555;
        }

        .similar-products {
            margin-top: 40px;
        }

        .similar-products h3 {
            margin-bottom: 20px;
            color: #333;
        }

        .similar-product {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #fff;
        }

        .similar-product img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .similar-product h4 {
            color: #333;
        }

        .similar-product p {
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-img">
            </div>
            <div class="col-md-6 product-info">
                <h2><?php echo $product['name']; ?></h2>
                <p><strong>Price:</strong> Rs <?php echo $product['price']; ?></p>
                <p><strong>Description:</strong> <?php echo $product['description']; ?></p>
                <a  href="cart.php?id=<?php echo $product['product_id']; ?>" class="btn btn-warning ">Add to Cart</a>
                    </div>
        </div>

        <div class="similar-products">
            <h3>Similar Products</h3>
            <div class="row">
                <?php foreach ($similarProducts as $similarProduct): ?>
                    <div class="col-md-3 similar-product">
                        <img src="<?php echo $similarProduct['image']; ?>" alt="<?php echo $similarProduct['name']; ?>">
                        <h4><?php echo $similarProduct['name']; ?></h4>
                        <p>Price: Rs <?php echo $similarProduct['price']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
