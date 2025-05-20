<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require 'db.php'; // Database connection

try {
    // Get the optional forest_division parameter
    $forest_division = isset($_GET['forest_division']) ? $_GET['forest_division'] : null;

    // Base query
    $query = "SELECT DISTINCT ON (forest_name) unique_id, forest_name FROM forest_punjab";
    $params = [];

    // If forest_division is provided, add the WHERE condition
    if ($forest_division) {
        $query .= " WHERE f_division = $1";
        $params[] = $forest_division;
    }

    // Prepare and execute the query safely
    if (!empty($params)) {
        $stmt = pg_prepare($dbconn, "fetch_forest_punjab", $query);
        $result = pg_execute($dbconn, "fetch_forest_punjab", $params);
    } else {
        $result = pg_query($dbconn, $query);
    }

    if (!$result) {
        throw new Exception("Query failed: " . pg_last_error($dbconn));
    }

    // Fetch all results and handle empty results
    $forest_punjab = pg_fetch_all($result);
    if (!$forest_punjab) {
        $forest_punjab = []; // Return an empty array if no records are found
    }

    // Close database connection
    pg_close($dbconn);

    // Return JSON response
    echo json_encode(["status" => "success", "data" => $forest_punjab]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>