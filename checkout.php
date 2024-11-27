<?php
session_start();
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit(); // Add exit() after header redirect to stop further execution
}

    // Retrieve shipping information
    $address = $_SESSION['shipping_address'];

    // Retrieve payment information
    $paymentMethod = $_SESSION['payment_method'];

    // Include database connection file
    require_once 'config.php';

    // Retrieve user ID
    $user_id = $_SESSION["id"];

    // Fetch cart IDs for the logged-in user with status 'not-buyed'
    $sql_cart_ids = "SELECT cart_id, product_id, quantity FROM cart WHERE user_id = ? ";
    $stmt_cart_ids = $con->prepare($sql_cart_ids);
    if ($stmt_cart_ids) {
        $stmt_cart_ids->bind_param("i", $user_id);
        $stmt_cart_ids->execute();
        $result_cart_ids = $stmt_cart_ids->get_result();

        // Initialize total amount
        $total_amount = 0;
        $total_price = $_SESSION['amount'] ;

        // Prepare statement for inserting order items
        $sql_order_item = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt_order_item = $con->prepare($sql_order_item);

        // Start a transaction
        $con->begin_transaction();

        // Insert data into the orders table
        $sql_order = "INSERT INTO orders (user_id, shipping_address ,total_amount) VALUES (?, ?, ?)";
        $stmt_order = $con->prepare($sql_order);
        $stmt_order->bind_param("isi", $user_id, $address, $total_price);

        if ($stmt_order->execute()) {
            // Retrieve the order ID of the inserted order
            $order_id = $stmt_order->insert_id;

            // Insert data into the order_item table for each cart item
            while ($row_cart_id = $result_cart_ids->fetch_assoc()) {
                $product_id = $row_cart_id['product_id'];
                $quantity = $row_cart_id['quantity'];

                // Fetch product price
                $sql_product = "SELECT price FROM products WHERE product_id = ?";
                $stmt_product = $con->prepare($sql_product);
                $stmt_product->bind_param("i", $product_id);
                if ($stmt_product->execute()) {
                    $result_product = $stmt_product->get_result();
                    if ($result_product->num_rows > 0) {
                        $row_product = $result_product->fetch_assoc();
                        $price = $row_product['price'];

                        // Calculate total amount
                        $total_amount += ($price * $quantity);

                        // Insert order item
                        $stmt_order_item->bind_param("iiid", $order_id, $product_id, $quantity, $price);
                        if (!$stmt_order_item->execute()) {
                            echo "Error inserting data into order_item table: " . $con->error;
                            $con->rollback();
                            exit();
                        }
                    } else {
                        echo "Error: Product with ID $product_id not found.";
                        $con->rollback();
                        exit();
                    }
                } else {
                    echo "Error executing product query: " . $con->error;
                    $con->rollback();
                    exit();
                }
            }

            // Close product statement
            $stmt_product->close();

            // Insert data into the payments table
            $sql_payment = "INSERT INTO payments (order_id, payment_method, amount) VALUES (?, ?, ?)";
            $stmt_payment = $con->prepare($sql_payment);
            $stmt_payment->bind_param("isd", $order_id, $paymentMethod, $total_amount);
            if ($stmt_payment->execute()) {
                // Commit the transaction
                $con->commit();

                // Delete cart items associated with the user
                $sql_delete_cart = "DELETE FROM cart WHERE user_id = ?";
                $stmt_delete_cart = $con->prepare($sql_delete_cart);
                $stmt_delete_cart->bind_param("i", $user_id);
                if ($stmt_delete_cart->execute()) {
                    echo "<script>alert('Your order has been placed successfully!');</script>";
                    header("Location: myorder.php");
                    exit();
                } else {
                    echo "Error deleting cart items: " . $con->error;
                }
            } else {
                echo "Error inserting data into payments table: " . $con->error;
            }
        } else {
            echo "Error inserting data into orders table: " . $con->error;
        }

        // Close statements
        $stmt_order->close();
        $stmt_payment->close();
        $stmt_order_item->close();
        $stmt_cart_ids->close();
    } else {
        echo "Error preparing statement: " . $con->error;
    }

    // Close database connection
    $con->close();

?>
