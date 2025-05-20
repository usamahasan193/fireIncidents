<?php
// Enable error reporting for debugging (optional)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
require 'dbConn.php'; // Adjust the path accordingly

// Function to get fire incident summary by zone
function get_fire_incident_summary_by_zone($pdo, $p_year, $p_month = null) {
    try {
        // Prepare the SQL query to call the PostgreSQL function
        $query = "SELECT * FROM get_fire_incident_summary_by_zone(:year, :month)";

        // Prepare the statement
        $stmt = $pdo->prepare($query);

        // Bind parameters
        $stmt->bindParam(':year', $p_year, PDO::PARAM_INT);
        
        // Check if month is provided and bind accordingly
        if ($p_month !== null) {
            $stmt->bindParam(':month', $p_month, PDO::PARAM_INT);
        } else {
            // If month is not provided, bind it as NULL
            $null = null;
            $stmt->bindParam(':month', $null, PDO::PARAM_NULL);
        }

        // Execute the query
        $stmt->execute();

        // Fetch the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Log the error and return empty array in case of failure
        error_log("Database error: " . $e->getMessage());
        return ['error' => 'Error fetching incident summary by zone'];
    }
}

// Function to get fire count by year
function get_fire_count_by_year($pdo, $year) {
    try {
        // Prepare the SQL query to call the PostgreSQL function
        $query = "SELECT * FROM get_fire_count_by_month(:year)"; // Assuming the function still takes only year

        // Prepare the statement
        $stmt = $pdo->prepare($query);

        // Bind parameters
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Fetch the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Log the error and return empty array in case of failure
        error_log("Database error: " . $e->getMessage());
        return ['error' => 'Error fetching fire count by year'];
    }
}

// Function to retrieve the count of fires categorized by zone and year.
function getFireCountByZone($pdo) {
    try {
        // Prepare the SQL query to call the function
        $query = "SELECT public.get_fire_count_by_zone() AS result";

        // Prepare the statement
        $stmt = $pdo->prepare($query);

        // Execute the statement
        $stmt->execute();

        // Fetch the results
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Parse the raw result into a structured array
        $parsed_data = [];
        foreach ($data as $row) {
            // The result is a string like ("Zone", year, count)
            $result = $row['result'];

            // Debugging: output the raw result for inspection
            // echo "Raw result: " . $result . "<br>";  // Uncomment to debug each result
            
            // Clean the result string to remove parentheses and split by commas
            $clean_result = trim($result, '()"');
            $parts = explode(',', $clean_result);

            // Ensure we have exactly 3 elements: Zone, Year, Count
            if (count($parts) === 3) {
                // Strip any unwanted escape characters from the 'zone' string
                $zone = stripslashes(trim($parts[0])); // Remove extra escape slashes

                // Check if any other trimming is needed
                $zone = trim($zone); // Make sure there are no leading/trailing spaces

                // Add the cleaned data to the result array
                $parsed_data[] = [
                    'zone' => $zone,
                    'year' => intval($parts[1]),
                    'count' => intval($parts[2])
                ];
            }
        }

        // Return the structured data
        return $parsed_data;
    } catch (PDOException $e) {
        // Log the error and return an empty array in case of failure
        error_log("Database error: " . $e->getMessage());
        return ['error' => 'Error fetching fire count by zone'];
    }
}

//Function for retrive division wise count of incidents

function getFireIncidentCountByDivision($pdo, $year) {
   
    try {
        
        // Prepare the SQL statement to call the PostgreSQL function
        $stmt = $pdo->prepare("SELECT * FROM get_fire_incident_count_by_division(:year)");
        
        // Bind the parameter
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        
        // Execute the statement
        $stmt->execute();
        
        // Fetch all results into an array
        // Fetch the results
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        // Log the error and return an empty array in case of failure
        error_log("Database error: " . $e->getMessage());
        return ['error' => 'Error fetching fire count by division'];
    }
}


// Check the request method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the request is JSON
    if (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        // Get the raw JSON input
        $json = file_get_contents('php://input');

        // Decode the JSON into an associative array
        $data = json_decode($json, true);

        // Validate the input
        $p_year = isset($data['year']) ? intval($data['year']) : null;
        $p_month = isset($data['month']) ? intval($data['month']) : null;

        // Check if year is provided
        if ($p_year === null) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Year is required.']);
            exit;
        }

        // Call the functions to get all summaries
        $incident_summary = get_fire_incident_summary_by_zone($pdo, $p_year, $p_month);
        $fire_count_summary = get_fire_count_by_year($pdo, $p_year);
        $zone_year_summary = getFireCountByZone($pdo);
        $division_wise_summary = getFireIncidentCountByDivision($pdo, $p_year);

        // Combine the results into a single array
        $combined_results = [
            'incident_summary' => $incident_summary,
            'fire_count_monthwise_summary' => $fire_count_summary,
            'fire_count_yearwise_summary' => $zone_year_summary,
            'fire_count_divisionwise_summary' => $division_wise_summary,

        ];

        // Return response in JSON format
        header('Content-Type: application/json');
        echo json_encode($combined_results);
    } else {
        // Handle form submission for year only
        $year = isset($_POST['year']) ? intval($_POST['year']) : null;

        if ($year) {
            // Call the function to get fire count by year
            $fire_count_summary = get_fire_count_by_year($pdo, $year);

            // Return response in JSON format
            header('Content-Type: application/json');
            echo json_encode(['fire_count_summary' => $fire_count_summary]);
        } else {
            // Return an error message if input is invalid
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid year provided.']);
        } 
    }
}
?>
