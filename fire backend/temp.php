<?php
// Database configuration
$host = 'firework.c3eu2sia4hbw.ap-southeast-1.rds.amazonaws.com'; // Database host
$dbname = 'aws_fireincidents'; // Database name
$user = 'postgres'; // Database username
$password = 'FT8zl5RtKs6eA'; // Database password

header('Content-Type: application/json'); // Set the content type to JSON

try {
    // Create a new PDO instance
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get the JSON input from the request body
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Check if the year is provided in the input
    if (!isset($input['year']) || !is_numeric($input['year'])) {
        echo json_encode(['error' => 'Year is required and must be a number.']);
        exit;
    }

    // Get the year from the input
    $p_year = (int)$input['year'];

    // Prepare the SQL statement to call the PostgreSQL function
    $stmt = $pdo->prepare("SELECT * FROM get_fire_incident_count_by_division(:year)");
    
    // Bind the parameter
    $stmt->bindParam(':year', $p_year, PDO::PARAM_INT);
    
    // Execute the statement
    $stmt->execute();
    
    // Fetch all results into an array
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return the results as JSON
    echo json_encode($results);
    
} catch (PDOException $e) {
    // Return an error message in JSON format
    echo json_encode(['error' => 'Database operation failed: ' . $e->getMessage()]);
}

// Close the PDO connection (optional, as it will close automatically at the end of the script)
$pdo = null;
?>