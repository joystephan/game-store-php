<?php
require_once '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate user inputs
    $username = trim($_POST['username']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    
    // Check if fields are not empty
    if (empty($username) || empty($email) || empty($password)) {
        echo "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    } else {
        // Hash the password securely
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);

        // Prepare and execute the SQL query
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        
        if ($stmt) {
            // Bind the parameters
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password_hashed);

            // Execute the statement
            if ($stmt->execute()) {
                echo "Registration successful! You can now <a href='login.php'>log in</a>.";
            } else {
                echo "Registration failed. Please try again.";
            }
        } else {
            echo "Error in preparing the SQL query.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>

        .form-container {
            background-color: rgba(31, 31, 31, 0.9);
            padding: 2rem;
            border-radius: 10px;
            width: 100%;
            max-width: 400px; /* Limit the form's width */
            margin: 5rem auto; /* Center the form vertically and horizontally */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); /* Soft shadow for the box */
            text-align: center;
        }

        .form-group {
            display: block;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
            color: #fff;
        }

        .input-field {
            width: 100%;
            padding: 1rem;
            margin: 0.5rem 0 1.5rem 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #222;
            color: #fff;
            font-size: 1rem;
            outline: none;
        }

        .input-field:focus {
            border-color: #00ddff;
        }

        form .error {
        color: red;
        font-size: 1rem;
        margin-bottom: 1rem;
       }

       h1 {
        text-align: center;
        font-size: 2.5rem;
        color: #fff;
        margin-bottom: 2rem;
        margin-top: 2rem;
       }

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
      
    </style>

</head>
<body>
    <nav>
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="cart.php">Cart</a></li>
            <li><a href="login.php">Login</a></li>
            
        </ul>
    </nav>

    <h1>Create an Account</h1>

    <form method="POST" action="" class="form-container">
       <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" name="username" class="input-field" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="input-field" required>
        </div>
       
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" class="input-field" required>
        </div>

      <button type="submit" class="submit-button">Register</button>
    </form>
</body>
</html> 