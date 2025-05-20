<?php
// Set headers to allow API access and return JSON response
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

// Include the database connection file
require 'db.php';

// Get the 'forest_circle' parameter from the API request
$forest_circle = isset($_GET['forest_circle']) ? pg_escape_string($_GET['forest_circle']) : '';

if (empty($forest_circle)) {
    echo json_encode(["status" => "error", "message" => "Missing 'forest_circle' parameter"]);
    exit;
}

// Query to fetch the forest zone geometry
$query = "SELECT forest_circle, ST_AsGeoJSON(geom) AS geojson FROM forest_circles WHERE forest_circle = '$forest_circle'";
$result = pg_query($dbconn, $query);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed: " . pg_last_error()]);
    exit;
}

// Fetch and format the result
$zones = [];
while ($row = pg_fetch_assoc($result)) {
    $zones[] = [
        "forest_circle" => $row['forest_circle'],
        "geometry" => json_decode($row['geojson']) // Convert to valid JSON
    ];
}

// Close database connection
pg_close($dbconn);

// Return response as JSON
echo json_encode(["status" => "success", "data" => $zones]);
?>
