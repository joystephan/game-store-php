
<?php
require_once './includes/functions.php';

$products = getAllProducts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Store</title>
    <link rel="stylesheet" href="./assets/css/style.css">

</head>
<body>
    <nav>
        <ul class="nav-left">
            <li><a href="#about">About Us</a></li> <!-- Link to About Us section -->
            <li><a href="pages/cart.php">Cart</a></li>
            <li><a href="pages/login.php">Login</a></li>
            <li><a href="pages/register.php">Register</a></li>
        </ul>
        <!-- Search Bar -->
        <form class="search-bar" action="search.php" method="GET">
            <input type="text" name="query" placeholder="Search games..." required>
            <button type="submit">Search</button>
        </form>
    </nav>
    
    <!-- Hero section with the featured image -->
    <section class="hero">
        <img src="assets/gaming.jpeg" alt="Featured Gaming image" class="hero-image">
    </section>

    <div class="hero-content">
        <h1>Welcome to Your Ultimate Gaming Destination Where Gamers Unite!</h1>
        <p>Explore the best selection of games, accessories, and tech. Whether you're a casual player or a hardcore gamer, we have everything you need to enhance your gaming experience. Get ready to level up your adventure!</p>
        <a href="#shop-now" class="btn">Shop Now</a>
   </div>


    <!-- About Us Section -->
    <section id="about">
        <h2>About Us</h2>
        <p>Welcome to Gaming Store! Your one-stop destination for the best games, gaming accessories, and merchandise. We are dedicated to providing gamers with the ultimate experience and top-notch customer service.</p>
    </section>

    
    <!-- Products Section -->
    <section id="shop-now" class="products">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                <a href="pages/product.php?id=<?php echo $product['product_id']; ?>">View Details</a>
            </div>
        <?php endforeach; ?>
    </section>

    <script src="script.js"></script>
</body>
</html> 