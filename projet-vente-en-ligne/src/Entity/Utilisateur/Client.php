<?php
namespace App\Entity\Utilisateur;

use App\Entity\Utilisateur\Utilisateur;
use App\Entity\Panier;

class Client extends Utilisateur
{
    private string $adresseLivraison;
    private Panier $panier;

    public function __construct(
        string $nom,
        string $prenom,
        string $email,
        string $adresseLivraison
    ) {
        parent::__construct($nom, $prenom, $email);
        $this->setAdresseLivraison($adresseLivraison);
        $this->panier = new Panier();
    }

    public function afficherRoles(): void
    {
        echo "Client";
    }

    public function passerCommande(): void
    {
        echo "Commande passée articles: " . $this->panier->compterArticles();
    }

    public function consulterHistorique(): array
    {
        return [];
    }

    public function getAdresseLivraison(): string
    {
        return $this->adresseLivraison;
    }

    public function setAdresseLivraison(string $adresseLivraison): self
    {
        $this->adresseLivraison = $adresseLivraison;
        return $this;
    }
}
