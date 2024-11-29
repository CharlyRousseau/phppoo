<?php

namespace App\Repository;

use App\Entity\Utilisateur\Admin;
use App\Entity\Utilisateur\Client;
use App\Entity\Utilisateur\Utilisateur;
use App\Entity\Utilisateur\Vendeur;
use App\Database\DatabaseConnection;
use PDO;
use InvalidArgumentException;
use RuntimeException;

class UtilisateurRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = DatabaseConnection::getInstance()->getConnection();
    }

    /**
     * @param Utilisateur $utilisateur L'utilisateur à créer.
     * @return int L'ID de l'utilisateur créé.
     */
    public function create(Utilisateur $utilisateur): int
    {
        $params = [
            ":nom" => $utilisateur->getNom(),
            ":email" => $utilisateur->getEmail(),
            ":motDePasse" => password_hash(
                $utilisateur->getMotDePasse(),
                PASSWORD_DEFAULT
            ),
        ];

        if ($utilisateur instanceof Admin) {
            $params[":niveauAcces"] = $utilisateur->getNiveauAcces();
            $params[":commission"] = null;
            $params[":adresseLivraison"] = null;
            $stmt = $this->connection->prepare(
                "INSERT INTO Utilisateur (nom, email, motDePasse, niveauAcces, commission, adresseLivraison)
                VALUES (:nom, :email, :motDePasse, :niveauAcces, :commission, :adresseLivraison)"
            );
        } elseif ($utilisateur instanceof Vendeur) {
            $params[":niveauAcces"] = null;
            $params[":commission"] = $utilisateur->getCommission();
            $params[":adresseLivraison"] = null;
            $stmt = $this->connection->prepare(
                "INSERT INTO Utilisateur (nom, email, motDePasse, niveauAcces, commission, adresseLivraison)
                VALUES (:nom, :email, :motDePasse, :niveauAcces, :commission, :adresseLivraison)"
            );
        } elseif ($utilisateur instanceof Client) {
            $params[":niveauAcces"] = null;
            $params[":commission"] = null;
            $params[":adresseLivraison"] = $utilisateur->getAdresseLivraison();
            $stmt = $this->connection->prepare(
                "INSERT INTO Utilisateur (nom, email, motDePasse, niveauAcces, commission, adresseLivraison)
                VALUES (:nom, :email, :motDePasse, :niveauAcces, :commission, :adresseLivraison)"
            );
        } else {
            throw new InvalidArgumentException("Type d'utilisateur inconnu.");
        }

        $stmt->execute($params);

        return (int) $this->connection->lastInsertId();
    }

    /**
     * @param int $id L'ID de l'utilisateur à rechercher.
     * @return Utilisateur|null L'utilisateur correspondant ou null si non trouvé.
     * @throws InvalidArgumentException Si l'ID est invalide.
     */
    public function read(int $id): ?Utilisateur
    {
        if ($id <= 0) {
            throw new InvalidArgumentException(
                "L'ID de l'utilisateur doit être un entier positif."
            );
        }

        $stmt = $this->connection->prepare(
            "SELECT * FROM Utilisateur WHERE id = :id"
        );
        $stmt->execute([":id" => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->createUtilisateurFromData($data) : null;
    }

    /**
     * @param Utilisateur $utilisateur L'utilisateur à mettre à jour (doit avoir un ID).
     * @throws RuntimeException Si l'utilisateur n'existe pas.
     */
    public function update(Utilisateur $utilisateur): void
    {
        if (!$this->read($utilisateur->getId())) {
            throw new RuntimeException(
                "L'utilisateur à mettre à jour n'existe pas."
            );
        }

        $params = [
            ":nom" => $utilisateur->getNom(),
            ":email" => $utilisateur->getEmail(),
            ":id" => $utilisateur->getId(),
        ];

        if ($utilisateur->getMotDePasse() !== null) {
            $params[":motDePasse"] = password_hash(
                $utilisateur->getMotDePasse(),
                PASSWORD_DEFAULT
            );
        }

        $sql = "UPDATE Utilisateur SET nom = :nom, email = :email";

        if (isset($params[":motDePasse"])) {
            $sql .= ", motDePasse = :motDePasse";
        }

        if ($utilisateur instanceof Admin) {
            $sql .= ", niveauAcces = :niveauAcces";
            $params[":niveauAcces"] = $utilisateur->getNiveauAcces();
        } elseif ($utilisateur instanceof Vendeur) {
            $sql .= ", commission = :commission";
            $params[":commission"] = $utilisateur->getCommission();
        } elseif ($utilisateur instanceof Client) {
            $sql .= ", adresseLivraison = :adresseLivraison";
            $params[":adresseLivraison"] = $utilisateur->getAdresseLivraison();
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
    }

    /**
     * @param int $id L'ID de l'utilisateur à supprimer.
     * @throws RuntimeException Si l'utilisateur n'existe pas.
     */
    public function delete(int $id): void
    {
        if (!$this->read($id)) {
            throw new RuntimeException(
                "L'utilisateur à supprimer n'existe pas."
            );
        }

        $stmt = $this->connection->prepare(
            "DELETE FROM Utilisateur WHERE id = :id"
        );
        $stmt->execute([":id" => $id]);
    }

    /**
     *
     * @return Utilisateur[] Une liste d'objets Utilisateur.
     */
    public function findAll(): array
    {
        $stmt = $this->connection->query("SELECT * FROM Utilisateur");
        $users = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $this->createUtilisateurFromData($data);
        }
        return $users;
    }

    /**
     *
     * @param array<string, mixed> $criteria Les critères sous forme de tableau associatif (clé => valeur).
     * @return Utilisateur[] Une liste d'objets Utilisateur correspondant aux critères.
     */
    public function findBy(array $criteria): array
    {
        $sql =
            "SELECT * FROM Utilisateur WHERE " .
            implode(
                " AND ",
                array_map(fn($key) => "$key = :$key", array_keys($criteria))
            );
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($criteria);
        $users = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $this->createUtilisateurFromData($data);
        }
        return $users;
    }

    /**
     * @param string $email L'email à vérifier.
     * @return bool True si l'email existe déjà, false sinon.
     */
    private function emailExists(string $email): bool
    {
        $stmt = $this->connection->prepare(
            "SELECT COUNT(*) FROM Utilisateur WHERE email = :email"
        );
        $stmt->execute([":email" => $email]);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * @param array<string, mixed> $data Les données utilisateur depuis la base de données.
     * @return Utilisateur L'utilisateur correspondant avec toutes ses informations.
     * @throws InvalidArgumentException Si les données utilisateur sont manquantes.
     */
    private function createUtilisateurFromData(array $data): Utilisateur
    {
        if (
            !isset(
                $data["nom"],
                $data["email"],
                $data["motDePasse"],
                $data["type"],
                $data["id"]
            )
        ) {
            throw new InvalidArgumentException(
                "Données utilisateur manquantes."
            );
        }

        switch ($data["type"]) {
            case "client":
                $utilisateur = new Client(
                    $data["nom"],
                    $data["email"],
                    $data["adresseLivraison"], // Spécifique à Client
                    null
                );
                $utilisateur->setMotDePasseHash($data["motDePasse"]);
                break;

            case "admin":
                $utilisateur = new Admin(
                    $data["nom"],
                    $data["email"],
                    null,
                    $data["niveauAcces"] // Spécifique à Admin
                );
                $utilisateur->setMotDePasseHash($data["motDePasse"]);
                break;

            case "vendeur":
                $utilisateur = new Vendeur(
                    $data["nom"],
                    $data["email"],
                    null,
                    $data["commission"] // Spécifique à Vendeur
                );
                $utilisateur->setMotDePasseHash($data["motDePasse"]);
                break;

            default:
                throw new InvalidArgumentException(
                    "Type d'utilisateur inconnu : " . $data["type"]
                );
        }

        $utilisateur->setId((int) $data["id"]);

        return $utilisateur;
    }
}
