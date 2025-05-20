<?php
// Include the database connection file
require 'dbConn.php'; // Make sure the path is correct based on your directory structure

// Function to authenticate user
function authenticateUser ($username, $password) {
    global $pdo;

    // Prepare the SQL statement
    $stmt = $pdo->prepare("SELECT user_id, username, display_name, password, f_division_id FROM user_management WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch the user record
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and verify password (plain text comparison)
    if ($user && $user['password'] === $password) {
        return $user; // User authenticated
    } else {
        return null; // Authentication failed
    }
}

// Function to get forest details by division
function getForestDetailsByDivision($division_id) {
    global $pdo;

    // Call the existing function in the database
    $stmt = $pdo->prepare("SELECT * FROM get_forest_details_by_division(:division_id)");
    $stmt->bindParam(':division_id', $division_id);
    $stmt->execute();

    // Fetch all forest records
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Check if the username and password are provided in the query string
if (isset($_GET['username']) && isset($_GET['password'])) {
    $username = $_GET['username'];
    $password = $_GET['password'];

    $authenticatedUser  = authenticateUser ($username, $password);

    if ($authenticatedUser ) {
        // Retrieve the f_division_id from the authenticated user
        $f_division_id = $authenticatedUser ['f_division_id'];

        // Call the function to get forest details using the f_division_id
        $forestDetails = getForestDetailsByDivision($f_division_id);

        // Return the forest details in JSON format
        header('Content-Type: application/json');
        echo json_encode([
            'f_division_id' => $f_division_id,
            'forest_details' => $forestDetails
        ]);
    } else {
        echo json_encode(['error' => 'Authentication failed: Invalid username or password.']);
    }
} else {
    echo json_encode(['error' => 'Please provide username and password in the query string.']);
}
?>