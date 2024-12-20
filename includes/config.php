<?php

// Start the session


// Generate CSRF token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a random token
}


// config.php
$host = 'localhost'; // MAMP defaults
$dbname = 'gaming_store'; // Your database name
$username = 'root'; // MAMP  default username
$password = 'root'; // MAMP default password for macOS, leave blank for Windows

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
