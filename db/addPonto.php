<?php
header("Content-Type: application/json");

$pontoInserir = $_POST['shape_for_db'];

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

$result = $pdo->query("INSERT INTO occurrences_point");


?>