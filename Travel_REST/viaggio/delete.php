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

if (isset($data->id)) {
    $viaggio->id = $data->id;

    // Verifica se il viaggio esiste nel database
    if ($viaggio->exists()) {
        if ($viaggio->delete()) {
            http_response_code(200);
            echo json_encode(array("risposta" => "Il viaggio è stato eliminato"));
        } else {
            //503 service unavailable
            http_response_code(503);
            echo json_encode(array("risposta" => "Impossibile eliminare il viaggio."));
        }
    } else {
        //404 not found
        http_response_code(404);
        echo json_encode(array("risposta" => "Il viaggio non esiste."));
    }
} else {
    //400 bad request
    http_response_code(400);
    echo json_encode(array("risposta" => "ID del viaggio mancante."));
}
