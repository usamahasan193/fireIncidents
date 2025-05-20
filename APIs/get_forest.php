<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require 'db.php'; // Database connection

try {
    $unique_id = isset($_GET['unique_id']) ? $_GET['unique_id'] : null;

    if (!$unique_id) {
        throw new Exception("Forest ID is required");
    }

    // Fetch additional columns along with the geometry
    $query = "
        SELECT 
            ST_AsGeoJSON(geom) AS geojson,
            f_zone,
            f_circle,
            f_division,
            forest_name,
            gps_area_acre,
            gross_area_acre,
            forest_type,
            area_hec,
            legal_state,
            civil_tehsil
        FROM forest_punjab 
        WHERE unique_id = $1
    ";
    $stmt = pg_prepare($dbconn, "fetch_forests", $query);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . pg_last_error($dbconn));
    }

    $result = pg_execute($dbconn, "fetch_forests", [$unique_id]);

    if (!$result) {
        throw new Exception("Query failed: " . pg_last_error($dbconn));
    }

    $forest_data = pg_fetch_all($result);
    if (!$forest_data) {
        $forest_data = []; // Return an empty array if no records are found
    }

    pg_close($dbconn);

    // Convert the result to a GeoJSON feature collection
    $features = [];
    foreach ($forest_data as $row) {
        $features[] = [
            "type" => "Feature",
            "geometry" => json_decode($row['geojson']),
            "properties" => [
                "forest_name" => $row['forest_name'],
                "f_division" => $row['f_division'],
                "f_circle" => $row['f_circle'],
                "gps_area_acre" => $row['gps_area_acre'],
                "forest_type" => $row['forest_type'],
                "f_zone" => $row['f_zone'], // Added from the input
                "gross_area_acre" => $row['gross_area_acre'], // Added from the input
                "area_hec" => $row['area_hec'], // Added from the input
                "legal_state" => $row['legal_state'], // Added from the input
                "civil_tehsil" => $row['civil_tehsil'] // Added from the input

            ]
        ];
    }

    $geojson = [
        "type" => "FeatureCollection",
        "features" => $features
    ];

    echo json_encode(["status" => "success", "data" => $geojson]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>