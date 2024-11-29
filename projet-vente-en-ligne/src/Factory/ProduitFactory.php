<?php
namespace App\Factory;

use App\Entity\Produit\Produit;
use App\Entity\Produit\ProduitPhysique;
use App\Entity\Produit\ProduitNumerique;
use App\Entity\Produit\ProduitPerissable;
use DateTime;
class ProduitFactory
{
    /**
     * @param string $type Le type de produit à créer. Peut être "physique", "numerique" ou "perissable".
     * @param array<int,mixed> $data Les données nécessaires à la création du produit.
     *
     * @return Produit Le produit créé.
     *
     * @throws \InvalidArgumentException Si le type de produit est inconnu.
     */
    public function creerProduit(string $type, array $data): mixed
    {
        switch ($type) {
            case "physique":
                return $this->creerProduitPhysique($data);
            case "numerique":
                return $this->creerProduitNumerique($data);
            case "perissable":
                return $this->creerProduitPerissable($data);
            default:
                throw new \InvalidArgumentException(
                    "Type de produit inconnu: $type"
                );
        }
    }

    /**
     *
     * @param array<int,mixed> $data Les données nécessaires pour créer le produit physique.
     *
     * @return ProduitPhysique Le produit physique créé.
     *
     * @throws \InvalidArgumentException Si les données sont invalides.
     */
    private function creerProduitPhysique(array $data): ProduitPhysique
    {
        $this->validerProduitPhysique($data);
        return new ProduitPhysique(
            $data["nom"],
            $data["description"],
            $data["prix"],
            $data["stock"],
            $data["poids"],
            $data["longueur"],
            $data["largeur"],
            $data["hauteur"]
        );
    }

    /**
     *
     * @param array<int,mixed> $data Les données à valider pour le produit physique.
     *
     * @throws \InvalidArgumentException Si les données sont invalides (nom manquant ou dimensions manquantes).
     */
    private function validerProduitPhysique(array $data): void
    {
        if (empty($data["nom"]) || !is_string($data["nom"])) {
            throw new \InvalidArgumentException(
                "Le nom est requis et doit être une chaîne."
            );
        }
        if (
            !isset(
                $data["poids"],
                $data["longueur"],
                $data["largeur"],
                $data["hauteur"]
            )
        ) {
            throw new \InvalidArgumentException(
                "Les dimensions et le poids doivent être fournis."
            );
        }
    }

    /**
     *
     * @param array<int,mixed> $data Les données nécessaires pour créer le produit numérique.
     *
     * @return ProduitNumerique Le produit numérique créé.
     *
     * @throws \InvalidArgumentException Si les données sont invalides.
     */
    private function creerProduitNumerique(array $data): ProduitNumerique
    {
        $this->validerProduitNumerique($data);
        return new ProduitNumerique(
            $data["nom"],
            $data["description"],
            $data["prix"],
            $data["stock"],
            $data["formatFichier"]
        );
    }

    /**
     *
     * @param array<int,mixed> $data Les données à valider pour le produit numérique.
     *
     * @throws \InvalidArgumentException Si les données sont invalides (nom manquant ou fichier manquant).
     */
    private function validerProduitNumerique(array $data): void
    {
        if (empty($data["nom"]) || !is_string($data["nom"])) {
            throw new \InvalidArgumentException(
                "Le nom est requis et doit être une chaîne."
            );
        }
        if (
            empty($data["formatFichier"]) ||
            !is_string($data["formatFichier"])
        ) {
            throw new \InvalidArgumentException(
                "Le fichier numérique est requis."
            );
        }
    }

    /**
     *
     * @param array<int,mixed> $data Les données nécessaires pour créer le produit périssable.
     *
     * @return ProduitPerissable Le produit périssable créé.
     *
     * @throws \InvalidArgumentException Si les données sont invalides.
     */
    private function creerProduitPerissable(array $data): ProduitPerissable
    {
        $this->validerProduitPerissable($data);
        return new ProduitPerissable(
            $data["nom"],
            $data["description"],
            $data["prix"],
            $data["stock"],
            new DateTime($data["dateExpiration"]),
            $data["temperatureStockage"]
        );
    }

    /**
     *
     * @param array<int,mixed> $data Les données à valider pour le produit périssable.
     *
     * @throws \InvalidArgumentException Si les données sont invalides (nom manquant ou date d'expiration invalide).
     */
    private function validerProduitPerissable(array $data): void
    {
        if (empty($data["nom"]) || !is_string($data["nom"])) {
            throw new \InvalidArgumentException(
                "Le nom est requis et doit être une chaîne."
            );
        }
        if (
            empty($data["dateExpiration"]) ||
            !strtotime($data["dateExpiration"])
        ) {
            throw new \InvalidArgumentException(
                "La date d'expiration est requise et doit être valide."
            );
        }
    }
}
