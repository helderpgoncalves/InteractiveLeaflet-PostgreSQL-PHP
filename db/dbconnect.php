<?php
header("Content-Type: application/json");
    
    // Conexão à BD
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

    $result = $pdo->query("SELECT *, ST_AsGeoJSON(point,5) as geojson FROM occurrences_point");

    $features = [];
    foreach ($result AS $row){
        unset($row['geometry']);
        $geometry = $row['geojson'] = json_decode($row['geojson']);
        unset($row['geojson']);
        $feature = ["type" => "Feature", "geometry" => $geometry, "properties" => $row];
        array_push($features, $feature);
    }

    $featuresCollection = ["type" => "FeatureCollection", "features" => $features];
    echo json_encode($featuresCollection);


    function inserirponto(){
        //WOrk
    }

?>





