<?php
// Include database connection file
require_once 'config.php';

// Check if order_id is provided in the URL
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    echo "Order ID not provided.";
    exit();
}

// Retrieve order ID from the URL
$order_id = $_GET['order_id'];

// Fetch order details
$sql_order = "SELECT * FROM orders WHERE order_id = ?";
$stmt_order = $con->prepare($sql_order);
$stmt_order->bind_param("i", $order_id);
$stmt_order->execute();
$result_order = $stmt_order->get_result();

// Fetch order items
$sql_order_items = "SELECT * FROM order_items WHERE order_id = ?";
$stmt_order_items = $con->prepare($sql_order_items);
$stmt_order_items->bind_param("i", $order_id);
$stmt_order_items->execute();
$result_order_items = $stmt_order_items->get_result();

// Fetch payment details
$sql_payment = "SELECT * FROM payments WHERE order_id = ?";
$stmt_payment = $con->prepare($sql_payment);
$stmt_payment->bind_param("i", $order_id);
$stmt_payment->execute();
$result_payment = $stmt_payment->get_result();

// Check if order exists
if ($result_order->num_rows > 0) {
    $row_order = $result_order->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .product-img {
            max-width: 100px;
            max-height: 100px;
        }

        .order-details {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center">Order Details</h1>
        <div class="order-details">
            <h3>Order Information</h3>
            <p><strong>Order ID:</strong> <?php echo $row_order['order_id']; ?></p>
            <p><strong>Order Date:</strong> <?php echo $row_order['order_date']; ?></p>
            <p><strong>Shipping Address:</strong> <?php echo $row_order['shipping_address']; ?></p>
            <p><strong>Total Amount:</strong> <?php echo "Rs " . $row_order['total_amount']; ?></p>
        </div>

        <div class="order-details">
            <h3>Order Items</h3>
            <ul class="list-group">
                <?php
                // Display order items
                if ($result_order_items->num_rows > 0) {
                    while ($row_order_item = $result_order_items->fetch_assoc()) {
                        // Fetch product details
                        $sql_product = "SELECT name, description, image FROM products WHERE product_id = ?";
                        $stmt_product = $con->prepare($sql_product);
                        $stmt_product->bind_param("i", $row_order_item['product_id']);
                        $stmt_product->execute();
                        $result_product = $stmt_product->get_result();

                        if ($result_product->num_rows > 0) {
                            $row_product = $result_product->fetch_assoc();
                            echo "<li class='list-group-item'>";
                            echo "<div class='row'>";
                            echo "<div class='col-md-3'><img src='{$row_product['image']}' class='product-img img-fluid'></div>";
                            echo "<div class='col-md-9'>";
                            echo "<h5>{$row_product['name']}</h5>";
                            echo "<p>{$row_product['description']}</p>";
                            echo "<p>Quantity: {$row_order_item['quantity']}</p>";
                            echo "<p>price per product: Rs {$row_order_item['price']}</p>";
                            echo "<p>Total price for this product: Rs " . ($row_order_item['price'] * $row_order_item['quantity']) . "</p>";
                            echo "</div>";
                            echo "</div>";
                            echo "</li>";
                        }
                    }
                } else {
                    echo "<li class='list-group-item'>No order items found.</li>";
                }
                ?>
            </ul>
        </div>

        <div class="order-details">
            <h3>Payment Information</h3>
            <?php
            // Display payment details
            if ($result_payment->num_rows > 0) {
                $row_payment = $result_payment->fetch_assoc();
            ?>
                <p><strong>Payment Method:</strong> <?php echo $row_payment['payment_method']; ?></p>
                <p><strong>Amount :</strong> <?php echo "Rs " . $row_payment['amount']; ?></p>
                <p><strong>Payment Date:</strong> <?php echo $row_payment['payment_date']; ?></p>
            <?php } else {
                echo "<p>No payment information found.</p>";
            } ?>
        </div>
        <div class="btn-group" role="group" aria-label="Order Actions">
                          <?php
                    // Check if the order is not canceled
                    if ($row_order['status'] != 'canceled') {
                        // Calculate time difference between order date and current date
                        $order_date = strtotime($row_order['order_date']);
                        $current_date = time();
                        $time_difference = $current_date - $order_date;
                        $hours_difference = round($time_difference / (60 * 60));

                        // Show cancel button if the order is less than 48 hours old
                        if ($hours_difference < 48) {
                            ?>
                            <a href="cancel.php?order_id=<?php echo $row_order['order_id']; ?>" class="btn btn-danger">Cancel Order</a>
                            <?php
                        }
                    }
                    ?>
                        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<?php
} else {
    echo "Order not found.";
}

// Close prepared statements
$stmt_order->close();
$stmt_order_items->close();
$stmt_payment->close();

// Close database connection
$con->close();
?>
