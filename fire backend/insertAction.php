<?php
// Database configuration
require 'dbConn.php'; // Make sure the path is correct based on your directory structure

// Check if the expected POST variables exist and set default values if necessary
$p_fire_id = isset($_POST['p_fire_id']) ? $_POST['p_fire_id'] : null;
$p_field_formation_id = isset($_POST['p_field_formation_id']) ? $_POST['p_field_formation_id'] : null;
$p_concern_official = isset($_POST['p_concern_official']) ? $_POST['p_concern_official'] : null;
$p_response_time = isset($_POST['p_response_time']) ? $_POST['p_response_time'] : null;
$p_actions_taken = isset($_POST['p_actions_taken']) ? $_POST['p_actions_taken'] : null;
$p_remarks = isset($_POST['p_remarks']) ? $_POST['p_remarks'] : null;

// Prepare the SQL query to insert action taken using user-provided data
$query = "
SELECT public.insert_action_taken(
    :p_fire_id,                   -- p_fire_id
    :p_field_formation_id,        -- p_field_formation_id
    :p_concern_official,          -- p_concern_official
    :p_response_time,             -- p_response_time
    :p_actions_taken,             -- p_actions_taken
    :p_remarks                    -- p_remarks
);";

// Prepare the statement
$stmt = $pdo->prepare($query);

// Bind parameters
$stmt->bindParam(':p_fire_id', $p_fire_id, PDO::PARAM_INT);
$stmt->bindParam(':p_field_formation_id', $p_field_formation_id, PDO::PARAM_INT);
$stmt->bindParam(':p_concern_official', $p_concern_official, PDO::PARAM_STR);
$stmt->bindParam(':p_response_time', $p_response_time, PDO::PARAM_STR);
$stmt->bindParam(':p_actions_taken', $p_actions_taken, PDO::PARAM_STR);
$stmt->bindParam(':p_remarks', $p_remarks, PDO::PARAM_STR);

// Execute the query
try {
    $stmt->execute();

    // Fetch the result which should contain the response of the insert action (you can customize this as needed)
    echo json_encode([
        "status" => "success",
        "message" => "Action taken inserted successfully"
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "An error occurred while inserting the action taken: " . $e->getMessage()
    ]);
}

// Close the database connection (not necessary with PDO as it's closed automatically)
$pdo = null;
?>
