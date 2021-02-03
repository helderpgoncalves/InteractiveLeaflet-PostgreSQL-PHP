<?php
header("Content-Type: application/json");

$user = "postgres";
$pwd = "";
$host = "localhost";
$database = "istp";
$port = '5432';

$tipo = $_POST['tipo'];

$dsn = "pgsql:host=$host;dbname=$database;port=$port";

$opt = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

$pdo = new PDO($dsn, 'postgres', 'admin', $opt);

if($tipo == 'maker'){ // SE FOR UM PONTO
$features = [];
foreach ($result as $row) {
    unset($row['geometry']);
    $geometry = $row['geojson'] = json_decode($row['geojson']);
    unset($row['geojson']);
    $feature = ["type" => "Feature", "geometry" => $geometry, "properties" => $row];
    array_push($features, $feature);
}

$featuresCollection = ["type" => "FeatureCollection", "features" => $features];

$idnovo = sizeof($features);
echo "TAMANHO " . $idnovo . "<br>";

$datetime = date("Y-m-d h:i:sa");
echo "Today is " . $datetime . "<br>";

//$nome = $_POST['nomeMarker'];
$lat = isset($_POST['latitude']) ? $_POST['latitude'] : null;
$long = isset($_POST['longitude']) ? $_POST['longitude'] : null;

echo "LAT " . $lat . "<br>";
echo "LONG " . $long . "<br>";

$result = $pdo->query("INSERT INTO occurrences_point(id, name, type, date, point, image) VALUES 
    ($idnovo + 1,'NovoMarker',1,'$datetime',ST_SetSRID(ST_MakePoint($long, $lat),4326),54654)");
    
} else if ($tipo == "polyline"){
    $result = $pdo->query("SELECT *, ST_AsGeoJSON(line,5) as geojson FROM occurrences_line");

    $features = [];
    foreach ($result as $row) {
        unset($row['geometry']);
        $geometry = $row['geojson'] = json_decode($row['geojson']);
        unset($row['geojson']);
        $feature = ["type" => "Feature", "geometry" => $geometry, "properties" => $row];
        array_push($features, $feature);
    }

    $featuresCollection = ["type" => "FeatureCollection", "features" => $features];

    $idnovo = sizeof($features);
    echo "TAMANHO " . $idnovo;

    $datetime = date("Y-m-d h:i:sa");
    echo "Today is " . $datetime;

    $CoordsPHP = isset($_POST['coordenadas']) ? $_POST['coordenadas'] : null;

    $data = json_decode($CoordsPHP, TRUE);

    $str = '';

    for ($i = 0; $i < count($data); $i = $i + 2) {

        $lat = $data[$i]['lat'];
        $long = $data[$i]['lng'];

        echo $lat;
        echo $long;

        $str .= ' ST_MakePoint(' . $long . ',' . $lat . '),';
    }

    echo $str;

    $newString = substr($str, 0, -1);

    $result = $pdo->query("INSERT INTO occurrences_line(id, name, type, date, line, image) VALUES 
    ($idnovo + 1,'NovoLine',1,'$datetime',ST_SetSRID(ST_MakeLine($newString), 4326), 54654)");

} else if ($tipo == "polygon") { // SE FOR UM POLIGONO

    $result = $pdo->query("SELECT *, ST_AsGeoJSON(geometry,5) as geojson FROM occurrences_polygon");

    $features = [];
    foreach ($result as $row) {
        unset($row['geometry']);
        $geometry = $row['geojson'] = json_decode($row['geojson']);
        unset($row['geojson']);
        $feature = ["type" => "Feature", "geometry" => $geometry, "properties" => $row];
        array_push($features, $feature);
    }

    $featuresCollection = ["type" => "FeatureCollection", "features" => $features];

    $idnovo = sizeof($features);
    echo "TAMANHO " . $idnovo;

    $datetime = date("Y-m-d h:i:sa");
    echo "Today is " . $datetime;

    //$nome = $_POST['nomeMarker'];
    $CoordsPHP = isset($_POST['coordenadas']) ? $_POST['coordenadas'] : null;

    $data = json_decode($CoordsPHP, TRUE);

    $var = count($data[0]);
    echo ($var);

    $strlastpoint = '';
    $str = '';

    for ($i = 0; $i < count($data[0]); $i = $i + 2) {

        $lat = $data[0][$i]['lat'];
        $long = $data[0][$i]['lng'];
        $str .= $long . ' ' . '' . $lat . ',';
    }

    $lat1 = $data[0][0]['lat'];
    $long1 = $data[0][0]['lng'];

    $strlastpoint .= $long1 . ' ' . '' . $lat1;

    echo $str . $strlastpoint;

    $result = $pdo->query("INSERT INTO occurrences_polygon(id, name, type, date, geometry, image) VALUES 
    ($idnovo + 1,'NovoLine',1,'$datetime',ST_GeomFromText('POLYGON (($str $strlastpoint))',4326), 54654)");
}
?>