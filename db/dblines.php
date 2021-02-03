<?php
header("Content-Type: application/json");

    $user = "postgres";
    $pwd = "";
    $host = "localhost";
    $database = "istp";
    $port = '5432';

    $dsn = "pgsql:host=$host;dbname=$database;port=$port";

    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ];

    $pdo = new PDO($dsn, 'postgres', 'admin', $opt);

    $result = $pdo->query("SELECT *, ST_AsGeoJSON(line,5) as geojson FROM occurrences_line");

    $features = [];
    foreach ($result AS $row){
        unset($row['geometry']);
        $geometry = $row['geojson'] = json_decode($row['geojson']);
        unset($row['geojson']);
        $feature = ["type" => "Feature", "geometry" => $geometry, "properties" => $row];
        array_push($features, $feature);
    }

    $featuresCollection1 = ["type" => "FeatureCollection", "features" => $features];
    echo json_encode($featuresCollection1);

    function inserirlinha(){
        //WOrk
    }

?>