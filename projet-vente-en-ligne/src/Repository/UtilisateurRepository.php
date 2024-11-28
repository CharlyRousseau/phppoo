<?php
namespace App\Repository;

use App\Entity\Utilisateur\Utilisateur;
use App\Database\DatabaseConnection;
use PDO;

class UtilisateurRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = DatabaseConnection::getInstance()->getConnection();
    }

    public function create(Utilisateur $utilisateur): int
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (:nom, :email, :mot_de_passe)"
        );
        $stmt->execute([
            ":nom" => $utilisateur->getNom(),
            ":email" => $utilisateur->getEmail(),
            ":mot_de_passe" => password_hash(
                $utilisateur->getMotDePasse(),
                PASSWORD_DEFAULT
            ),
        ]);
        return (int) $this->connection->lastInsertId();
    }

    public function read(int $id): ?Utilisateur
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM utilisateurs WHERE id = :id"
        );
        $stmt->execute([":id" => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new Utilisateur(
                $data["id"],
                $data["nom"],
                $data["email"],
                $data["mot_de_passe"]
            );
        }
        return null;
    }

    public function update(Utilisateur $utilisateur): void
    {
        $stmt = $this->connection->prepare(
            "UPDATE utilisateurs SET nom = :nom, email = :email, mot_de_passe = :mot_de_passe WHERE id = :id"
        );
        $stmt->execute([
            ":nom" => $utilisateur->getNom(),
            ":email" => $utilisateur->getEmail(),
            ":mot_de_passe" => password_hash(
                $utilisateur->getMotDePasse(),
                PASSWORD_DEFAULT
            ),
            ":id" => $utilisateur->getId(),
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->connection->prepare(
            "DELETE FROM utilisateurs WHERE id = :id"
        );
        $stmt->execute([":id" => $id]);
    }
    /**
     * @return Utilisateur[]
     */
    public function findAll(): array
    {
        $stmt = $this->connection->query("SELECT * FROM utilisateurs");
        $users = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new Utilisateur(
                $data["id"],
                $data["nom"],
                $data["email"],
                $data["mot_de_passe"]
            );
        }
        return $users;
    }
    /**
     * @return Utilisateur[]
     * @param array<int,mixed> $criteria
     */
    public function findBy(array $criteria): array
    {
        $sql =
            "SELECT * FROM utilisateurs WHERE " .
            implode(
                " AND ",
                array_map(fn($k) => "$k = :$k", array_keys($criteria))
            );
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($criteria);
        $users = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new Utilisateur(
                $data["id"],
                $data["nom"],
                $data["email"],
                $data["mot_de_passe"]
            );
        }
        return $users;
    }
}
