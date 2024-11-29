<?php
namespace App\Entity\Utilisateur;

use App\Entity\Utilisateur\Utilisateur;
use App\Entity\Produit\Produit;

class Vendeur extends Utilisateur
{
    private array $boutique = [];
    private float $commission;
    public function __construct(
        string $nom,
        string $email,
        float $commission,
        ?string $motDePasse = null
    ) {
        parent::__construct($nom, $email, $motDePasse);
        $this->setCommission($commission);
    }

    public function afficherRoles(): void
    {
        echo "Vendeur";
    }

    /**
     * @param Produit $produit Le produit à ajouter.
     *
     * @return void
     */
    public function ajouterProduit(Produit $produit): void
    {
        echo "vide";
    }

    /**
     * @param Produit $produit Le produit dont le stock doit être modifié.
     * @param int $quantite La quantité à ajouter ou à retirer.
     *
     * @return void
     */
    public function gererStock(Produit $produit, int $quantite): void
    {
        echo "vide";
    }

    /**
     * @return float La commission du vendeur.
     */
    public function getCommission(): float
    {
        return $this->commission;
    }

    /**
     * @param float $commission La commission à attribuer au vendeur.
     *
     * @return self Retourne l'instance du vendeur pour une chaîne d'appels.
     */
    public function setCommission(float $commission): self
    {
        $this->commission = $commission;
        return $this;
    }

    /**
     * @return Produit[] Tableau des objets Produit.
     */
    public function getBoutique(): array
    {
        return $this->boutique;
    }
}
