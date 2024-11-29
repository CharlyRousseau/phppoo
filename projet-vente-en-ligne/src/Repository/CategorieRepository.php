<?php

namespace App\Repository;

use App\Entity\Categorie;
use App\Database\DatabaseConnection;
use PDO;
use PDOException;

class CategorieRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = DatabaseConnection::getInstance()->getConnection();
    }

    /**
     * @param Categorie $categorie La catégorie à créer.
     *
     * @return int L'ID de la catégorie nouvellement créée.
     */
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

    /**
     * @param int $id L'ID de la catégorie à récupérer.
     *
     * @return Categorie|null La catégorie si trouvée, ou null si non trouvée.
     * @throws \RuntimeException Si une erreur survient lors de la récupération.
     */
    public function read(int $id): ?Categorie
    {
        try {
            $sql = "SELECT * FROM Categorie WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return null;
            }

            $categorie = new Categorie(
                $result["nom"],
                $result["description"] ?? ""
            );

            $categorie->setId($result["id"]);

            return $categorie;
        } catch (PDOException $e) {
            throw new \RuntimeException(
                "Erreur lors de la récupération de la Catégorie : " .
                    $e->getMessage()
            );
        }
    }

    /**
     * @param Categorie $categorie La catégorie à mettre à jour.
     *
     * @return void
     * @throws \PDOException Si une erreur survient lors de la mise à jour.
     */
    public function update(Categorie $categorie): void
    {
        $stmt = $this->connection->prepare(
            "UPDATE Categorie SET nom = :nom, description = :description WHERE id = :id"
        );
        $stmt->execute([
            ":nom" => $categorie->getNom(),
            ":description" => $categorie->getDescription(), // Correction ici (c'était précédemment $categorie->getNom())
            ":id" => $categorie->getId(),
        ]);
    }

    /**
     * @param int $id L'ID de la catégorie à supprimer.
     *
     * @return void
     * @throws \PDOException Si une erreur survient lors de la suppression.
     */
    public function delete(int $id): void
    {
        $stmt = $this->connection->prepare(
            "DELETE FROM Categorie WHERE id = :id"
        );
        $stmt->execute([":id" => $id]);
    }

    /**
     * @return Categorie[] Un tableau d'objets Categorie.
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
     * @param array $criteria Un tableau associatif de critères pour filtrer les catégories.
     *
     * @return Categorie[] Un tableau de catégories correspondant aux critères donnés.
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
