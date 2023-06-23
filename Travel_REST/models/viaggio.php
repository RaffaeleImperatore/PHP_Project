<?php

class Viaggio
{
    private $conn;
    private $table_name = "viaggi";
    // proprietà di un libro
    public $id;
    public $paese_partenza;
    public $paese_destinazione;
    public $posti_rimasti;
    // costruttore
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // READ viaggi con filtri
// READ viaggi con filtri
public function read($paese_partenza = null, $paese_destinazione = null, $posti_rimasti = null)
{
    $query = "SELECT
                a.id, a.paese_partenza, a.paese_destinazione, a.posti_rimasti
            FROM
                " . $this->table_name . " a ";

    $where = [];
    $params = [];

    // Aggiungi filtro per paese di partenza
    if ($paese_partenza !== null) {
        $where[] = "a.paese_partenza = ?";
        $params[] = $paese_partenza;
    }

    // Aggiungi filtro per paese di destinazione
    if ($paese_destinazione !== null) {
        $where[] = "a.paese_destinazione = ?";
        $params[] = $paese_destinazione;
    }

    // Aggiungi filtro per posti rimasti
    if ($posti_rimasti !== null) {
        $where[] = "a.posti_rimasti >= ?";
        $params[] = $posti_rimasti;
    }

    // Componi la query finale
    if (!empty($where)) {
        $query .= "WHERE " . implode(" AND ", $where);
    }

    $stmt = $this->conn->prepare($query);

    // Bind dei parametri
    for ($i = 0; $i < count($params); $i++) {
        $stmt->bindParam($i + 1, $params[$i]);
    }

    // Esegui la query
    $stmt->execute();

    return $stmt;
}

    public function exists()
    {
        // Query per verificare l'esistenza del viaggio
        $query = "SELECT id FROM " . $this->table_name . " WHERE id = :id";

        // Preparazione dello statement
        $stmt = $this->conn->prepare($query);

        // Binding dei parametri
        $stmt->bindParam(":id", $this->id);

        // Esecuzione della query
        $stmt->execute();

        // Verifica se il viaggio esiste
        return $stmt->rowCount() > 0;
    }


    // CREARE Viaggio

    public function create()
    {
        // Verifica che il paese di partenza sia presente
        $query_paese_partenza = "SELECT nome FROM paesi WHERE nome = :paese_partenza";
        $stmt_paese_partenza = $this->conn->prepare($query_paese_partenza);
        $stmt_paese_partenza->bindParam(":paese_partenza", $this->paese_partenza);
        $stmt_paese_partenza->execute();

        // Verifica che il paese di destinazione sia presente
        $query_paese_destinazione = "SELECT nome FROM paesi WHERE nome = :paese_destinazione";
        $stmt_paese_destinazione = $this->conn->prepare($query_paese_destinazione);
        $stmt_paese_destinazione->bindParam(":paese_destinazione", $this->paese_destinazione);
        $stmt_paese_destinazione->execute();

        // Controlla se i paesi non sono presenti
        if ($stmt_paese_partenza->rowCount() == 0 || $stmt_paese_destinazione->rowCount() == 0) {
            http_response_code(400);
            echo json_encode(array("message" => "Impossibile creare il viaggio. Paesi non presenti nel Database."));
            return false;
        }

        // Verifica se esiste già un viaggio con lo stesso paese di partenza e destinazione
        $query_verifica = "SELECT id FROM " . $this->table_name . " WHERE paese_partenza = :paese_partenza AND paese_destinazione = :paese_destinazione";
        $stmt_verifica = $this->conn->prepare($query_verifica);
        $stmt_verifica->bindParam(":paese_partenza", $this->paese_partenza);
        $stmt_verifica->bindParam(":paese_destinazione", $this->paese_destinazione);
        $stmt_verifica->execute();

        // Controlla se esiste già un viaggio con gli stessi paesi
        if ($stmt_verifica->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(array("message" => "Impossibile creare il viaggio. Viaggio già esistente."));
            return false;
        }

        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    paese_partenza = :paese_partenza,
                    paese_destinazione = :paese_destinazione,
                    posti_rimasti = :posti_rimasti";

        $stmt = $this->conn->prepare($query);

        $this->paese_partenza = htmlspecialchars(strip_tags($this->paese_partenza));
        $this->paese_destinazione = htmlspecialchars(strip_tags($this->paese_destinazione));
        $this->posti_rimasti = htmlspecialchars(strip_tags($this->posti_rimasti));

        // binding
        $stmt->bindParam(":paese_partenza", $this->paese_partenza);
        $stmt->bindParam(":paese_destinazione", $this->paese_destinazione);
        $stmt->bindParam(":posti_rimasti", $this->posti_rimasti);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    // AGGIORNARE Viaggio

    public function update()
    {
        // Verifica se i paesi di partenza e destinazione esistono nella tabella dei paesi
        $check_countries_query = "SELECT COUNT(*) as count FROM paesi WHERE nome = :paese_partenza OR nome = :paese_destinazione";
        $check_countries_stmt = $this->conn->prepare($check_countries_query);
        $check_countries_stmt->bindParam(":paese_partenza", $this->paese_partenza);
        $check_countries_stmt->bindParam(":paese_destinazione", $this->paese_destinazione);
        $check_countries_stmt->execute();
        $countries_count = $check_countries_stmt->fetch(PDO::FETCH_ASSOC)['count'];

        if ($countries_count < 2) {
            return false; // Uno o entrambi i paesi non esistono nella tabella dei paesi
        }

        // Verifica se esiste già un viaggio con gli stessi paesi di partenza e destinazione, escludendo il viaggio corrente
        $check_existing_query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE id <> :id AND paese_partenza = :paese_partenza AND paese_destinazione = :paese_destinazione";
        $check_existing_stmt = $this->conn->prepare($check_existing_query);
        $check_existing_stmt->bindParam(":id", $this->id);
        $check_existing_stmt->bindParam(":paese_partenza", $this->paese_partenza);
        $check_existing_stmt->bindParam(":paese_destinazione", $this->paese_destinazione);
        $check_existing_stmt->execute();
        $existing_count = $check_existing_stmt->fetch(PDO::FETCH_ASSOC)['count'];

        if ($existing_count > 0) {
            return false; // Un viaggio con gli stessi paesi di partenza e destinazione già esiste
        }

        // Query di aggiornamento
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    paese_partenza = :paese_partenza,
                    paese_destinazione = :paese_destinazione,
                    posti_rimasti = :posti_rimasti
                WHERE
                    id = :id";

        $stmt = $this->conn->prepare($query);

        // Binding dei parametri
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":paese_partenza", $this->paese_partenza);
        $stmt->bindParam(":paese_destinazione", $this->paese_destinazione);
        $stmt->bindParam(":posti_rimasti", $this->posti_rimasti);

        // Esecuzione della query
        if ($stmt->execute()) {
            return true; // Aggiornamento riuscito
        }

        return false; // Errore nell'esecuzione della query
    }

    // CANCELLARE Viaggio

    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";


        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));


        $stmt->bindParam(1, $this->id);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
