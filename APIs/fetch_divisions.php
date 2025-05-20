<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require 'db.php';

// Debugging: Log the received parameters
error_log("Received forest_circle: " . ($_GET['forest_circle'] ?? 'No forest_circle parameter received'));

$forest_circle = isset($_GET['forest_circle']) ? pg_escape_string($_GET['forest_circle']) : '';

if (empty($forest_circle)) {
    echo json_encode(["status" => "error", "message" => "Missing 'forest_circle' parameter"]);
    exit;
}

$query = "SELECT DISTINCT forest_division FROM forest_divisions WHERE forest_circle = '$forest_circle' ORDER BY forest_division";
$result = pg_query($dbconn, $query);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed: " . pg_last_error()]);
    exit;
}

$divisions = [];
while ($row = pg_fetch_assoc($result)) {
    $divisions[] = $row['forest_division'];
}

pg_close($dbconn);

echo json_encode(["status" => "success", "data" => $divisions]);
?>