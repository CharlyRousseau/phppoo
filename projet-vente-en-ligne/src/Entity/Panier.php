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

    /**
     * @param Produit $produits Le produit à ajouter.
     * @param int $quantite La quantité du produit à ajouter.
     *
     * @return void
     */
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

    /**
     * @param Produit $produit Le produit à retirer.
     * @param int $quantite La quantité du produit à retirer.
     *
     * @return void
     */
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

    /**
     * @return float Le total du panier en montant TTC.
     */
    public function calculerTotal(): float
    {
        $total = 0;
        foreach ($this->articles as $article) {
            $total +=
                $article["produit"]->calculerPrixTTC() * $article["quantite"];
        }
        return $total;
    }

    /**
     * @return int Le nombre total d'articles dans le panier.
     */
    public function compterArticles(): int
    {
        $totalQuantite = 0;

        foreach ($this->articles as $article) {
            $totalQuantite += $article["quantite"];
        }

        return $totalQuantite;
    }

    /**
     * @return DateTime La date de création du panier.
     */
    public function getDateCreation(): DateTime
    {
        return $this->dateCreation;
    }

    /**
     * @param DateTime $dateCreation La date de création du panier.
     *
     * @return void
     */
    public function setDateCreation(DateTime $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }
}
