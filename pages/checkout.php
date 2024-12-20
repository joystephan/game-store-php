<?php
session_start();
require_once '../includes/config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ensure the cart is not empty
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty. <a href='../index.php'>Go to Shop</a></p>";
    exit;
}

// Fetch cart items
$cart_items = [];
$total_price = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id IN ($ids)");
    $stmt->execute();
    $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($cart_items as $item) {
        $total_price += $item['price'] * $_SESSION['cart'][$item['product_id']];
    }
}

// Handle form submission for order placement
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $address = htmlspecialchars($_POST['address']);
    $phone = htmlspecialchars($_POST['phone']);
    $user_id = $_SESSION['user_id'];
    $product_names = implode(', ', array_column($cart_items, 'name'));

    // Insert the order into the orders table
    $order_query = $conn->prepare("
        INSERT INTO orders (user_id, product_name, total_price, name, address, phone)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $order_query->execute([$user_id, $product_names, $total_price, $name, $address, $phone]);

    // Clear the cart after successful checkout
    unset($_SESSION['cart']);

    // Redirect to a thank-you page
    header("Location: thank_you.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Gaming Store</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: 'Orbitron', sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1, h2 {
            text-align: center;
            color: #f39c12;
        }

        table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #444;
            color: #fff;
        }

        table tr:nth-child(even) {
            background-color: #555;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form label {
            margin-top: 10px;
            font-weight: bold;
        }

        form input[type="text"], form input[type="number"] {
            padding: 10px;
            margin-top: 5px;
            border: none;
            border-radius: 5px;
            width: 100%;
        }

        .btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #f39c12;
            color: #fff;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #e67e22;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Checkout</h1>
        <h2>Order Summary</h2>
        <table>
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
        <p><strong>Total Price:</strong> $<?php echo number_format($total_price, 2); ?></p>

        <h2>Shipping Information</h2>
        <form method="POST">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>

            <label for="address">Address</label>
            <input type="text" id="address" name="address" required>

            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" required>

            <button type="submit" class="btn">Place Order</button>
        </form>
    </div>
</body>
</html>
