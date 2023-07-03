<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../'); // Imposta il percorso corretto del file .env
$dotenv->load();

class Database
{
    // connessione al database
    public function getConnection()
    {
        $this->conn = null;
        try {
            $host = $_ENV['DB_HOST'];
            $db_name = $_ENV['DB_NAME'];
            $username = $_ENV['DB_USERNAME'];
            $password = $_ENV['DB_PASSWORD'];

            $this->conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Errore di connessione: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
