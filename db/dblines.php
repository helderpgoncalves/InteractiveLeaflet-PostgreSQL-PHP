<?php
header("Content-Type: application/json");

//move connection to one file
require_once './connection/connect.php';

try {
    $result = $pdo->query("SELECT *, ST_AsGeoJSON(line,5) as geojson FROM occurrences_line");

    $features = [];
    foreach ($result as $row) {
        unset($row['geometry']);
        $geometry = $row['geojson'] = json_decode($row['geojson']);
        unset($row['geojson']);
        $feature = ["type" => "Feature", "geometry" => $geometry, "properties" => $row];
        array_push($features, $feature);
    }

    $featuresCollection1 = ["type" => "FeatureCollection", "features" => $features];
    echo json_encode($featuresCollection1);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>