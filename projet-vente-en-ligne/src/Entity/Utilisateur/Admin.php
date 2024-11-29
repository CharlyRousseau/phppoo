<?php
namespace App\Entity\Utilisateur;

use App\Entity\Utilisateur\Utilisateur;
use DateTime;

class Admin extends Utilisateur
{
    private int $niveauAcces;
    private DateTime $derniereConnexion;

    /**
     * @param string $nom Le nom de l'administrateur.
     * @param string $email L'email de l'administrateur.
     * @param int $niveauAcces Le niveau d'accès de l'administrateur.
     * @param string|null $motDePasse Le mot de passe de l'administrateur (facultatif).
     */

    public function __construct(
        string $nom,
        string $email,
        int $niveauAcces,
        ?string $motDePasse = null
    ) {
        parent::__construct($nom, $email, $motDePasse);
        $this->niveauAcces = $niveauAcces;
        $this->derniereConnexion = new DateTime();
    }

    /**
     * @return int Le niveau d'accès de l'administrateur.
     */
    public function getNiveauAcces(): int
    {
        return $this->niveauAcces;
    }

    /**
     * @param int $niveauAcces Le niveau d'accès à définir.
     *
     * @return self Retourne l'instance de l'administrateur pour une chaîne d'appels.
     */
    public function setNiveauAcces(int $niveauAcces): self
    {
        $this->niveauAcces = $niveauAcces;
        return $this;
    }

    /**
     * @return DateTime La dernière connexion de l'administrateur.
     */
    public function getDerniereConnexion(): DateTime
    {
        return $this->derniereConnexion;
    }

    /**
     * @return self Retourne l'instance de l'administrateur pour une chaîne d'appels.
     */
    public function setDerniereConnexion(): self
    {
        $this->derniereConnexion = new DateTime();
        return $this;
    }

    /**
     * Méthode permettant de gérer les utilisateurs.
     * (Actuellement vide, mais elle pourrait être étendue avec des fonctionnalités de gestion des utilisateurs).
     *
     * @return void
     */
    public function gererUtilisateurs(): void
    {
        echo "vide";
    }

    /**
     * Accède au journal système.
     * (Actuellement retourne un tableau vide, mais peut être étendu pour récupérer des journaux systèmes).
     *
     * @return array Un tableau de journaux système.
     */
    public function accederJournalSysteme(): array
    {
        return [];
    }

    public function afficherRoles(): void
    {
        echo "Admin";
    }
}
