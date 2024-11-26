<?php
namespace Entity;

use Entity\Utilisateur;
use Entity\Produit;

class Vendeur extends Utilisateur
{
    /**
     * @var Produit[]
     */
    private array $boutique = [];
    private float $commission;

    public function __construct(
        string $nom,
        string $email,
        string $motDePasse,
        float $commission
    ) {
        parent::__construct($nom, $email, $motDePasse);
        $this->setCommission($commission);
    }

    public function afficherRoles(): void
    {
        echo "Vendeur";
    }

    public function ajouterProduit(Produit $produit): void
    {
        echo "vide";
    }

    public function gererStock(Produit $produit, int $quantite): void
    {
        echo "vide";
    }

    public function getCommission(): float
    {
        return $this->commission;
    }

    public function setCommission(float $commission): self
    {
        $this->commission = $commission;
        return $this;
    }

    /**
     * @return Produit[]
     */
    public function getBoutique(): array
    {
        return $this->boutique;
    }
}
