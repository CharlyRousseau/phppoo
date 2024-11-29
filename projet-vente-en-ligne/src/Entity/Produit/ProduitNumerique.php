<?php
namespace App\Entity\Produit;

use App\Entity\Produit\Produit;

class ProduitNumerique extends Produit
{
    private string $lienTelechargement;
    private float $tailleFichier;
    private string $formatFichier;

    /**
     * @param string $nom Le nom du produit.
     * @param string $description La description du produit.
     * @param float $prix Le prix du produit.
     * @param float $tailleFichier La taille du fichier en Mo.
     * @param string $formatFichier Le format du fichier.
     */
    public function __construct(
        string $nom,
        string $description,
        float $prix,
        float $tailleFichier,
        string $formatFichier
    ) {
        parent::__construct($nom, $description, $prix, 1); // Un produit numérique est toujours en stock avec 1 unité.
        $this->lienTelechargement = $this->genererLienTelechargement();
        $this->setTailleFichier($tailleFichier);
        $this->setFormatFichier($formatFichier);
    }

    /**
     * @return float Retourne 0 pour les frais de livraison.
     */
    public function calculerFraisLivraison(): float
    {
        return 0;
    }

    /**
     * @return void Affiche les informations du produit numérique.
     */
    public function afficherDetails(): void
    {
        echo "Lien de téléchargement : " . $this->lienTelechargement . PHP_EOL;
        echo "Taille du fichier : " . $this->tailleFichier . " Mo" . PHP_EOL;
        echo "Format du fichier : " . $this->formatFichier . PHP_EOL;
    }

    /**
     * @return string Retourne le lien de téléchargement.
     */
    public function genererLienTelechargement(): string
    {
        return "https://example.com/" . uniqid();
    }

    /**
     * @return string Le lien de téléchargement du produit numérique.
     */
    public function getLienTelechargement(): string
    {
        return $this->lienTelechargement;
    }

    /**
     * @return float La taille du fichier.
     */
    public function getTailleFichier(): float
    {
        return $this->tailleFichier;
    }

    /**
     * @param float $tailleFichier La taille du fichier en Mo.
     *
     * @return self Retourne l'instance de l'objet ProduitNumerique pour une chaîne d'appels.
     */
    public function setTailleFichier(float $tailleFichier): self
    {
        $this->tailleFichier = $tailleFichier;
        return $this;
    }

    /**
     * @return string Le format du fichier (par exemple "PDF", "MP3").
     */
    public function getFormatFichier(): string
    {
        return $this->formatFichier;
    }

    /**
     * @param string $formatFichier Le format du fichier (par exemple "PDF", "MP3").
     *
     * @return self Retourne l'instance de l'objet ProduitNumerique pour une chaîne d'appels.
     */
    public function setFormatFichier(string $formatFichier): self
    {
        $this->formatFichier = $formatFichier;
        return $this;
    }
}
