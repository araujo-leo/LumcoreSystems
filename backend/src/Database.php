<?php
namespace App;

require_once __DIR__ . '/../vendor/autoload.php'; 

use Dotenv\Dotenv;

class Database {
    private $conn;

    public function __construct() {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();

        $host = $_ENV['DB_HOST'];
        $dbname = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];

        try {
            $this->conn = new \PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Erro na conexão com o banco: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
