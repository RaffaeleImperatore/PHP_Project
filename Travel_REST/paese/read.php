<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// includiamo database.php e paese.php per poterli usare
include_once '../config/database.php';
include_once '../models/paese.php';
// creiamo un nuovo oggetto Database e ci colleghiamo al nostro database
$database = new Database();
$db = $database->getConnection();
// Creiamo un nuovo oggetto Paese
$paese = new Paese($db);
// query products
$stmt = $paese->read();
$num = $stmt->rowCount();
// se vengono trovati paesi nel database
if($num>0) {
    // array di paesi
    $paesi_arr = [];
    $paesi_arr["records"] = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $paese_item = array(
            "nome" => $nome,
            "id" => $id
        );
        array_push($paesi_arr["records"], $paese_item);
    }
    echo json_encode($paesi_arr);
} else {
    echo json_encode(
        array("message" => "Nessun Paese Trovato.")
    );
}
