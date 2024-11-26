<?php
namespace Entity;

use Entity\Produit;
use DateTime;

class ProduitPerissable extends Produit
{
    private DateTime $dateExpiration;
    private float $temperatureStockage;

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

    public function calculerFraisLivraison(): float
    {
        if ($this->temperatureStockage < 8) {
            return 5;
        }
        return 0;
    }

    public function afficherDetails(): void
    {
        echo "Date d'expiration : " . $this->dateExpiration . PHP_EOL;
        echo "Température de stockage recommandée : " .
            $this->temperatureStockage .
            "°C" .
            PHP_EOL;
    }

    public function estPerime(): bool
    {
        return new DateTime() > $this->dateExpiration;
    }

    public function getDateExpiration(): string
    {
        return $this->dateExpiration;
    }

    public function setDateExpiration(DateTime $dateExpiration): self
    {
        $this->dateExpiration = $dateExpiration;
        return $this;
    }

    public function getTemperatureStockage(): float
    {
        return $this->temperatureStockage;
    }

    public function setTemperatureStockage(float $temperatureStockage): self
    {
        $this->temperatureStockage = $temperatureStockage;
        return $this;
    }
}
