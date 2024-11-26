<?php
namespace Entity;
/**
 * Class Produit
 * Représente un produit
 */
abstract class Produit
{
    protected ?int $id = null;
    protected string $nom;
    protected string $description;
    protected float $prix;
    protected int $stock;

    public function __construct(
        string $nom,
        string $description,
        float $prix,
        int $stock
    ) {
        $this->setNom($nom);
        $this->setDescription($description);
        $this->setPrix($prix);
        $this->setStock($stock);
    }

    abstract protected function calculerFraisLivraison(): float;

    abstract protected function afficherDetails(): void;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }
    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        if ($prix <= 0) {
            throw new \InvalidArgumentException("Le prix doit être positif.");
        }
        $this->prix = $prix;
        return $this;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        if ($stock < 0) {
            throw new \InvalidArgumentException(
                "Le stock ne peut pas être négatif"
            );
        }
        $this->stock = $stock;
        return $this;
    }

    public function calculerPrixTTC(): float
    {
        return $this->prix * 1.2;
    }

    public function verifierStock(int $quantite): bool
    {
        return $this->stock >= $quantite;
    }
}
