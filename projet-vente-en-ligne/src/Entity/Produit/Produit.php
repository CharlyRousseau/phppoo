<?php
namespace App\Entity\Produit;

use App\Config\ConfigurationManager;

abstract class Produit
{
    protected ?int $id = null;
    protected string $nom;
    protected string $description;
    protected float $prix;
    protected int $stock;

    /**
     * @param string $nom Le nom du produit.
     * @param string $description La description du produit.
     * @param float $prix Le prix du produit.
     * @param int $stock La quantité en stock.
     */
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

    /**
     * @return float Les frais de livraison du produit.
     */
    abstract protected function calculerFraisLivraison(): float;

    /**
     * @return void
     */
    abstract protected function afficherDetails(): void;

    /**
     * @return int|null L'ID du produit ou null s'il n'est pas défini.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id L'identifiant à attribuer au produit.
     *
     * @return self Retourne l'instance de l'objet Produit pour une chaîne d'appels.
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string Le nom du produit.
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param string $nom Le nom à attribuer au produit.
     *
     * @return self Retourne l'instance de l'objet Produit pour une chaîne d'appels.
     */
    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return string La description du produit.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description La description à attribuer au produit.
     *
     * @return self Retourne l'instance de l'objet Produit pour une chaîne d'appels.
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return float Le prix du produit.
     */
    public function getPrix(): float
    {
        return $this->prix;
    }

    /**
     * @param float $prix Le prix à attribuer au produit.
     * @throws \InvalidArgumentException Si le prix est inférieur ou égal à zéro.
     *
     * @return self Retourne l'instance de l'objet Produit pour une chaîne d'appels.
     */
    public function setPrix(float $prix): self
    {
        if ($prix <= 0) {
            throw new \InvalidArgumentException("Le prix doit être positif.");
        }
        $this->prix = $prix;
        return $this;
    }

    /**
     * @return int Le nombre d'unités en stock.
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int $stock La quantité à attribuer au produit.
     * @throws \InvalidArgumentException Si le stock est négatif.
     *
     * @return self Retourne l'instance de l'objet Produit pour une chaîne d'appels.
     */
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

    /**
     * @return float Le prix TTC du produit.
     */
    public function calculerPrixTTC(): float
    {
        $tva = ConfigurationManager::getInstance()->get("tva");
        return $this->prix * (1 + $tva / 100);
    }

    /**
     * @param int $quantite La quantité à vérifier.
     *
     * @return bool Retourne vrai si le stock est suffisant, sinon faux.
     */
    public function verifierStock(int $quantite): bool
    {
        return $this->stock >= $quantite;
    }
}
