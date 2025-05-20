<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
require 'db.php';

try {
    // Get parameters
    $marker = $_GET['marker'] ?? null;
    $buffer_radius = $_GET['buffer_radius'] ?? null;
    $zone_name = $_GET['zone_name'] ?? null;
    $resource_name = $_GET['resource_name'] ?? null;
    $staff_type = $_GET['staff_type'] ?? null;

    // Validate
    if (!$resource_name) {
        throw new Exception("Resource Name is required");
    }
    if ($resource_name === 'fieldstaff' && !$staff_type) {
        throw new Exception("Staff type is required for fieldstaff");
    }

    // Build query
    if ($marker && $buffer_radius) {
        $coords = explode(',', $marker);
        if (count($coords) !== 2) throw new Exception("Invalid marker format");
        
        $lat = floatval($coords[0]);
        $lon = floatval($coords[1]);
        
        if ($resource_name === 'fieldstaff') {
            $query = "SELECT * FROM get_filtered_fieldstaff_v4($1, $2, $3, $4)";
            $params = [$lat, $lon, $buffer_radius, $staff_type];
        } else {
            $query = "SELECT * FROM get_filtered_resources_v4($1, $2, $3, $4)";
            $params = [$lat, $lon, $buffer_radius, $resource_name];
        }
    } 
    elseif ($zone_name) {
        $query = "SELECT * FROM get_filtered_resources_zones($1, $2, $3)";
        $params = [$resource_name, 'Zone', $zone_name];
    } 
    else {
        throw new Exception("Either marker+buffer or zone_name required");
    }

    // Execute
    $result = pg_query_params($dbconn, $query, $params);
    if (!$result) throw new Exception(pg_last_error($dbconn));
    
    // Process results
    $data = pg_fetch_all($result) ?: [];
    
    // Additional filtering for fieldstaff in zones
    if ($resource_name === 'fieldstaff' && $zone_name) {
        $data = array_values(array_filter($data, function($item) use ($staff_type) {
            $rd = json_decode($item['resource_data'] ?? '{}', true);
            return ($rd['type'] ?? null) === $staff_type;
        }));
    }

    // Format response
    foreach ($data as &$item) {
        if (isset($item['resource_data'])) {
            $rd = json_decode($item['resource_data'], true);
            if (isset($rd['latitude']) && isset($rd['longitude'])) {
                $item['coordinates'] = [
                    'latitude' => $rd['latitude'],
                    'longitude' => $rd['longitude']
                ];
            }
        }
    }

    echo json_encode([
        'status' => 'success',
        'data' => $data
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
?>