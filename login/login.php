<?php
session_start();

if (isset($_SESSION["id"])) {
    header("Location: ../home.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    require_once("config.php");

    // Validate login
    $query = "SELECT * FROM users WHERE username=? AND password=?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        // Query execution failed
        die("Query failed: " . mysqli_error($con));
    }

    if (mysqli_num_rows($result) == 1) {
        // Fetch user ID
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['user_id'];

        // Store user ID in session
        $_SESSION['id'] = $user_id;
        $_SESSION['password'] = $password;

        // Close the database connection
        mysqli_stmt_close($stmt);
        mysqli_close($con);

        // Display an alert message and redirect to home.php
        echo '<script>alert("Login successful!"); window.location.href = "../home.php";</script>';
        exit();
    } else {
        // Login failed
        $error_message = "Username or password is incorrect. Please try again.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .card {
            width: 350px;
            background-color: rgb(255, 255, 255);
            overflow: hidden;
            border: 2px solid;
            border-radius: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
            padding: 30px;
            gap: 10px;
        }

        input {
            padding: 15px;
            border: none;
        }

        input:focus {
            outline: none;
            border-color: #3498db;
        }

        h1,
        h5,
        .button {
            text-align: center;
            text-decoration: none;
            font-weight: 700;
        }

        .forget {
            float: right;
        }

        p,
        a {
            font-size: 12px;
            text-decoration: none;
        }

        .button {
            background-color: #f0c90b;
            padding: 10px;
            color: black;
        }

        .button:hover {
            background-color: green;
            padding: 10px;
            color: white;
        }

        @media (max-width: 450px) {
            .card {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <form action="login.php" method="post">
            <h1>Welcome!</h1>
            <?php if (isset($error_message)) {
                echo '<p style="color: red;">' . $error_message . '</p>';
            } ?>
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <input class="button" type="submit" value="Login">
            <p><a style="float:left" href="adminlogin.php">Admin login</a> <a class="forget" href="#">Forgot password?</a></p>
            <h5>Create account?<a href="signup.php">Sign up</a></h5>
        </form>
    </div>
</div>
</body>
</html>
