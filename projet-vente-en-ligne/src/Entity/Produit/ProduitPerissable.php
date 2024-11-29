<?php
namespace App\Entity\Produit;

use App\Entity\Produit\Produit;
use DateTime;

class ProduitPerissable extends Produit
{
    private DateTime $dateExpiration;
    private float $temperatureStockage;

    /**
     * @param string $nom Le nom du produit.
     * @param string $description La description du produit.
     * @param float $prix Le prix du produit.
     * @param int $stock Le nombre d'unités en stock.
     * @param DateTime $dateExpiration La date d'expiration du produit.
     * @param float $temperatureStockage La température de stockage recommandée en degrés Celsius.
     */

    public function __construct(
        string $nom,
        string $description,
        float $prix,
        int $stock,
        DateTime $dateExpiration,
        float $temperatureStockage
    ) {
        parent::__construct($nom, $description, $prix, $stock);
        $this->setDateExpiration($dateExpiration);
        $this->setTemperatureStockage($temperatureStockage);
    }

    /**
     * @return float Les frais de livraison calculés.
     */
    public function calculerFraisLivraison(): float
    {
        if ($this->temperatureStockage < 8) {
            return 5; // Frais supplémentaires pour les produits nécessitant une température de stockage basse.
        }
        return 0; // Aucun frais supplémentaire pour les produits à température ambiante.
    }

    /**
     * @return void Affiche les informations du produit périssable.
     */
    public function afficherDetails(): void
    {
        echo "Date d'expiration : " .
            $this->dateExpiration->format("Y-m-d") .
            PHP_EOL;
        echo "Température de stockage recommandée : " .
            $this->temperatureStockage .
            "°C" .
            PHP_EOL;
    }

    /**
     * @return bool Retourne true si le produit est périmé, sinon false.
     */
    public function estPerime(): bool
    {
        return new DateTime() > $this->dateExpiration;
    }

    /**
     * @return string La date d'expiration du produit au format 'Y-m-d'.
     */
    public function getDateExpiration(): string
    {
        return $this->dateExpiration->format("Y-m-d");
    }

    /**
     * @param DateTime $dateExpiration La date d'expiration du produit.
     * @return self Retourne l'instance de l'objet ProduitPerissable pour une chaîne d'appels.
     */
    public function setDateExpiration(DateTime $dateExpiration): self
    {
        $this->dateExpiration = $dateExpiration;
        return $this;
    }

    /**
     * @return float La température de stockage en degrés Celsius.
     */
    public function getTemperatureStockage(): float
    {
        return $this->temperatureStockage;
    }

    /**
     * @param float $temperatureStockage La température de stockage en degrés Celsius.
     * @return self Retourne l'instance de l'objet ProduitPerissable pour une chaîne d'appels.
     */
    public function setTemperatureStockage(float $temperatureStockage): self
    {
        $this->temperatureStockage = $temperatureStockage;
        return $this;
    }
}
