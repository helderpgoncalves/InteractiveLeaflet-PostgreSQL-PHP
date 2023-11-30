<?php
header("Content-Type: application/json");

//move connection to one file
require_once './connection/connect.php';

try {
    $result = $pdo->query("SELECT *, ST_AsGeoJSON(geometry,5) as geojson FROM occurrences_circle");

    $features = [];
    foreach ($result as $row) {
        unset($row['geometry']);
        $geometry = $row['geojson'] = json_decode($row['geojson']);
        unset($row['geojson']);
        $feature = ["type" => "Feature", "geometry" => $geometry, "properties" => $row];
        array_push($features, $feature);
    }

    $featuresCollection = ["type" => "FeatureCollection", "features" => $features];
    echo json_encode($featuresCollection);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>