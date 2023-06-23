<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../models/viaggio.php';

$database = new Database();
$db = $database->getConnection();

$viaggio = new Viaggio($db);

// Leggi i parametri di ricerca dai parametri GET
$paese_partenza = isset($_GET['paese_partenza']) ? $_GET['paese_partenza'] : null;
$paese_destinazione = isset($_GET['paese_destinazione']) ? $_GET['paese_destinazione'] : null;
$posti_rimasti = isset($_GET['posti_rimasti']) ? $_GET['posti_rimasti'] : null;

$stmt = $viaggio->read($paese_partenza, $paese_destinazione, $posti_rimasti);
$num = $stmt->rowCount();

if ($num > 0) {
    $viaggi_arr = [];
    $viaggi_arr["records"] = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $viaggio_item = array(
            "id" => $id,
            "paese_partenza" => $paese_partenza,
            "paese_destinazione" => $paese_destinazione,
            "posti_rimasti" => $posti_rimasti
        );

        array_push($viaggi_arr["records"], $viaggio_item);
    }

    echo json_encode($viaggi_arr);
} else {
    echo json_encode(
        array("message" => "Nessun Viaggio Trovato.")
    );
}
?>

