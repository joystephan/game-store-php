<?php
require_once './includes/config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $stmt = $conn->query("SELECT DATABASE()");
    $dbName = $stmt->fetchColumn();
    echo "Successfully connected to database: " . htmlspecialchars($dbName);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
