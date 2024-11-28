<?php
namespace App\Factory;
use App\Entity\Produit\Produit;
use App\Entity\Produit\ProduitPhysique;
use App\Entity\Produit\ProduitNumerique;
use App\Entity\Produit\ProduitPerissable;

class ProduitFactory
{
    /**
     * @param array<int,mixed> $data
     */
    public function creerProduit(string $type, array $data): Produit
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
     * @param array<int,mixed> $data
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
     * @param array<int,mixed> $data
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
     * @param array<int,mixed> $data
     */
    private function creerProduitNumerique(array $data): ProduitNumerique
    {
        $this->validerProduitNumerique($data);
        return new ProduitNumerique(
            $data["nom"],
            $data["description"],
            $data["prix"],
            $data["stock"],
            $data["fichier"]
        );
    }
    /**
     * @param array<int,mixed> $data
     */
    private function validerProduitNumerique(array $data): void
    {
        if (empty($data["nom"]) || !is_string($data["nom"])) {
            throw new \InvalidArgumentException(
                "Le nom est requis et doit être une chaîne."
            );
        }
        if (empty($data["fichier"]) || !is_string($data["fichier"])) {
            throw new \InvalidArgumentException(
                "Le fichier numérique est requis."
            );
        }
    }

    /**
     * @param array<int,mixed> $data
     */
    private function creerProduitPerissable(array $data): ProduitPerissable
    {
        $this->validerProduitPerissable($data);
        return new ProduitPerissable(
            $data["nom"],
            $data["description"],
            $data["prix"],
            $data["stock"],
            $data["dateExpiration"]
        );
    }
    /**
     * @param array<int,mixed> $data
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
