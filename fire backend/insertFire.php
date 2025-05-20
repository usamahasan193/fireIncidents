<?php
// Database configuration
// Include the database connection file
require 'dbConn.php'; // Make sure the path is correct based on your directory structure

// Retrieve data from the POST request (user input from form)
$p_f_division_id = isset($_POST['p_f_division_id']) ? $_POST['p_f_division_id'] : null;
$p_forest_unique_id = isset($_POST['p_forest_unique_id']) ? $_POST['p_forest_unique_id'] : null;
$p_tehsil_name = isset($_POST['p_tehsil_name']) ? $_POST['p_tehsil_name'] : null;
$p_f_subdiv_range = isset($_POST['p_f_subdiv_range']) ? $_POST['p_f_subdiv_range'] : null;
$p_f_block_name = isset($_POST['p_f_block_name']) ? $_POST['p_f_block_name'] : null;
$p_f_beat_name = isset($_POST['p_f_beat_name']) ? $_POST['p_f_beat_name'] : null;
$p_f_compartment_no = isset($_POST['p_f_compartment_no']) ? $_POST['p_f_compartment_no'] : null;
$p_fire_site = isset($_POST['p_fire_site']) ? $_POST['p_fire_site'] : null;
$p_incharg_name = isset($_POST['p_incharg_name']) ? $_POST['p_incharg_name'] : null;
$p_forest_guard_name = isset($_POST['p_forest_guard_name']) ? $_POST['p_forest_guard_name'] : null;
$p_block_officer_name = isset($_POST['p_block_officer_name']) ? $_POST['p_block_officer_name'] : null;
$p_firewatchers_count = isset($_POST['p_firewatchers_count']) ? $_POST['p_firewatchers_count'] : null;
$p_labore_count = isset($_POST['p_labore_count']) ? $_POST['p_labore_count'] : null;
$p_informer_name = isset($_POST['p_informer_name']) ? $_POST['p_informer_name'] : null;
$p_first_information = isset($_POST['p_first_information']) ? $_POST['p_first_information'] : null;
$p_fire_date_time = isset($_POST['p_fire_date_time']) ? $_POST['p_fire_date_time'] : null; // Ensure format is correct
$p_kind_of_fire = isset($_POST['p_kind_of_fire']) ? $_POST['p_kind_of_fire'] : null;
$p_status_of_coupe = isset($_POST['p_status_of_coupe']) ? $_POST['p_status_of_coupe'] : null;
$p_composition_of_the_area = isset($_POST['p_composition_of_the_area']) ? $_POST['p_composition_of_the_area'] : null;
$p_cause_of_fire = isset($_POST['p_cause_of_fire']) ? $_POST['p_cause_of_fire'] : null;
$p_nature_and_extent_of_damage = isset($_POST['p_nature_and_extent_of_damage']) ? $_POST['p_nature_and_extent_of_damage'] : null;
$p_access_to_terrain_from_local_road = isset($_POST['p_access_to_terrain_from_local_road']) ? $_POST['p_access_to_terrain_from_local_road'] : null;
$p_temperature_on_incident_day = isset($_POST['p_temperature_on_incident_day']) ? $_POST['p_temperature_on_incident_day'] : null;
$p_present_status = isset($_POST['p_present_status']) ? $_POST['p_present_status'] : null;
$p_estimated_value_of_damage_rs = isset($_POST['p_estimated_value_of_damage_rs']) ? $_POST['p_estimated_value_of_damage_rs'] : null;
$p_adjoining_communities = isset($_POST['p_adjoining_communities']) ? $_POST['p_adjoining_communities'] : null;
$p_nearest_source_of_water = isset($_POST['p_nearest_source_of_water']) ? $_POST['p_nearest_source_of_water'] : null;
$p_remedial_measures_taken = isset($_POST['p_remedial_measures_taken']) ? $_POST['p_remedial_measures_taken'] : null;
$p_post_fire_treatment_require_to_crop = isset($_POST['p_post_fire_treatment_require_to_crop']) ? $_POST['p_post_fire_treatment_require_to_crop'] : null;
$p_damage_report_no = isset($_POST['p_damage_report_no']) ? $_POST['p_damage_report_no'] : null;
$p_fir_police_report_no = isset($_POST['p_fir_police_report_no']) ? $_POST['p_fir_police_report_no'] : null;
$p_remarks_recommendations = isset($_POST['p_remarks_recommendations']) ? $_POST['p_remarks_recommendations'] : null;
$p_latitude = isset($_POST['p_latitude']) ? $_POST['p_latitude'] : null;
$p_longitude = isset($_POST['p_longitude']) ? $_POST['p_longitude'] : null;
$p_fire_boundary_geojson = isset($_POST['p_fire_boundary_geojson']) ? $_POST['p_fire_boundary_geojson'] : null;


// Prepare the SQL query to insert fire incident using user-provided data
$query = "
SELECT public.insert_fire_incident(
    :p_f_division_id,                       -- p_f_division_id
    :p_forest_unique_id,                     -- p_forest_unique_id
    :p_tehsil_name,                          -- p_tehsil_name
    :p_f_subdiv_range,                       -- p_f_subdiv_range
    :p_f_block_name,                        -- p_f_block_name
    :p_f_beat_name,                         -- p_f_beat_name
    :p_f_compartment_no,                     -- p_f_compartment_no
    :p_fire_site,                            -- p_fire_site
    :p_incharg_name,                         -- p_incharg_name
    :p_forest_guard_name,                    -- p_forest_guard_name
    :p_block_officer_name,                   -- p_block_officer_name
    :p_firewatchers_count,                   -- p_firewatchers_count
    :p_labore_count,                         -- p_labore_count
    :p_informer_name,                        -- p_informer_name
    :p_first_information,                    -- p_first_information
    :p_fire_date_time,                       -- p_fire_date_time
    :p_kind_of_fire,                         -- p_kind_of_fire
    :p_status_of_coupe,                      -- p_status_of_coupe
    :p_composition_of_the_area,              -- p_composition_of_the_area
    :p_cause_of_fire,                        -- p_cause_of_fire
    :p_nature_and_extent_of_damage,          -- p_nature_and_extent_of_damage
    :p_access_to_terrain_from_local_road,    -- p_access_to_terrain_from_local_road
    :p_temperature_on_incident_day,          -- p_temperature_on_incident_day
    :p_present_status,                       -- p_present_status
    :p_estimated_value_of_damage_rs,         -- p_estimated_value_of_damage_rs
    :p_adjoining_communities,                -- p_adjoining_communities
    :p_nearest_source_of_water,              -- p_nearest_source_of_water
    :p_remedial_measures_taken,              -- p_remedial_measures_taken
    :p_post_fire_treatment_require_to_crop,  -- p_post_fire_treatment_require_to_crop
    :p_damage_report_no,                     -- p_damage_report_no
    :p_fir_police_report_no,                 -- p_fir_police_report_no
    :p_remarks_recommendations,              -- p_remarks_recommendations
    :p_latitude,                             -- p_latitude
    :p_longitude,                            -- p_longitude
    :p_fire_boundary_geojson                 -- p_fire_boundary_geojson
);";

// Prepare the statement
$stmt = $pdo->prepare($query);

// Bind parameters
$stmt->bindParam(':p_f_division_id', $p_f_division_id, PDO::PARAM_INT);
$stmt->bindParam(':p_forest_unique_id', $p_forest_unique_id, PDO::PARAM_INT);
$stmt->bindParam(':p_tehsil_name', $p_tehsil_name, PDO::PARAM_STR);
$stmt->bindParam(':p_f_subdiv_range', $p_f_subdiv_range, PDO::PARAM_STR);
$stmt->bindParam(':p_f_block_name', $p_f_block_name, PDO::PARAM_STR);
$stmt->bindParam(':p_f_beat_name', $p_f_beat_name, PDO::PARAM_STR);
$stmt->bindParam(':p_f_compartment_no', $p_f_compartment_no, PDO::PARAM_INT);
$stmt->bindParam(':p_fire_site', $p_fire_site, PDO::PARAM_STR);
$stmt->bindParam(':p_incharg_name', $p_incharg_name, PDO::PARAM_STR);
$stmt->bindParam(':p_forest_guard_name', $p_forest_guard_name, PDO::PARAM_STR);
$stmt->bindParam(':p_block_officer_name', $p_block_officer_name, PDO::PARAM_STR);
$stmt->bindParam(':p_firewatchers_count', $p_firewatchers_count, PDO::PARAM_INT);
$stmt->bindParam(':p_labore_count', $p_labore_count, PDO::PARAM_INT);
$stmt->bindParam(':p_informer_name', $p_informer_name, PDO::PARAM_STR);
$stmt->bindParam(':p_first_information', $p_first_information, PDO::PARAM_STR);
$stmt->bindParam(':p_fire_date_time', $p_fire_date_time, PDO::PARAM_STR);
$stmt->bindParam(':p_kind_of_fire', $p_kind_of_fire, PDO::PARAM_STR);
$stmt->bindParam(':p_status_of_coupe', $p_status_of_coupe, PDO::PARAM_STR);
$stmt->bindParam(':p_composition_of_the_area', $p_composition_of_the_area, PDO::PARAM_STR);
$stmt->bindParam(':p_cause_of_fire', $p_cause_of_fire, PDO::PARAM_STR);
$stmt->bindParam(':p_nature_and_extent_of_damage', $p_nature_and_extent_of_damage, PDO::PARAM_STR);
$stmt->bindParam(':p_access_to_terrain_from_local_road', $p_access_to_terrain_from_local_road, PDO::PARAM_STR);
$stmt->bindParam(':p_temperature_on_incident_day', $p_temperature_on_incident_day, PDO::PARAM_STR);
$stmt->bindParam(':p_present_status', $p_present_status, PDO::PARAM_STR);
$stmt->bindParam(':p_estimated_value_of_damage_rs', $p_estimated_value_of_damage_rs, PDO::PARAM_INT);
$stmt->bindParam(':p_adjoining_communities', $p_adjoining_communities, PDO::PARAM_STR);
$stmt->bindParam(':p_nearest_source_of_water', $p_nearest_source_of_water, PDO::PARAM_STR);
$stmt->bindParam(':p_remedial_measures_taken', $p_remedial_measures_taken, PDO::PARAM_STR);
$stmt->bindParam(':p_post_fire_treatment_require_to_crop', $p_post_fire_treatment_require_to_crop, PDO::PARAM_STR);
$stmt->bindParam(':p_damage_report_no', $p_damage_report_no, PDO::PARAM_STR);
$stmt->bindParam(':p_fir_police_report_no', $p_fir_police_report_no, PDO::PARAM_STR);
$stmt->bindParam(':p_remarks_recommendations', $p_remarks_recommendations, PDO::PARAM_STR);
$stmt->bindParam(':p_latitude', $p_latitude, PDO::PARAM_STR);
$stmt->bindParam(':p_longitude', $p_longitude, PDO::PARAM_STR);
$stmt->bindParam(':p_fire_boundary_geojson', $p_fire_boundary_geojson, PDO::PARAM_STR);

// Execute the query
try {
    $stmt->execute();

    // Fetch the result which should contain the `fire_id`
    $fire_id = $pdo->lastInsertId();  // Assuming fire_id is auto-generated

    // Return the fire_id in a JSON response
    echo json_encode([
        "status" => "success",
        "fire_id" => $fire_id
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "status" => "error",
        "message" => "An error occurred while inserting the fire incident: " . $e->getMessage()
    ]);
}

// Close the database connection (not necessary with PDO as it's closed automatically)
$pdo = null;
?>
