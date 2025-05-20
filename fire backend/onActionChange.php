<?php

// Include the database connection file
require 'dbConn.php'; // Make sure the path is correct based on your directory structure


// Get the department or community parameter from user input
$department_or_community = isset($_GET['department_or_community']) ? $_GET['department_or_community'] : null;

if ($department_or_community === null) {
    echo json_encode(['error' => 'Parameter "department_or_community" is required.']);
    exit;
}

// Prepare the SQL query to call the function
$query = "SELECT public.get_field_formation(:department_or_community) AS result";

try {
    // Prepare the statement
    $stmt = $pdo->prepare($query);
    
    // Bind the parameter
    $stmt->bindParam(':department_or_community', $department_or_community);
    
    // Execute the statement
    $stmt->execute();
    
    // Fetch the results
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Convert the results to JSON
    $json_data = json_encode($data);
    
    // Set the content type to application/json
    header('Content-Type: application/json');
    
    // Output the JSON data
    echo $json_data;

} catch (PDOException $e) {
    // Handle any errors
    echo json_encode(['error' => $e->getMessage()]);
}

// Close the database connection
$pdo = null;
?>