<?php
session_start();
if (!isset($_SESSION["id"])){
    header("Location: login.php");
    exit(); // Add exit() after header redirect to stop further execution
}

// Include database connection file
require_once 'config.php';

// Retrieve user ID
$user_id = $_SESSION["id"];

// Fetch user information from the user table
$sql_user = "SELECT full_name, phone_number, address FROM users WHERE user_id = ?";
$stmt_user = $con->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

// Store the total amount from the previous session
$total_amount = $_SESSION['amount'] ?? 0;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store either address or shipping address based on user input
    if (isset($_POST['shipping_address']) && !empty(trim($_POST['shipping_address']))) {
        $_SESSION['shipping_address'] = $_POST['shipping_address'];
    } else {
        $_SESSION['shipping_address'] = $_POST['address'];
    }

    // Redirect to the next page
    header("Location: payment.php");
    exit();
}

// Close statement
$stmt_user->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            color: #343a40;
        }

        label {
            color: #343a40;
            font-weight: bold;
        }

        .form-control {
            margin-bottom: 20px;
        }

        button {
            background-color: #007bff;
            color: #ffffff;
            border: none;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
   <div class="container mt-5">
    <h1 class="mb-4">Checkout</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" action="">
        <div class="row">
            <div class="col-md-6">
                <h2>Shipping Information</h2>
                <?php if ($row_user = $result_user->fetch_assoc()) : ?>
                <div class="form-group">
                    <label>Full Name:</label>
                    <input type="text" class="form-control" name="fullname" value="<?php echo $row_user['full_name']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Phone Number:</label>
                    <input type="tel" class="form-control" name="phone" value="<?php echo $row_user['phone_number']; ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Address:</label>
                    <textarea class="form-control" name="address" readonly><?php echo $row_user['address']; ?></textarea>
                </div>
                <?php endif; ?>
                <div class="form-group">
                    <label>Use the same address for shipping?</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="same_address_checkbox" name="same_address_checkbox" onchange="showShippingAddress()">
                        <label class="form-check-label" for="same_address_checkbox">Yes</label>
                    </div>
                </div>
                <div class="form-group" id="shipping_address_group" style="display: block;">
                    <label>Shipping Address:</label>
                    <textarea class="form-control" name="shipping_address"  id="shippingAddressTextarea"required ></textarea>
                </div>
                <div class="form-group">
                    <label>Total Amount:</label>
                    <input type="text" class="form-control" name="total_amount" value="<?php echo $total_amount; ?>" readonly>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Proceed For Payment</button>
    </form>
</div>

<script>
    function showShippingAddress() {
    var checkbox = document.getElementById('same_address_checkbox');
    var shippingAddressGroup = document.getElementById('shipping_address_group');
    
    if (checkbox.checked) {
        shippingAddressGroup.style.display = 'none';
        shippingAddressTextarea.removeAttribute('required');
    } else {
        shippingAddressGroup.style.display = 'block';
        shippingAddressTextarea.setAttribute('required', '');
    }
}
</script>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
