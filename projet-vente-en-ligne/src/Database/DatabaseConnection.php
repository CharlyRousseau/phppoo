<?php
namespace App\Database;

use PDO;
use PDOException;

class DatabaseConnection
{
    private static ?DatabaseConnection $instance = null;
    private ?PDO $connection = null;
    private string $host;
    private string $db;
    private string $user;
    private string $password;

    private function __construct()
    {
        $this->host = "db";
        $this->db = "projet_vente_en_ligne";
        $this->user = "root";
        $this->password = "root";

        $this->connect();
    }

    // Retourne l'instance unique de la classe
    public static function getInstance(): DatabaseConnection
    {
        if (self::$instance === null) {
            self::$instance = new DatabaseConnection();
        }
        return self::$instance;
    }

    private function connect(): void
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8";
            $this->connection = new PDO($dsn, $this->user, $this->password);
            $this->connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        } catch (PDOException $e) {
            throw new \RuntimeException(
                "Erreur de connexion à la base de données : " . $e->getMessage()
            );
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    private function __clone()
    {
    }

    public function __destruct()
    {
        $this->connection = null;
    }
}
