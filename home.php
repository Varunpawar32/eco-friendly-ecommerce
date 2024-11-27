 <?php
 session_start();

if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit();
}
// Include database connection file
include_once 'config.php';

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con);
}

// Query to fetch products from the database
$sql = "SELECT * FROM products LIMIT 4"; // Changed table name to "Products"
$result = $con->query($sql);

?>


<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Document</title>
    <!-- <link rel="stylesheet" href="project\home page\style.css"> -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Spartan', sans-serif;
        }

        #header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 80px;
            background-color: #E3E6F3;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06);
        }

        #navbar {
            display: flex;
            align-items: center;
            justify-content: center;

        }

        #navbar li {
            list-style: none;
            padding: 0 20px;
        }

        #navbar li a {
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            font-family: 'spartan ', sans-serif;
            color: #1a1a1a;
            transition: 0.03s ease;
        }

        #navbar li a:hover {
            color: #088178;
        }

        .banner {
            width: 100%;
            max-width: 100%;
            overflow: hidden;
        }

        .banner img {
            width: 100%;
            display: none;
        }

        .links {
            text-decoration: none;
            display: flex;
            justify-content: center;
            background-color: white;
            border-radius: 50px;
            padding: 10px;
            margin: 10px;

        }

        footer {
            background-color: #111;
            padding: 20px 0;
            align-items: center;
        }

        .container {
            width: 100px;
            padding: 70px 30px 20px;
        }

        .links a {
            padding: 10px;
            background-color: white;
            /* margin: 2px; */
        }

        body {
            font-family: Arial, sans-serif;
        }


        /* CSS */
        .button-33 {
            background-color: #c2fbd7;
            border-radius: 100px;
            box-shadow: rgba(44, 187, 99, .2) 0 -25px 18px -14px inset, rgba(44, 187, 99, .15) 0 1px 2px, rgba(44, 187, 99, .15) 0 2px 4px, rgba(44, 187, 99, .15) 0 4px 8px, rgba(44, 187, 99, .15) 0 8px 16px, rgba(44, 187, 99, .15) 0 16px 32px;
            color: green;
            cursor: pointer;
            display: inline-block;
            font-family: CerebriSans-Regular, -apple-system, system-ui, Roboto, sans-serif;
            padding: 7px 20px;
            text-align: center;
            text-decoration: none;
            transition: all 250ms;
            border: 0;
            font-size: 26px;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }

        .button-33:hover {
            box-shadow: rgba(44, 187, 99, .35) 0 -25px 18px -14px inset, rgba(44, 187, 99, .25) 0 1px 2px, rgba(44, 187, 99, .25) 0 2px 4px, rgba(44, 187, 99, .25) 0 4px 8px, rgba(44, 187, 99, .25) 0 8px 16px, rgba(44, 187, 99, .25) 0 16px 32px;
            transform: scale(1.01)
        }

        footer {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            position: relative;
            left: 0;
            bottom: 0;
            width: 100%;
        }

        .footer-container {
            display: flex;
            justify-content: space-around;
            margin: 0 auto;
            max-width: 1200px;
        }

        .footer-section {
            flex: 1;
            margin: 0 20px;
        }

        .footer-section h3 {
            margin-bottom: 10px;
        }

        .footer-section p {
            margin-bottom: 10px;
        }

        .footer-bottom {
            text-align: center;
            padding: 10px 0;
            border-top: 1px solid #fff;
        }

        .social-link {
            color: #fff;
            text-decoration: none;
            margin-right: 10px;
        }

        .social-link:hover {
            color: #ccc;
        }

        .product-categories {
            background-color: #f2f2f2;
            padding: 20px;
        }

        .product-categories h2 {
            margin-top: 0;
        }

        .product-categories ul {
            list-style-type: none;
            margin-left: 3px;
            padding: 0;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .product-categories li {
            flex-basis: calc(20% - 10px);
            margin-bottom: 10px;
        }

        .product-categories a {
            display: block;
            background-color: #ddd;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            color: #333;
        }

        .product-categories a:hover {
            background-color: #eee;
            text-align: center;
  
            /* transition: border-color 0.3s ease; */
        }
        .headerfooter{
            text-align: center;
  
        }

        .slideshow {
    width: 100%;
    height: 400px;
    overflow: hidden;
    position: relative;
    overflow: auto;
  }

  .slideshow::-webkit-scrollbar {
    display: none;
  }

  .slide-container {
    width: 300%;
    height: 100%;
    transition: transform 0.5s ease-in-out;
  }

  .slide {
    width: 100%;
    height: 100%;
    float: left;
  }

  .slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .prev-btn,
  .next-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    font-size: 18px;
  }

  .prev-btn {
    left: 0;
  }

  .next-btn {
    right: 0;
  }

   /* Custom CSS for product cards  */
   .product-card {
        border: 1px solid #ccc; /* Add border */
        border-radius: 10px; /* Add border radius */
        overflow: hidden; /* Ensure content does not overflow */
        transition: box-shadow 0.3s ease; /* Add transition effect */
    }

    .product-card:hover {
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add box shadow on hover */
    }

    .product-card img {
        transition: transform 0.3s ease; /* Add transition effect to image */
    }

    .product-card:hover img {
        transform: scale(1.05); /* Scale up image on hover */
    }

    .product-card .card-body {
        padding: 20px; /* Add padding */
    }
    </style>
</head>

<body>
    <section id="header">
        <a href="index.html"><img src="img/logo1.png" alt="" style="
        height: 41px;
        width: 124px; "></a></li>
        <div>
            <ul id="navbar">
                <li><a href="">Home</a></li>
                <li><a href="../user/home.php">shop</a></li>
                <li><a href="cart.php">cart</a></li>
                <li><a href="myorder.php">My order</a></li>
                <li><a href="blog.php">blog</a></li>
                <li><a href="aboutus.php">about us </a></li>
                <li><a href="login/logout.php">Logout</a></li>

            </ul>
        </div>

    </section>
   

<!-- coursal slide running on background -->
<div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="img/img2.jpg" height="500" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="img/img2.jpg" height="500" class="d-block w-100" alt="...">
    </div>
    <div class="carousel-item">
      <img src="img/img2.jpg"  height="500" class="d-block w-100" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>


    <section class="product-categories">
        <br>
        <h2 class="headerfooter">Shop by Category</h2>
        <br> <br>
        <ul>
            <!-- <li><a href="#">Tower Farms</a></li> -->
            <li><a href="user/home.php">Home essential</a></li>
            <li><a href="user/fashion.php">Fashion</a></li>
            <li><a href="user/beauty.php">Gifts</a></li>
            <li><a href="#">catogries</a></li>
        </ul>
    </section><br><br>
    <h2 class="text-center fw-bold mb-4">Latest Products</h2>
    <div class="row row-cols-1 row-cols-md-4 mx-3">
    <?php
    // Display products in card format
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
    ?>
        <div class="col mb-4 justify-content-center">
            <!-- Example product card -->
            <a href="productview.php?id=<?php echo $row['product_id']; ?>" class="card-link">
                <div class="card h-100 product-card"> <!-- Add product-card class -->
                    <img src="<?php echo $row['image']; ?>" class="card-img-top" height="250" alt="Product Image"> </a>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['name']; ?></h5>
                        <p class="card-text"><?php echo $row['description']; ?></p>
                        <p class="card-text">Rs <?php echo $row['price']; ?></p>
                        <a  href="cart.php?id=<?php echo $row['product_id']; ?>" class="btn btn-warning ">Add to Cart</a>
                    </div>
                </div>
            
        </div>
    <?php
        }
    } else {
        echo "No products found";
    }
    $con->close();
    ?>
</div>

</body>
<footer>
    <section id="foot">
        <div class="foot">

        </div>
    </section>
    <script>
        // Auto change banner images every 3 seconds
        let currentImageIndex = 0;
        const bannerImages = document.querySelectorAll('.banner-img');

        function showNextImage() {
            bannerImages[currentImageIndex].style.display = 'none';
            currentImageIndex = (currentImageIndex + 1) % bannerImages.length;
            bannerImages[currentImageIndex].style.display = 'block';
        }

        setInterval(showNextImage, 5000);
    </script>
    <footer>
        <div class="footer-container">
            <div class="footer-section">
                <h3>About Us</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero.</p>
            </div>
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p>Email: balu662004@gmail.com</p>
                <p>Phone: 9987676937</p>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <a href="#" class="social-link"><i class="fa-brands fa-facebook"></i></a>
                <a href="#" class="social-link"><i class="fa-brands fa-twitter"></i></a>
                <a href="#" class="social-link"><i class="fa-brands fa-google"></i></a>
                <a href="#" class="social-link"><i class="fa-brands fa-youtube"></i></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="copyright">Copyright &copy; 2024 balkrishna parab</p>
        </div>
    </footer>

</footer>

</html>
