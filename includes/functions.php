<?php
// functions.php
require_once 'config.php';

// Fetch all products
function getAllProducts() {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM products");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch product by ID
function getProductById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// User login
function loginUser($email, $password) {
    global $conn;

     // Prepare and execute the query to fetch the user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    // Fetch the user data from the database
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    

    if ($user) {
        // Log the entered password and stored password hash for debugging
        error_log("Entered password: $password");
        error_log("Stored hash: " . $user['password']);
        
        // Check if the user exists and the password matches
        if (password_verify($password, $user['password'])) {
            // Log successful password verification
            error_log("Password verification successful for email: $email");
            return $user;
        } else {
            // Log failed password verification
            error_log("Password verification failed for email: $email");
        }
    } else {
        error_log("No user found for email: $email");
    }

    return false;
}

// User registration
function registerUser($username, $email, $password) {
    global $conn;
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();
    return $conn->lastInsertId();
}

function searchProducts($query) {
    $pdo = new PDO('mysql:host=localhost;port=8889;dbname=gaming_store', 'root', 'root');
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE :query");
    $stmt->execute(['query' => '%' . $query . '%']);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



?>
