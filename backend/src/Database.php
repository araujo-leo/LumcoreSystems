<?php
namespace App;

class Database {
    private $host = 'localhost';
    private $dbname = 'landing_db';
    private $user = 'root';
    private $pass = '03098100';
    public $conn;

    public function __construct() {
        try {
            $this->conn = new \PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Erro na conexão com o banco: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}