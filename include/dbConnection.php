<?php
// Třída pro připojení k databázi
class Database {
    private $servername;
    private $username;
    private $password;
    private $database;
    private $conn;

    public function __construct($servername, $username, $password, $database) {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }

    // Metoda pro připojení k databázi
    public function connect() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->database);
        
        // Kontrola spojení
        if ($this->conn->connect_error) {
            die("Spojení s databází selhalo: " . $this->conn->connect_error);
        }

        return $this->conn;
    }

    // Metoda pro získání připojení k databázi
    public function getConnection() {
        return $this->conn;
    }
}

// Vytvoření instance třídy Database
$db = new Database("localhost", "root", "", "rapnews_database");

// Připojení k databázi
$conn = $db->connect();
?>
