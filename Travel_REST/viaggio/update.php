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

// ...
$data = json_decode(file_get_contents("php://input"));

if (isset($data->id) && isset($data->paese_partenza) && isset($data->paese_destinazione) && isset($data->posti_rimasti)) {
    $viaggio->id = $data->id;
    $viaggio->paese_partenza = $data->paese_partenza;
    $viaggio->paese_destinazione = $data->paese_destinazione;
    $viaggio->posti_rimasti = $data->posti_rimasti;

    if ($viaggio->update()) {
        http_response_code(200);
        echo json_encode(["risposta" => "Viaggio aggiornato"]);
    } else {
        // 503 service unavailable
        http_response_code(503);
        echo json_encode(["risposta" => "Impossibile aggiornare il viaggio"]);
    }
} else {
    // 400 bad request
    http_response_code(400);
    echo json_encode(["risposta" => "Dati incompleti per l'aggiornamento del viaggio"]);
}

