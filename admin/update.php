<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update product</title>

    <style>
         body {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
      background-color: #f5f5f5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    } 
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
    </style>
</head>
<body>

<?php
session_start();
include '../login/config.php';

// Check if the ID parameter is set in the URL
if (isset($_GET['ID'])) {
    // Validate and sanitize the ID parameter
    $id = intval($_GET['ID']);

    // Prepare and execute the SQL query
    $query = "SELECT * FROM products WHERE product_id=$id";
    $record = mysqli_query($con, $query);

    // Check if the query execution failed
    if (!$record) {
        die('Error fetching data: ' . mysqli_error($con));
    }

    // Fetch data from the result set
    $data = mysqli_fetch_array($record);
} else {
    // Handle case when ID parameter is missing
    die('ID parameter is missing in the URL.');
}

// Use $data array to populate the form or perform other actions
?>

<div class="container">
    <h1>Product Management</h1>
    <form action="update1.php" method="POST" enctype="multipart/form-data">
       <h3> <label id="form-heading">-----Update details----</label></h3>
      <label for="productName">Product Name:</label>
      <input type="text" id="productName" value="<?php echo $data['name']; ?>" name="pname">
      <input type="hidden" name="id" value="<?php echo $data['product_id']; ?>">
      <label for="productDescription">Product Description:</label>
      <textarea id="productDescription" name="pdes" rows="4"><?php echo $data['description']; ?></textarea>

      <label for="productPrice">Product Price:</label>
      <input type="number" id="productPrice" value="<?php echo $data['price']; ?>" name="pprice" step="0.01">

      <label for="productImage">Product Image URL:</label>
      <input type="file" id="productImage" name="pimg">
      <img src="<?php echo $data['image']; ?>" style="height:100px;" alt="">

      <label for="productCategory">Product Category:</label>
      <select id="productCategory" name="pcat">
        <option value="Fashion" <?php if ($data['category'] == "Fashion") echo "selected"; ?>>Fashion</option>
        <option value="Beauty and wellness" <?php if ($data['category'] == "Beauty and wellness") echo "selected"; ?>>Beauty and wellness</option>
        <option value="Home" <?php if ($data['category'] == "Home") echo "selected"; ?>>Home</option>
        <option value="Gift" <?php if ($data['category'] == "Gift") echo "selected"; ?>>Gift</option>
      </select>

      <button name="update">Update Product</button>
    </form>
  </div>

</body>
</html>
