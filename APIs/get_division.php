<?php
// Set headers to allow API access and return JSON response
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

// Include the database connection file
require 'db.php';

// Get the 'forest_division' parameter from the API request
$forest_division = isset($_GET['forest_division']) ? pg_escape_string($_GET['forest_division']) : '';

if (empty($forest_division)) {
    echo json_encode(["status" => "error", "message" => "Missing 'forest_division' parameter"]);
    exit;
}

// Query to fetch the forest zone geometry
$query = "SELECT forest_division, ST_AsGeoJSON(geom) AS geojson FROM forest_divisions WHERE forest_division = '$forest_division'";
$result = pg_query($dbconn, $query);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed: " . pg_last_error()]);
    exit;
}

// Fetch and format the result
$zones = [];
while ($row = pg_fetch_assoc($result)) {
    $zones[] = [
        "forest_division" => $row['forest_division'],
        "geometry" => json_decode($row['geojson']) // Convert to valid JSON
    ];
}

// Close database connection
pg_close($dbconn);

// Return response as JSON
echo json_encode(["status" => "success", "data" => $zones]);
?>
