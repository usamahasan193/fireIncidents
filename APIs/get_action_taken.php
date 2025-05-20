<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require 'db.php';

// Get fire_ids parameter
$fire_ids_param = isset($_GET['fire_ids']) ? $_GET['fire_ids'] : '';

// Debug log
error_log("Received fire_ids: " . $fire_ids_param);

// Validate fire_ids parameter
if (empty($fire_ids_param)) {
    echo json_encode(["status" => "error", "message" => "Missing 'fire_ids' parameter"]);
    exit;
}

// Parse comma-separated IDs
$fire_ids_array = explode(',', $fire_ids_param);

// Validate each ID is numeric
$valid_ids = [];
foreach ($fire_ids_array as $id) {
    $id = trim($id);
    if (is_numeric($id)) {
        $valid_ids[] = (int)$id;
    }
}

if (empty($valid_ids)) {
    echo json_encode(["status" => "error", "message" => "No valid fire IDs provided"]);
    exit;
}

// Convert PHP array to PostgreSQL array format
$pg_array = "{" . implode(",", $valid_ids) . "}";

// Call the PostgreSQL function
$query = "SELECT * FROM get_action_taken_by_fire_ids($1)";
$result = pg_query_params($dbconn, $query, array($pg_array));

if (!$result) {
    echo json_encode([
        "status" => "error", 
        "message" => "Query failed: " . pg_last_error(),
        "query" => $query,
        "params" => $pg_array
    ]);
    exit;
}

// Fetch all results
$actions = [];
while ($row = pg_fetch_assoc($result)) {
    // Convert numeric strings to appropriate types
    foreach ($row as $key => $value) {
        if (is_numeric($value) && strpos($value, '.') !== false) {
            $row[$key] = (float)$value;
        } else if (is_numeric($value)) {
            $row[$key] = (int)$value;
        }
    }
    $actions[] = $row;
}

// Group actions by fire_id for better organization
$grouped_actions = [];
foreach ($actions as $action) {
    $fire_id = $action['fire_id'];
    if (!isset($grouped_actions[$fire_id])) {
        $grouped_actions[$fire_id] = [];
    }
    $grouped_actions[$fire_id][] = $action;
}

pg_close($dbconn);

echo json_encode([
    "status" => "success", 
    "count" => count($actions),
    "data" => $actions,
    "grouped_data" => $grouped_actions
]);
?>