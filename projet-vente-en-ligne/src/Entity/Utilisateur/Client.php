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
        string $email,
        string $adresseLivraison,
        ?string $motDePasse = null
    ) {
        parent::__construct($nom, $email, $motDePasse);
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

    /**
     * @param string $adresseLivraison L'adresse de livraison à définir.
     *
     * @return self Retourne l'instance du client pour une chaîne d'appels.
     */
    public function setAdresseLivraison(string $adresseLivraison): self
    {
        $this->adresseLivraison = $adresseLivraison;
        return $this;
    }
}
