<?php
// Set headers to allow API access and return JSON response
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

// Include the database connection file
require 'db.php';

// Query to fetch all forest zones
$query = "SELECT DISTINCT forest_zone FROM forest_zones ORDER BY forest_zone";
$result = pg_query($dbconn, $query);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed: " . pg_last_error()]);
    exit;
}

// Fetch and format the result
$zones = [];
while ($row = pg_fetch_assoc($result)) {
    $zones[] = $row['forest_zone'];
}

// Close database connection
pg_close($dbconn);

// Return response as JSON
echo json_encode(["status" => "success", "data" => $zones]);
?>