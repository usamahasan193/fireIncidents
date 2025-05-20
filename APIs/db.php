<?php
// Database connection parameters
$host = 'firework.c3eu2sia4hbw.ap-southeast-1.rds.amazonaws.com';  // PostgreSQL host
$dbname = 'aws_fireincidents'; // Database name
$user = 'postgres';        // Database username
$password = 'FT8zl5RtKs6eA';   // Database password

// Create a connection string
$conn_string = "host=$host dbname=$dbname user=$user password=$password";

// Connect to PostgreSQL
$dbconn = pg_connect($conn_string);

if (!$dbconn) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . pg_last_error()]));
}
?>
