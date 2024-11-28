<?php

namespace App\Repository;

use App\Entity\Produit\Produit;
use App\Entity\Produit\ProduitNumerique;
use App\Entity\Produit\ProduitPerissable;
use App\Entity\Produit\ProduitPhysique;
use App\Database\DatabaseConnection;
use PDO;
use PDOException;
use PDOStatement;

class ProduitRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = DatabaseConnection::getInstance()->getConnection();
    }

    public function create(Produit $produit): int
    {
        try {
            // Préparer l'insert
            $sql = "INSERT INTO Produit (nom, description, prix, stock, type, poids, longueur, largeur, hauteur, lienTelechargement, tailleFichier, formatFichier, dateExpiration, temperatureStockage, categorie_id)
                    VALUES (:nom, :description, :prix, :stock, :type, :poids, :longueur, :largeur, :hauteur, :lienTelechargement, :tailleFichier, :formatFichier, :dateExpiration, :temperatureStockage, :categorie_id)";
            $stmt = $this->connection->prepare($sql);

            // Liaison des paramètres généraux
            $stmt->bindValue(":nom", $produit->getNom());
            $stmt->bindValue(":description", $produit->getDescription());
            $stmt->bindValue(":prix", $produit->getPrix());
            $stmt->bindValue(":stock", $produit->getStock());
            $stmt->bindValue(":type", $this->getProduitType($produit));

            // Propriétés spécifiques au type de produit
            $this->bindProductSpecificParams($stmt, $produit);

            // Catégorie par défaut
            $stmt->bindValue(":categorie_id", null, PDO::PARAM_NULL); // Assurez-vous que vous avez une catégorie valide ici

            // Exécuter la requête
            $stmt->execute();

            return (int) $this->connection->lastInsertId();
        } catch (PDOException $e) {
            throw new \RuntimeException(
                "Erreur lors de l'ajout du produit : " . $e->getMessage()
            );
        }
    }

    public function read(int $id): ?Produit
    {
        try {
            $sql = "SELECT * FROM Produit WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                return null;
            }

            return $this->mapRowToProduit($result);
        } catch (PDOException $e) {
            throw new \RuntimeException(
                "Erreur lors de la récupération du produit : " .
                    $e->getMessage()
            );
        }
    }

    public function update(Produit $produit): void
    {
        try {
            $sql = "UPDATE Produit SET
                        nom = :nom,
                        description = :description,
                        prix = :prix,
                        stock = :stock,
                        type = :type,
                        poids = :poids,
                        longueur = :longueur,
                        largeur = :largeur,
                        hauteur = :hauteur,
                        lienTelechargement = :lienTelechargement,
                        tailleFichier = :tailleFichier,
                        formatFichier = :formatFichier,
                        dateExpiration = :dateExpiration,
                        temperatureStockage = :temperatureStockage,
                        categorie_id = :categorie_id
                    WHERE id = :id";

            $stmt = $this->connection->prepare($sql);

            // Liaison des paramètres
            $stmt->bindValue(":id", $produit->getId());
            $stmt->bindValue(":nom", $produit->getNom());
            $stmt->bindValue(":description", $produit->getDescription());
            $stmt->bindValue(":prix", $produit->getPrix());
            $stmt->bindValue(":stock", $produit->getStock());
            $stmt->bindValue(":type", $this->getProduitType($produit));

            // Propriétés spécifiques au type de produit
            $this->bindProductSpecificParams($stmt, $produit);

            // Catégorie par défaut
            $stmt->bindValue(":categorie_id", null, PDO::PARAM_NULL); // Assurez-vous que vous avez une catégorie valide ici

            // Exécuter la requête
            $stmt->execute();
        } catch (PDOException $e) {
            throw new \RuntimeException(
                "Erreur lors de la mise à jour du produit : " . $e->getMessage()
            );
        }
    }

    public function delete(int $id): void
    {
        try {
            $sql = "DELETE FROM Produit WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new \RuntimeException(
                "Erreur lors de la suppression du produit : " . $e->getMessage()
            );
        }
    }
    /**
     * @return Produit[]
     */
    public function findAll(): array
    {
        try {
            $sql = "SELECT * FROM Produit";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $produits = [];
            foreach ($results as $row) {
                $produits[] = $this->mapRowToProduit($row);
            }

            return $produits;
        } catch (PDOException $e) {
            throw new \RuntimeException(
                "Erreur lors de la récupération des produits : " .
                    $e->getMessage()
            );
        }
    }
    /**
     * @param array<int,mixed> $row
     */
    private function mapRowToProduit(array $row): Produit
    {
        $produit = null;

        if ($row["type"] === "physique") {
            $produit = new ProduitPhysique(
                $row["nom"],
                $row["description"],
                $row["prix"],
                $row["stock"],
                $row["poids"],
                $row["longueur"],
                $row["largeur"],
                $row["hauteur"]
            );
        } elseif ($row["type"] === "numerique") {
            $produit = new ProduitNumerique(
                $row["nom"],
                $row["description"],
                $row["prix"],
                $row["stock"],
                $row["lienTelechargement"],
                $row["tailleFichier"],
                $row["formatFichier"]
            );
        } elseif ($row["type"] === "perissable") {
            $produit = new ProduitPerissable(
                $row["nom"],
                $row["description"],
                $row["prix"],
                $row["stock"],
                new \DateTime($row["dateExpiration"]),
                $row["temperatureStockage"]
            );
        }

        $produit->setId($row["id"]);

        return $produit;
    }

    private function getProduitType(Produit $produit): string
    {
        if ($produit instanceof ProduitPhysique) {
            return "physique";
        } elseif ($produit instanceof ProduitNumerique) {
            return "numerique";
        } elseif ($produit instanceof ProduitPerissable) {
            return "perissable";
        }
        throw new \InvalidArgumentException("Type de produit inconnu");
    }

    private function bindProductSpecificParams(
        PDOStatement $stmt,
        Produit $produit
    ): void {
        if ($produit instanceof ProduitPhysique) {
            $stmt->bindValue(":poids", $produit->getPoids());
            $stmt->bindValue(":longueur", $produit->getLongueur());
            $stmt->bindValue(":largeur", $produit->getLargeur());
            $stmt->bindValue(":hauteur", $produit->getHauteur());
            $stmt->bindValue(":lienTelechargement", null, PDO::PARAM_NULL);
            $stmt->bindValue(":tailleFichier", null, PDO::PARAM_NULL);
            $stmt->bindValue(":formatFichier", null, PDO::PARAM_NULL);
            $stmt->bindValue(":dateExpiration", null, PDO::PARAM_NULL);
            $stmt->bindValue(":temperatureStockage", null, PDO::PARAM_NULL);
        } elseif ($produit instanceof ProduitNumerique) {
            $stmt->bindParam(
                ":lienTelechargement",
                $produit->getLienTelechargement()
            );
            $stmt->bindValue(":tailleFichier", $produit->getTailleFichier());
            $stmt->bindValue(":formatFichier", $produit->getFormatFichier());
            $stmt->bindValue(":poids", null, PDO::PARAM_NULL);
            $stmt->bindValue(":longueur", null, PDO::PARAM_NULL);
            $stmt->bindValue(":largeur", null, PDO::PARAM_NULL);
            $stmt->bindValue(":hauteur", null, PDO::PARAM_NULL);
            $stmt->bindValue(":dateExpiration", null, PDO::PARAM_NULL);
            $stmt->bindValue(":temperatureStockage", null, PDO::PARAM_NULL);
        } elseif ($produit instanceof ProduitPerissable) {
            $stmt->bindValue(
                ":dateExpiration",
                $produit->getDateExpiration()->format("Y-m-d")
            );
            $stmt->bindValue(
                ":temperatureStockage",
                $produit->getTemperatureStockage()
            );
            $stmt->bindValue(":poids", null, PDO::PARAM_NULL);
            $stmt->bindValue(":longueur", null, PDO::PARAM_NULL);
            $stmt->bindValue(":largeur", null, PDO::PARAM_NULL);
            $stmt->bindValue(":hauteur", null, PDO::PARAM_NULL);
            $stmt->bindValue(":lienTelechargement", null, PDO::PARAM_NULL);
            $stmt->bindValue(":tailleFichier", null, PDO::PARAM_NULL);
            $stmt->bindValue(":formatFichier", null, PDO::PARAM_NULL);
        }
    }
}
