<?php
namespace App\Entity;

use App\Entity\Produit\Produit;

class Categorie
{
    private int $id;
    private string $nom;
    private string $description;

    /**
     * @var Produit[]
     */
    private array $produits = [];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function ajouterProduit(Produit $produit): void
    {
        $this->produits[] = $produit;
    }

    public function retirerProduit(Produit $produit): void
    {
        $key = array_search($produit, $this->produits, true);
        if ($key !== false) {
            unset($this->produits[$key]);
        }
    }

    /**
     * Renvoie le tableau de produit
     *
     * @return Produit[]
     */
    public function listerProduits(): array
    {
        return $this->produits;
    }
}
