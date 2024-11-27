<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit(); // Add exit() after header redirect to stop further execution
}

// Include database connection file
require_once '../config.php';

// Check if the form is submitted
if (isset($_POST['upload'])) {
    // Get form data
    $productName = $_POST['pname'];
    $productDescription = $_POST['pdes'];
    $productPrice = $_POST['pprice'];
    $productCategory = $_POST['pcat'];

    // Check if a file is selected
    if (isset($_FILES['pimg']) && $_FILES['pimg']['error'] === UPLOAD_ERR_OK) {
        // Retrieve file details
        $fileTmpPath = $_FILES['pimg']['tmp_name'];
        $fileName = $_FILES['pimg']['name'];
        $fileSize = $_FILES['pimg']['size'];
        $fileType = $_FILES['pimg']['type'];

        // Check if file size is less than 5MB
        if ($fileSize > 5242880) { // 5MB in bytes
            $errorMsg = "File size exceeds 5MB limit.";
        } else {
            // Move uploaded file to a temporary location
            $uploadDir = '../uploads/';
            $uploadPath = $uploadDir . basename($fileName);
            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                // Insert product data into the database
                $sql = "INSERT INTO products (name, description, price, image, category) VALUES (?, ?, ?, ?, ?)";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("ssdss", $productName, $productDescription, $productPrice, $uploadPath, $productCategory);
                if ($stmt->execute()) {
                    $successMsg = "Product added successfully.";
                } else {
                    $errorMsg = "Failed to add product.";
                }
                $stmt->close();
            } else {
                $errorMsg = "Failed to upload file.";
            }
        }
    } else {
        $errorMsg = "Please select a file to upload.";
    }
}

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = $con->query($sql);

// Check if there are any products
if ($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $products = [];
}

// Close database connection
$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
       /* body {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
      background-color: #f5f5f5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    } */

    h1 {
      text-align: center;
      color: #333;
    }

    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 400px;
      margin-top: 20px;
    }

    form {
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
      border: 2px solid #3498db;
      margin-top: 20px;
    }
    #form-heading{
        align-items: center;
        color: orange;
        padding-right: 30px;
    }
    label {
      display: block;
      margin-bottom: 8px;
      color: #333;
    }

    input,
    select {
      width: calc(100% - 20px);
      padding: 10px;
      margin-bottom: 16px;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
    }

    textarea {
      width: calc(100% - 20px);
      padding: 10px;
      margin-bottom: 16px;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
      resize: vertical;
    }

    select {
      appearance: none;
    }

    button {
      background-color: #2ecc71; /* Green button color */
      color: #fff;
      padding: 12px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      width: 100%;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #27ae60; /* Darker green on hover */
    }
    .operation:hover{
background-color: red;
    }
    </style>
</head>
<body>
<div class="container">
    <h1>Product Management</h1>
    <!-- Product upload form -->
    <form action="" method="POST" enctype="multipart/form-data">
        <h3><label id="form-heading">-----Product details----</label></h3>
        <label for="productName">Product Name:</label>
        <input type="text" id="productName" name="pname">

        <label for="productDescription">Product Description:</label>
        <textarea id="productDescription" name="pdes" rows="4"></textarea>

        <label for="productPrice">Product Price:</label>
        <input type="number" id="productPrice" name="pprice" step="0.01">

        <label for="productImage">Product Image:</label>
        <input type="file" id="productImage" name="pimg">

        <label for="productCategory">Product Category:</label>
        <select id="productCategory" name="pcat">
            <option value="Fashion">Fashion</option>
            <option value="Beauty and wellness">Beauty and wellness</option>
            <option value="Home">Home</option>
            <option value="Gift">Gift</option>
        </select>

        <button type="submit" name="upload" class="btn btn-primary">Add Product</button>
    </form>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div>
                <!-- Product list table -->
                <table class="table table-striped border my-5 w-900">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $product) { ?>
                        <tr>
                            <td><?php echo $product['product_id']; ?></td>
                            <td><?php echo $product['name']; ?></td>
                            <td><?php echo $product['description']; ?></td>
                            <td><?php echo "Rs " . $product['price']; ?></td>
                            <td><img src="<?php echo $product['image']; ?>" height="90px" width="200px"></td>
                            <td><?php echo $product['category']; ?></td>
                            <td>
                                <a href="update.php?ID=<?php echo $product['product_id']; ?>"><button class="btn btn-primary">Update</button></a>
                                <a href="delete.php?ID=<?php echo $product['product_id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');"><button class="btn btn-danger">Delete</button></a>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
