<?php
// Include the database connection file
require 'dbConn.php'; // Make sure the path is correct based on your directory structure

// Function to get fire incident details
function getFireIncidentDetails($pdo, $forest_zone_name = null, $forest_circle_name = null, $forest_division_name = null, $forest_punjab = null, $incident_year = null, $incident_month = null) {
    // Prepare the SQL query to call the function
    $query = "SELECT * FROM get_fire_incident_details_filtered(:forest_zone_name, :forest_circle_name, :forest_division_name, :forest_punjab, :incident_year, :incident_month)";
    
    // Prepare the statement
    $stmt = $pdo->prepare($query);
    
    // Bind parameters
    $stmt->bindParam(':forest_zone_name', $forest_zone_name, PDO::PARAM_STR);
    $stmt->bindParam(':forest_circle_name', $forest_circle_name, PDO::PARAM_STR);
    $stmt->bindParam(':forest_division_name', $forest_division_name, PDO::PARAM_STR);
    $stmt->bindParam(':forest_punjab', $forest_punjab, PDO::PARAM_STR);
    $stmt->bindParam(':incident_year', $incident_year, PDO::PARAM_INT);
    $stmt->bindParam(':incident_month', $incident_month, PDO::PARAM_INT);
    
    // Execute the statement
    $stmt->execute();
    
    // Fetch all results
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return $data;
}

// Get the JSON input from the request body
$jsonInput = file_get_contents('php://input');

// Decode the JSON input
$inputData = json_decode($jsonInput, true);

// Check if decoding was successful
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'Invalid JSON input']);
    exit;
}

// Extract parameters from the decoded JSON
$forest_zone_name = $inputData['forest_zone_name'] ?? null;
$forest_circle_name = $inputData['forest_circle_name'] ?? null;
$forest_division_name = $inputData['forest_division_name'] ?? null;
$forest_punjab = $inputData['forest_punjab'] ?? null;
$incident_year = $inputData['incident_year'] ?? null;
$incident_month = $inputData['incident_month'] ?? null;

// Get fire incident details
$fire_incidents = getFireIncidentDetails($pdo, $forest_zone_name, $forest_circle_name, $forest_division_name, $forest_punjab, $incident_year, $incident_month);

// Output the results as JSON
header('Content-Type: application/json');
echo json_encode($fire_incidents);
?>