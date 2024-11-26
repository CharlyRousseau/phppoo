<?php
namespace Entity;

use Entity\Produit;

class ProduitNumerique extends Produit
{
    private string $lienTelechargement;
    private float $tailleFichier;
    private string $formatFichier;

    public function __construct(
        string $nom,
        string $description,
        float $prix,
        float $tailleFichier,
        string $formatFichier
    ) {
        parent::__construct($nom, $description, $prix, 1);
        $this->lienTelechargement = $this->genererLienTelechargement();
        $this->setTailleFichier($tailleFichier);
        $this->setFormatFichier($formatFichier);
    }

    public function calculerFraisLivraison(): float
    {
        return 0;
    }

    public function afficherDetails(): void
    {
        echo "Lien de téléchargement : " . $this->lienTelechargement . PHP_EOL;
        echo "Taille du fichier : " . $this->tailleFichier . " Mo" . PHP_EOL;
        echo "Format du fichier : " . $this->formatFichier . PHP_EOL;
    }

    public function genererLienTelechargement(): string
    {
        return "https://example.com/" . uniqid();
    }

    public function getLienTelechargement(): string
    {
        return $this->lienTelechargement;
    }

    public function getTailleFichier(): float
    {
        return $this->tailleFichier;
    }

    public function setTailleFichier(float $tailleFichier): self
    {
        $this->tailleFichier = $tailleFichier;
        return $this;
    }

    public function getFormatFichier(): string
    {
        return $this->formatFichier;
    }

    public function setFormatFichier(string $formatFichier): self
    {
        $this->formatFichier = $formatFichier;
        return $this;
    }
}
