<?php
namespace App\Entity\Produit;

use App\Entity\Produit\Produit;

class ProduitPhysique extends Produit
{
    private float $poids;
    private float $longueur;
    private float $largeur;
    private float $hauteur;

    /**
     * @param string $nom Le nom du produit.
     * @param string $description La description du produit.
     * @param float $prix Le prix du produit.
     * @param int $stock Le nombre d'unités en stock.
     * @param float $poids Le poids du produit en kilogrammes.
     * @param float $longueur La longueur du produit en centimètres.
     * @param float $largeur La largeur du produit en centimètres.
     * @param float $hauteur La hauteur du produit en centimètres.
     */
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

    /**
     * @return float Le volume du produit en centimètres cubes (cm³).
     */
    public function calculerVolume(): float
    {
        $volume =
            $this->getLongueur() * $this->getLargeur() * $this->getHauteur();
        return $volume;
    }

    /**
     * @return float Les frais de livraison calculés en fonction du volume.
     */
    public function calculerFraisLivraison(): float
    {
        return $this->calculerVolume() * 0.0001;
    }

    /**
     * @return void Affiche les informations du produit physique.
     */
    public function afficherDetails(): void
    {
        echo "Poids : " . $this->getPoids() . " kg" . PHP_EOL;
    }

    /**
     * @return float Le poids du produit en kilogrammes.
     */
    public function getPoids(): float
    {
        return $this->poids;
    }

    /**
     * @param float $poids Le poids du produit en kilogrammes.
     * @return self Retourne l'instance de l'objet ProduitPhysique pour une chaîne d'appels.
     */
    public function setPoids(float $poids): self
    {
        $this->poids = $poids;
        return $this;
    }

    /**
     * @return float La longueur du produit en centimètres.
     */
    public function getLongueur(): float
    {
        return $this->longueur;
    }

    /**
     * @param float $longueur La longueur du produit en centimètres.
     * @return self Retourne l'instance de l'objet ProduitPhysique pour une chaîne d'appels.
     */
    public function setLongueur(float $longueur): self
    {
        $this->longueur = $longueur;
        return $this;
    }

    /**
     * @return float La largeur du produit en centimètres.
     */
    public function getLargeur(): float
    {
        return $this->largeur;
    }

    /**
     * @param float $largeur La largeur du produit en centimètres.
     * @return self Retourne l'instance de l'objet ProduitPhysique pour une chaîne d'appels.
     */
    public function setLargeur(float $largeur): self
    {
        $this->largeur = $largeur;
        return $this;
    }

    /**
     * @return float La hauteur du produit en centimètres.
     */
    public function getHauteur(): float
    {
        return $this->hauteur;
    }

    /**
     * @param float $hauteur La hauteur du produit en centimètres.
     * @return self Retourne l'instance de l'objet ProduitPhysique pour une chaîne d'appels.
     */
    public function setHauteur(float $hauteur): self
    {
        $this->hauteur = $hauteur;
        return $this;
    }
}
