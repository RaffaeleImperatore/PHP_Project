<?php

//headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/viaggio.php';

$database = new Database();
$db = $database->getConnection();
$viaggio = new Viaggio($db);
$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->paese_partenza) &&
    !empty($data->paese_destinazione) &&
    !empty($data->posti_rimasti)
) {
    $viaggio->paese_partenza = $data->paese_partenza;
    $viaggio->paese_destinazione = $data->paese_destinazione;
    $viaggio->posti_rimasti = $data->posti_rimasti;

    if ($viaggio->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Viaggio creato correttamente."));
    } else {
        //503 servizio non disponibile
        http_response_code(503);
        echo json_encode(array("message" => "Impossibile creare il viaggio."));
    }
} else {
    //400 bad request
    http_response_code(400);
    echo json_encode(array("message" => "Impossibile creare il viaggio. I dati sono incompleti."));
}
?>