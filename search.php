<?php
require_once './includes/functions.php';

if (isset($_GET['query'])) {
    $query = htmlspecialchars($_GET['query']);
    $searchResults = searchProducts($query); // A function to search products in the database
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
    <h1>Search Results for "<?php echo $query; ?>"</h1>
    <div class="products">
        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $product): ?>
                <div class="product">
                    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                    <p>Price: $<?php echo htmlspecialchars($product['price']); ?></p>
                    <a href="pages/product.php?id=<?php echo $product['product_id']; ?>">View Details</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No results found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
