<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .thank-you-container {
            text-align: center;
            margin: 100px auto;
            padding: 30px;
            background-color: #121212;
            color: white;
            border: 2px solid #00ddff; 
            border-radius: 10px;
            width: 70%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .thank-you-container:hover {
            transform: scale(1.1); /* Slightly enlarge the container */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.4); /* Enhance the shadow */
        }

        .thank-you-container h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .thank-you-container p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .thank-you-container .btn {
            background-color: #00ddff;
            padding: 10px 20px;
            color: black;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.1rem;
        }

        .thank-you-container .btn:hover {
            background-color: #b18ce9;
        }
        
    </style>
</head>
<body>
    <div class="thank-you-container">
        <h1>Thank You for Your Order!</h1>
        <p>Your order has been placed successfully.</p>
        <a href="../index.php" class="btn">Return to Shop</a>
    </div>
</body>
</html>
