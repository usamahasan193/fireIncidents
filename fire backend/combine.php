<?php
// Enable error reporting for debugging (optional)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
require 'dbConn.php'; // Adjust the path accordingly

// Function to get fire count by zone
function get_fire_count_by_zone($pdo) {
    try {
        // Prepare the SQL query to call the PostgreSQL function
        $query = "SELECT * FROM get_fire_count_by_zone()"; // Assuming the function takes no parameters

        // Prepare the statement
        $stmt = $pdo->prepare($query);

        // Execute the query
        $stmt->execute();

        // Fetch the results
        $summary = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Debugging: Log the results
        error_log("Fire count summary: " . print_r($summary, true));

        return $summary;
    } catch (PDOException $e) {
        // Log the error message
        error_log("Database error: " . $e->getMessage());
        return []; // Return an empty array or handle the error as needed
    }
}

// Check the request method
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Call the function to get fire count by zone
    $fire_count_summary = get_fire_count_by_zone($pdo);

    // Return response in JSON format
    header('Content-Type: application/json');
    echo json_encode($fire_count_summary);
} else {
    // Return an error message for unsupported request methods
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unsupported request method.']);
}
?>