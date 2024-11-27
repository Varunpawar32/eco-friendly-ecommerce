<?php

require_once "config.php";
$username = $password = $confirm_password = $email = $full_name = $address = $phone_number = "";
$username_err = $password_err = $confirm_password_err = $email_err = $full_name_err = $address_err = $phone_number_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    // Validate username
    if (empty(trim($_POST['username']))) {
        $username_err = "Username cannot be blank";
    } else {
        $sql = "SELECT user_id FROM Users WHERE username = ?";
        $stmt = mysqli_prepare($con, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $param_username);
            $param_username = trim($_POST['username']);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken";
                } else {
                    $username = trim($_POST['username']);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        mysqli_stmt_close($stmt);
    }

    // Validate email
    if (empty(trim($_POST['email']))) {
        $email_err = "Email cannot be blank";
    } else {
        $email = trim($_POST['email']);
    }

    // Validate password
    if (empty(trim($_POST['password']))) {
        $password_err = "Password cannot be blank";
    } elseif (strlen(trim($_POST['password'])) < 8) {
        $password_err = "Password must have at least 8 characters";
    } elseif (!preg_match("/[a-z]/", $_POST['password']) || !preg_match("/[A-Z]/", $_POST['password']) || !preg_match("/[0-9]/", $_POST['password']) || !preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $_POST['password'])) {
        $password_err = "Password must include at least one lowercase letter, one uppercase letter, one digit, and one special character";
    } else {
        $password = trim($_POST['password']);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match";
        }
    }

    // Check for full name
    if (empty(trim($_POST['full_name']))) {
        $full_name_err = "Full name cannot be blank";
    } else {
        $full_name = trim($_POST['full_name']);
    }

    // Check for address
    if (empty(trim($_POST['address']))) {
        $address_err = "Address cannot be blank";
    } else {
        $address = trim($_POST['address']);
    }

    // Check for phone number
    if (empty(trim($_POST['phone_number']))) {
        $phone_number_err = "Phone number cannot be blank";
    } else {
        $phone_number = trim($_POST['phone_number']);
    }

    // Check for errors before inserting into database
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($full_name_err) && empty($address_err) && empty($phone_number_err)) {
        $sql = "INSERT INTO Users (username, email, password, full_name, address, phone_number) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssss", $param_username, $param_email, $param_password, $param_full_name, $param_address, $param_phone_number);
            $param_username = $username;
            $param_email = $email;
            $param_password = $password;
            $param_full_name = $full_name;
            $param_address = $address;
            $param_phone_number = $phone_number;
            if (mysqli_stmt_execute($stmt)) {
                header("location: login.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f5f5f5;
        }

        .container {
            width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form div {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }

        span {
            color: red;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $username; ?>">
                <span><?php echo $username_err; ?></span>
            </div>
            <div <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="email" value="<?php echo $email; ?>">
                <span><?php echo $email_err; ?></span>
            </div>
            <div <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" value="<?php echo $password; ?>">
                <span><?php echo $password_err; ?></span>
            </div>
            <div <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>">
                <span><?php echo $confirm_password_err; ?></span>
            </div>
            <div <?php echo (!empty($full_name_err)) ? 'has-error' : ''; ?>">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?php echo $full_name; ?>">
                <span><?php echo $full_name_err; ?></span>
            </div>
            <div <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                <label>Address</label>
                <input type="text" name="address" value="<?php echo $address; ?>">
                <span><?php echo $address_err; ?></span>
            </div>
            <div <?php echo (!empty($phone_number_err)) ? 'has-error' : ''; ?>">
                <label>Phone Number</label>
                <input type="text" name="phone_number" value="<?php echo $phone_number; ?>">
                <span><?php echo $phone_number_err; ?></span>
            </div>
            <div>
                <input type="submit" value="Sign Up">
            </div>
        </form>
    </div>
</body>

</html>
