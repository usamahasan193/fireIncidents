<?php
// Set headers to allow API access and return JSON response
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

// Include the database connection file
require 'db.php';

// Get the 'forest_zone' parameter from the API request
$forest_zone = isset($_GET['forest_zone']) ? pg_escape_string($_GET['forest_zone']) : '';

if (empty($forest_zone)) {
    echo json_encode(["status" => "error", "message" => "Missing 'forest_zone' parameter"]);
    exit;
}

// Query to fetch circles for the selected zone
$query = "SELECT DISTINCT forest_circle FROM forest_circles WHERE forest_zone = '$forest_zone' ORDER BY forest_circle";
$result = pg_query($dbconn, $query);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed: " . pg_last_error()]);
    exit;
}

// Fetch and format the result
$circles = [];
while ($row = pg_fetch_assoc($result)) {
    $circles[] = $row['forest_circle'];
}

// Close database connection
pg_close($dbconn);

// Return response as JSON
echo json_encode(["status" => "success", "data" => $circles]);
?>