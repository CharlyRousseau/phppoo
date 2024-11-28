<?php
namespace App\Repository;

use App\Entity\Categorie;
use App\Database\DatabaseConnection;
use PDO;

class CategorieRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = DatabaseConnection::getInstance()->getConnection();
    }

    public function create(Categorie $categorie): int
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO Categorie (nom) VALUES (:nom)"
        );
        $stmt->execute([
            ":nom" => $categorie->getNom(),
        ]);
        return (int) $this->connection->lastInsertId();
    }

    public function read(int $id): ?Categorie
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM Categorie WHERE id = :id"
        );
        $stmt->execute([":id" => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new Categorie($data["id"], $data["nom"]);
        }
        return null;
    }

    public function update(Categorie $categorie): void
    {
        $stmt = $this->connection->prepare(
            "UPDATE Categorie SET nom = :nom WHERE id = :id"
        );
        $stmt->execute([
            ":nom" => $categorie->getNom(),
            ":id" => $categorie->getId(),
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->connection->prepare(
            "DELETE FROM Categorie WHERE id = :id"
        );
        $stmt->execute([":id" => $id]);
    }

    /**
     * Récupérer toutes les catégories
     *
     * @return Categorie[]
     */
    public function findAll(): array
    {
        $stmt = $this->connection->query("SELECT * FROM Categorie");
        $categories = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = new Categorie($data["id"], $data["nom"]);
        }
        return $categories;
    }

    /**
     *Recherche par critères
     *
     * @param array $criteria
     * @return Categorie[]
     */
    public function findBy(array $criteria): array
    {
        $sql =
            "SELECT * FROM Categorie WHERE " .
            implode(
                " AND ",
                array_map(fn($k) => "$k = :$k", array_keys($criteria))
            );
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($criteria);
        $categories = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = new Categorie($data["id"], $data["nom"]);
        }
        return $categories;
    }
}
