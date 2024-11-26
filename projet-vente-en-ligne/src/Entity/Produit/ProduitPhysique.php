<?php
namespace Entity;

use Entity\Produit;

class ProduitPhysique extends Produit
{
    private float $poids;
    private float $longueur;
    private float $largeur;
    private float $hauteur;

    public function __construct(
        string $nom,
        string $description,
        float $prix,
        int $stock,
        float $poids,
        float $longueur,
        float $largeur,
        float $hauteur
    ) {
        parent::__construct($nom, $description, $prix, $stock);
        $this->setPoids($poids);
        $this->setLongueur($longueur);
        $this->setLargeur($largeur);
        $this->setHauteur($hauteur);
    }

    public function calculerVolume(): float
    {
        $volume =
            $this->getLongueur() * $this->getLargeur() * $this->getHauteur();
        return $volume;
    }

    public function calculerFraisLivraison(): float
    {
        return $this->calculerVolume() * 0.1;
    }

    public function afficherDetails(): void
    {
        echo "Poids : " . $this->getPoids() . " kg" . PHP_EOL;
    }

    public function getPoids(): float
    {
        return $this->poids;
    }

    public function setPoids(float $poids): self
    {
        $this->poids = $poids;
        return $this;
    }

    public function getLongueur(): float
    {
        return $this->longueur;
    }

    public function setLongueur(float $longueur): self
    {
        $this->longueur = $longueur;
        return $this;
    }

    public function getLargeur(): float
    {
        return $this->largeur;
    }

    public function setLargeur(float $largeur): self
    {
        $this->largeur = $largeur;
        return $this;
    }

    public function getHauteur(): float
    {
        return $this->hauteur;
    }

    public function setHauteur(float $hauteur): self
    {
        $this->hauteur = $hauteur;
        return $this;
    }
}
