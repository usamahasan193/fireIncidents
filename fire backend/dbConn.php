<?php
// Database connection parameters
$host = 'firework.c3eu2sia4hbw.ap-southeast-1.rds.amazonaws.com';  // PostgreSQL host
$dbname = 'aws_fireincidents'; // Database name
$user = 'postgres';        // Database username
$password = 'FT8zl5RtKs6eA';   // Database password

try {
    // Create a new PDO instance
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>