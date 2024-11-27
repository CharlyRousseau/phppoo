<?php
namespace App\Entity;

use App\Entity\Produit\Produit;
use DateTime;

class Panier
{
    /**
     * Tableau associatif où la clé est l'ID du produit et la valeur est un tableau contenant :
     * - 'produit' : l'objet Produit
     * - 'quantite' : la quantité du produit
     *
     * @var array<int, array{produit: Produit, quantite: int}>
     */
    private array $articles = [];

    private DateTime $dateCreation;

    public function ajouterArticle(Produit $produits, int $quantite): void
    {
        if (array_key_exists($produits->getId(), $this->articles)) {
            $this->articles[$produits->getId()]["quantite"] += $quantite;
        } else {
            $this->articles[$produits->getId()] = [
                "produit" => $produits,
                "quantite" => $quantite,
            ];
        }
    }

    public function retirerArticle(Produit $produit, int $quantite): void
    {
        if (array_key_exists($produit->getId(), $this->articles)) {
            $this->articles[$produit->getId()]["quantite"] -= $quantite;
            if ($this->articles[$produit->getId()]["quantite"] <= 0) {
                unset($this->articles[$produit->getId()]);
            }
        }
    }

    public function vider(): void
    {
        $this->articles = [];
    }

    public function calculerTotal(): float
    {
        $total = 0;
        foreach ($this->articles as $article) {
            $total +=
                $article["produit"]->calculerPrixTTC() * $article["quantite"];
        }
        return $total;
    }

    public function compterArticles(): int
    {
        $totalQuantite = 0;

        foreach ($this->articles as $article) {
            $totalQuantite += $article["quantite"];
        }

        return $totalQuantite;
    }

    public function getDateCreation(): DateTime
    {
        return $this->dateCreation;
    }

    public function setDateCreation(DateTime $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }
}
