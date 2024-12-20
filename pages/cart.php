<?php
session_start();
require_once '../includes/config.php';

// Initialize the cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Add to Cart requests
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity > 0) {
        $_SESSION['cart'][$product_id] = $quantity;
    }
}

// Fetch cart products
$cart_items = [];
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));

    // Prepare and execute the SQL query to fetch product details for cart items
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id IN ($ids)");
    $stmt->execute();

    // Fetch the result as an associative array
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Gaming Store</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: 'Orbitron', sans-serif;
            background-color: #121212;
            color: #ffffff;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            padding-top: 4rem; 
        }

        nav {
            background-color: #333;
            padding: 10px 20px;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: flex-start;
        }

        nav ul li {
            margin-right: 20px;
        }

        nav ul li a {
            color: black;
            text-decoration: none;
            font-weight: bold;
        }

        
        h1 {
            text-align: center;
            margin-top: 30px;
            color: white;
        }

        .cart-table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .cart-table th,
        .cart-table td {
            padding: 12px 15px;
            text-align: center;
        }

        .cart-table th {
            background-color: black;
            color: #00ddff;
        }

        .cart-table td {
            background-color: black;
        }

        .cart-table tr:nth-child(even) td {
            background-color: black;
        }

        .total-price {
            font-size: 20px;
            font-weight: bold;
            text-align: right;
            margin-right: 20%;
        }

        .empty-cart {
            text-align: center;
            font-size: 18px;
            color: #888;
        }

        .btn {
            background-color: #00ddff;
            padding: 10px 20px;
            color: black;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .btn:hover {
            background-color: #b18ce9;
        }

        strong {
            color: #00ddff;
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

    <h1>Your Shopping Cart</h1>

    <?php if (!empty($cart_items)): ?>
    <table class="cart-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td>$<?php echo htmlspecialchars($item['price']); ?></td>
                    <td><?php echo $_SESSION['cart'][$item['product_id']]; ?></td>
                    <td>$<?php echo number_format($item['price'] * $_SESSION['cart'][$item['product_id']], 2); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p class="total-price">
        <strong>Total:</strong> $
        <?php
        $total = 0;
        foreach ($cart_items as $item) {
            $total += $item['price'] * $_SESSION['cart'][$item['product_id']];
        }
        echo number_format($total, 2);
        ?>
    </p>

    <div style="text-align: center;">
        <a href="checkout.php" class="btn">Proceed to Checkout</a>
    </div>
<?php else: ?>
    <p class="empty-cart">Your cart is empty. Start shopping now!</p>
    <div style="text-align: center;">
        <a href="product.php" class="btn">Go to Shop</a>
    </div>
<?php endif; ?>

</body>
</html>
