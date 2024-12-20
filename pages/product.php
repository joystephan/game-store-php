<?php
require_once '../includes/config.php';

// Fetch product details based on the ID in the URL
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // Prepare and execute the SQL query
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = :product_id");

    if ($stmt) {
        // Bind the product_id parameter to the prepared statement
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Get the result and fetch the product data
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            die("Product not found.");
        }
    } else {
        die("Database query failed: " . $conn->errorInfo());
    }
} else {
    die("Invalid Product ID");
}

// Fetch related products based on the category_id
$related_products_stmt = $conn->prepare("SELECT * FROM products WHERE category_id = :category_id AND product_id != :product_id LIMIT 6");

if ($related_products_stmt) {
    $related_products_stmt->bindParam(':category_id', $product['category_id'], PDO::PARAM_INT);
    $related_products_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $related_products_stmt->execute();
    $related_products = $related_products_stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    die("Failed to fetch related products.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
         nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed; /* Ensures it stays at the top and over the hero */
            top: 0;
            width: 100%;
            z-index: 100; /* Keeps it above other elements */
            background: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
            backdrop-filter: blur(5px); /* Optional: Adds a glassmorphism effect */
            padding: 1rem; /* Adjust padding as needed */
        }
        
        nav ul li a {
            color: black;
            text-decoration: none;
            font-size: 1.1rem;
            transition: color 0.3s;
        }


        .container {
            width: 80%;
            margin: 20px auto;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            background-color: black;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .product-image {
            flex: 1;
            max-width: 45%;
            margin-right: 20px;
        }

        .product-image img {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .product-details {
            flex: 2;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .product-details h1 {
            font-size: 2rem;
            color: white;
            margin-bottom: 10px;
        }

        .product-details p {
            font-size: 1.2rem;
            color: #b18ce9;
            margin: 10px 0;
        }

        .product-details .price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #00aaff;
            margin: 20px 0;
        }

        .form-container {
            display: flex;
            align-items: center;
            margin-top: 20px;
        }

        .form-container input[type="number"] {
            padding: 10px;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-right: 10px;
            width: 80px;
        }

        .form-container button {
            padding: 10px 20px;
            font-size: 1rem;
            background: #00ddff;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #b18ce9;
        }
        .related-products {
            margin-top: 40px;
        }

        .related-products h2 {
            font-size: 1.8rem;
            color: white;
            margin-bottom: 20px;
        }

        .related-products .product-card {
            display: inline-block;
            width: 30%;
            margin-right: 3%;
            text-align: center;
        }

        .related-products .product-card img {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .related-products .product-card .name {
            font-size: 1.1rem;
            color: white;
            margin: 10px 0;
        }

        .related-products .product-card .price {
            font-size: 1.2rem;
            color: #00aaff;
            margin-bottom: 10px;
        }


        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .product-image {
                max-width: 100%;
                margin-bottom: 20px;
            }

            .product-details {
                flex: none;
            }

            .related-products .product-card {
                width: 100%;
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
<nav>
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>


    <div class="container">
        <div class="product-image">
         <img src="<?php echo htmlspecialchars($product['image_url'] ? $product['image_url'] : 'default_image.jpg'); ?>" alt="<?php echo htmlspecialchars($product['name'] ? $product['name'] : 'Unknown Product'); ?>">

        </div>
        <div class="product-details">
            <h1><?php echo htmlspecialchars($product['name'] ?? 'Unknown Product'); ?></h1>
            <p><?php echo nl2br(htmlspecialchars($product['description'] ?? 'No description available')); ?></p>
            <p class="price">$<?php echo number_format(htmlspecialchars($product['price']), 2); ?></p>

            <form method="POST" action="cart.php" class="form-container">
                <label for="quantity">Quantity:</label>
                <input type="number" name="quantity" min="1" value="1" id="quantity">
                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                <button type="submit">Add to Cart</button>
            </form>
        </div>
    </div>

    <div class="related-products">
        <h2>Related Products</h2>
        <div class="product-card-container">
        <?php foreach ($related_products as $related_product): ?>
            <div class="product-card">
                <img src="<?php echo htmlspecialchars($related_product['image_url'] ? $related_product['image_url'] : 'default_image.jpg'); ?>" alt="<?php echo htmlspecialchars($related_product['name'] ? $related_product['name'] : 'Unknown Product'); ?>">
                <div class="name"><?php echo htmlspecialchars($related_product['name'] ? $related_product['name'] : 'Unknown Product'); ?></div>
                <div class="price">$<?php echo number_format($related_product['price'], 2); ?></div>
                <a href="product.php?id=<?php echo $related_product['product_id']; ?>">View Details</a>
            </div>
        <?php endforeach; ?>

        </div>
    </div>

</body>
</html>
