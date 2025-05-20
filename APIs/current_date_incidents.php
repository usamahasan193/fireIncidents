<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

require 'db.php';

// Query to fetch fire incidents for the current date, including all columns
$query = "SELECT fire_id, tehsil_name, f_subdiv_range, f_beat_name, incharg_name, forest_guard_name, 
            firewatchers_count, first_information, kind_of_fire, status_of_coupe, cause_of_fire, 
            present_status, latitude, longitude, fire_date_time, fire_site, duringf_image1, duringf_image2, postf_image1, postf_image2
            FROM fire_incidents
            WHERE fire_date_time::date = CURRENT_DATE";

$result = pg_query($dbconn, $query);

if (!$result) {
    echo json_encode(["status" => "error", "message" => "Query failed: " . pg_last_error()]);
    exit;
}

// Fetch and format the result as GeoJSON
$features = [];
while ($row = pg_fetch_assoc($result)) {

    // Ensure latitude and longitude are floats
    $latitude = (float)$row['latitude'];
    $longitude = (float)$row['longitude'];

    // Convert fire_date_time to a string representation if needed.
    $fireDateTime = $row['fire_date_time'];

    // Handle null values with 'NA'
    $tehsilName = $row['tehsil_name'] === null ? 'NA' : $row['tehsil_name'];
    $fSubdivRange = $row['f_subdiv_range'] === null ? 'NA' : $row['f_subdiv_range'];
    $fBeatName = $row['f_beat_name'] === null ? 'NA' : $row['f_beat_name'];
    $inchargeName = $row['incharg_name'] === null ? 'NA' : $row['incharg_name'];
    $forestGuard = $row['forest_guard_name'] === null ? 'NA' : $row['forest_guard_name'];
    $firstInformation = $row['first_information'] === null ? 'NA' : $row['first_information'];
    $kindOfFire = $row['kind_of_fire'] === null ? 'NA' : $row['kind_of_fire'];
    $statusOfCoupe = $row['status_of_coupe'] === null ? 'NA' : $row['status_of_coupe'];
    $causeOfFire = $row['cause_of_fire'] === null ? 'NA' : $row['cause_of_fire'];
    $presentStatus = $row['present_status'] === null ? 'NA' : $row['present_status'];
    $fireSite = $row['fire_site'] === null ? 'NA' : $row['fire_site'];

    // Handle null values for image fields as well
    $duringfImage1 = $row['duringf_image1'];
    $duringfImage2 = $row['duringf_image2'];
    $postfImage1 = $row['postf_image1'];
    $postfImage2 = $row['postf_image2'];

    $features[] = [
        'type' => 'Feature',
        'geometry' => [
            'type' => 'Point',
            'coordinates' => [$longitude, $latitude] // Correct order: [longitude, latitude]
        ],
        'properties' => [
            'fire_id' => $row['fire_id'],
            'tehsil_name' => $tehsilName,
            'f_subdiv_range' => $fSubdivRange,
            'f_beat_name' => $fBeatName,
            'incharg_name' => $inchargeName,
            'forest_guard_name' => $forestGuard,
            'firewatchers_count' => (int)$row['firewatchers_count'],
            'first_information' => $firstInformation,
            'kind_of_fire' => $kindOfFire,
            'status_of_coupe' => $statusOfCoupe,
            'cause_of_fire' => $causeOfFire,
            'present_status' => $presentStatus,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'fire_date_time' => $fireDateTime,
            'fire_site' => $fireSite,
            'duringf_image1' => $duringfImage1,
            'duringf_image2' => $duringfImage2,
            'postf_image1' => $postfImage1,
            'postf_image2' => $postfImage2,
        ]
    ];
}

// Close database connection
pg_close($dbconn);

// Return response as GeoJSON
$geojson = [
    'type' => 'FeatureCollection',
    'features' => $features
];

echo json_encode($geojson);
?>