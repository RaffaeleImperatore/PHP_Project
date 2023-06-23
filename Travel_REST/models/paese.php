<?php

class Paese
{
    private $conn;
    private $table_name = "paesi";
    // proprietà di un paese
    public $id;
    public $nome;
    // costruttore
    public function __construct($db)
    {
        $this->conn = $db;
    }


    // READ paesi
    public function read()
    {
        // select all
        $query = "SELECT
                       a.id, a.nome
                    FROM
                   " . $this->table_name . " a";
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }


    // CREARE PAESE
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " (nome) VALUES (:nome)";
        $stmt = $this->conn->prepare($query);
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        // binding
        $stmt->bindParam(":nome", $this->nome);
        // execute query
        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }



    // AGGIORNARE PAESE
    public function update()
    {
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    nome = :nome WHERE id= :id";


        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));

        // binding
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":id", $this->id);

        // execute the query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }


    // CANCELLARE PAESE
    public function delete()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE nome = ?";


        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));


        $stmt->bindParam(1, $this->nome);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
