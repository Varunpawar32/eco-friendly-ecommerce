<?php
session_start();
if($_SERVER["REQUEST_METHOD"]=="POST"){
  $username = $_POST["username"];
  $password = $_POST["password"]; 

 require_once("config.php");
  // validate login 

  $query = "SELECT * FROM admin WHERE username='$username' AND password ='$password' ";
  $result = mysqli_query($con, $query);
  if ($result-> num_rows == 1){
    // login success 
    $_SESSION["username"] = $username;
    $_SESSION["loggedin"] = true;
    header("Location:../dashboard.php");
    exit();
}
else{
  // login failed 
  $error_message = "Username or password is incorrect. Please try again.";
  
}

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Admin Login</h2>
        <form action="adminlogin.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>

</body>
</html>
