<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require 'db.php'; // Include database connection

// Get parameters from request
$resource_name = isset($_GET['resource_name']) ? $_GET['resource_name'] : null;
$type_name = isset($_GET['type_name']) ? $_GET['type_name'] : null;
$type = isset($_GET['type']) ? $_GET['type'] : null;

if (!$resource_name) {
    echo json_encode(["status" => "error", "message" => "Resource Name is required."]);
    exit;
}

// Prepare the query
$query = "SELECT * FROM get_filtered_resources_v2($1, $2, $3)";

// Execute the query
$result = pg_query_params($dbconn, $query, [$resource_name, $type_name, $type]);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query execution failed: " . pg_last_error()]);
    exit;
}

// Fetch results
$data = pg_fetch_all($result);

// Return JSON response
echo json_encode(["status" => "success", "data" => $data]);
?>
