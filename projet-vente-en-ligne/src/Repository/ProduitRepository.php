<?php

namespace App\Repository;

use App\Entity\Produit\Produit;
use App\Factory\ProduitFactory;
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
    private ProduitFactory $produitFactory;

    public function __construct()
    {
        $this->connection = DatabaseConnection::getInstance()->getConnection();
        $this->produitFactory = new ProduitFactory();
    }

    /**
     * @param Produit $produit Le produit à insérer.
     * @return int L'ID du produit inséré.
     * @throws RuntimeException Si une erreur survient lors de l'insertion.
     */
    public function create(Produit $produit): int
    {
        try {
            $sql = "INSERT INTO Produit (nom, description, prix, stock, type, poids, longueur, largeur, hauteur, lienTelechargement, tailleFichier, formatFichier, dateExpiration, temperatureStockage, categorie_id)
                    VALUES (:nom, :description, :prix, :stock, :type, :poids, :longueur, :largeur, :hauteur, :lienTelechargement, :tailleFichier, :formatFichier, :dateExpiration, :temperatureStockage, :categorie_id)";
            $stmt = $this->connection->prepare($sql);

            $stmt->bindValue(":nom", $produit->getNom());
            $stmt->bindValue(":description", $produit->getDescription());
            $stmt->bindValue(":prix", $produit->getPrix());
            $stmt->bindValue(":stock", $produit->getStock());
            $stmt->bindValue(":type", $this->getProduitType($produit));

            $this->bindProductSpecificParams($stmt, $produit);

            $stmt->bindValue(":categorie_id", null, PDO::PARAM_NULL);

            $stmt->execute();

            return (int) $this->connection->lastInsertId();
        } catch (PDOException $e) {
            throw new \RuntimeException(
                "Erreur lors de l'ajout du produit : " . $e->getMessage()
            );
        }
    }

    /**
     * @param int $id L'ID du produit.
     * @return Produit|null Le produit correspondant ou null si introuvable.
     * @throws RuntimeException Si une erreur survient lors de la récupération.
     */
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

            return $this->produitFactory->creerProduit(
                $result["TYPE"],
                $result
            ); // Utilisation de la factory
        } catch (PDOException $e) {
            throw new \RuntimeException(
                "Erreur lors de la récupération du produit : " .
                    $e->getMessage()
            );
        }
    }

    /**
     *
     * @param Produit $produit Le produit à mettre à jour.
     * @throws RuntimeException Si une erreur survient lors de la mise à jour.
     */
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

            $stmt->bindValue(":id", $produit->getId());
            $stmt->bindValue(":nom", $produit->getNom());
            $stmt->bindValue(":description", $produit->getDescription());
            $stmt->bindValue(":prix", $produit->getPrix());
            $stmt->bindValue(":stock", $produit->getStock());
            $stmt->bindValue(":type", $this->getProduitType($produit));

            $this->bindProductSpecificParams($stmt, $produit);

            $stmt->bindValue(":categorie_id", null, PDO::PARAM_NULL);

            $stmt->execute();
        } catch (PDOException $e) {
            throw new \RuntimeException(
                "Erreur lors de la mise à jour du produit : " . $e->getMessage()
            );
        }
    }

    /**
     *
     * @param int $id L'ID du produit à supprimer.
     * @throws RuntimeException Si une erreur survient lors de la suppression.
     */
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
     *
     * @return Produit[] Un tableau d'objets Produit.
     * @throws RuntimeException Si une erreur survient lors de la récupération des produits.
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
                $produits[] = $this->produitFactory->creerProduit(
                    $row["TYPE"],
                    $row
                ); // Utilisation de la factory
            }

            return $produits;
        } catch (PDOException $e) {
            throw new \RuntimeException(
                "Erreur lors de la récupération des produits : " .
                    $e->getMessage()
            );
        }
    }

    /*
     * Rechercher des produits par des critères spécifiques.
     *
     * @param array $criteria Tableau associatif des critères de recherche (par exemple, ['nom' => 'Produit1', 'type' => 'physique']).
     * @return Produit[] Un tableau d'objets Produit correspondant aux critères.
     * @throws RuntimeException Si une erreur survient lors de la récupération des produits.
     */
    public function findBy(array $criteria): array
    {
        try {
            // Construction de la requête SQL dynamique en fonction des critères.
            $sql = "SELECT * FROM Produit WHERE ";
            $conditions = [];
            $params = [];

            foreach ($criteria as $field => $value) {
                $conditions[] = "$field = :$field";
                $params[":$field"] = $value;
            }

            $sql .= implode(" AND ", $conditions);

            // Préparation de la requête
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);

            // Récupération des résultats
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $produits = [];
            foreach ($results as $row) {
                $produits[] = $this->produitFactory->creerProduit(
                    $row["TYPE"],
                    $row
                ); // Utilisation de la factory
            }

            return $produits;
        } catch (PDOException $e) {
            throw new \RuntimeException(
                "Erreur lors de la récupération des produits avec les critères spécifiés : " .
                    $e->getMessage()
            );
        }
    }

    /**
     * @param Produit $produit Le produit pour lequel récupérer le type.
     * @return string Le type du produit ('physique', 'numerique', 'perissable').
     * @throws InvalidArgumentException Si le type du produit est inconnu.
     */
    private function getProduitType(Produit $produit): string
    {
        if ($produit instanceof ProduitPhysique) {
            return "physique";
        } elseif ($produit instanceof ProduitNumerique) {
            return "numerique";
        } elseif ($produit instanceof ProduitPerissable) {
            return "perissable";
        }

        throw new \InvalidArgumentException("Type de produit inconnu.");
    }

    /**
     *
     * @param PDOStatement $stmt La requête préparée.
     * @param Produit $produit Le produit dont les paramètres spécifiques doivent être liés.
     */
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
            $stmt->bindValue(
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
            $stmt->bindValue(":dateExpiration", $produit->getDateExpiration());
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
