<?php
session_start();
if (!isset($_SESSION["id"])){
    header("Location: login.php");
    exit(); // Add exit() after header redirect to stop further execution
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Store the payment method in the session
    if(isset($_POST['payment_method']) && !empty($_POST['payment_method'])) {
        $_SESSION['payment_method'] = $_POST['payment_method'];
        header("Location: checkout.php");
        exit();
    } else {
        $error_message = "Please select a payment method.";
    }
}
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

        h1, h2, h3 {
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

        .error {
            color: red;
        }
    </style>
</head>
<body>
   <div class="container mt-5">
    <h1 class="mb-4">Checkout</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class="row">
            <div class="col-md-6">
                <h2>Payment Information</h2>
                <div class="form-group">
                    <h3>Choose Payment Method</h3>
                    <select class="form-control" name="payment_method" id="payment_method">
                        <option value="">Select Mode</option>
                        <option value="Credit card">Credit Card</option>
                        <option value="Debit card">Debit Card</option>
                        <option value="UPI">UPI</option>
                        <option value="Cash on delivery">Cash on Delivery</option>
                    </select>
                    <?php if(isset($error_message)) { ?>
                        <p class="error"><?php echo $error_message; ?></p>
                    <?php } ?>
                </div>
                <div id="payment_form">
                    <!-- Payment method forms will be displayed here -->
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Pay</button>
    </form>
</div>

<script>
    document.getElementById('payment_method').addEventListener('change', function() {
        var paymentMethod = this.value;
        var paymentFormDiv = document.getElementById('payment_form');
        paymentFormDiv.innerHTML = ''; // Clear previous form content

        // Generate and append payment method form based on selection
        if (paymentMethod === 'Credit card') {
            paymentFormDiv.innerHTML = `
                <h3>Credit Card Details</h3>
                <div class="form-group">
            <label>Card Number:</label>
            <input type="text" class="form-control" name="card_number" id="cardNumber">
            <div class="error-message" id="cardNumberError"></div>
        </div>
        <div class="form-group">
            <label>Expiry Date:</label>
            <input type="text" class="form-control" name="expiry_date" id="expiryDate">
            <div class="error-message" id="expiryDateError"></div>
        </div>
        <div class="form-group">
            <label>CVV:</label>
            <input type="text" class="form-control" name="cvv" id="cvv">
            <div class="error-message" id="cvvError"></div>
        </div>`;
        } else if (paymentMethod === 'Debit card') {
            paymentFormDiv.innerHTML = `
                <h3>Debit Card Details</h3>
                <div class="form-group">
                    <label>Card Number:</label>
                    <input type="text" class="form-control" name="card_number">
                </div>
                <div class="form-group">
                    <label>Expiry Date:</label>
                    <input type="text" class="form-control" name="expiry_date">
                </div>
                <div class="form-group">
                    <label>CVV:</label>
                    <input type="text" class="form-control" name="cvv">
                </div>
            `;
        } else if (paymentMethod === 'UPI') {
            paymentFormDiv.innerHTML = `
                <h3>UPI Details</h3>
                <div class="form-group">
                    <label>UPI ID:</label>
                    <input type="text" class="form-control" name="upi_id">
                </div>
            `;
        }
        // No additional form fields needed for Cash on Delivery
    });
</script>


<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
