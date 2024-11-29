<?php

namespace App\Entity\Utilisateur;

abstract class Utilisateur
{
    private int $id;
    private string $nom;
    private string $email;
    private string $motDePasse;

    /**
     * @param string $nom Le nom de l'utilisateur.
     * @param string $email L'email de l'utilisateur.
     * @param string|null $motDePasse Le mot de passe de l'utilisateur (en clair). Par défaut null.
     */
    public function __construct(
        string $nom,
        string $email,
        string $motDePasse = null
    ) {
        $this->nom = $nom;
        $this->email = $email;

        // Si un mot de passe est fourni, on le hache
        if ($motDePasse !== null) {
            $this->setMotDePasseHash($motDePasse);
        }
    }

    /**
     * @return int L'identifiant de l'utilisateur.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id L'identifiant de l'utilisateur.
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string Le nom de l'utilisateur.
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param string $nom Le nom de l'utilisateur.
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string L'email de l'utilisateur.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email L'email de l'utilisateur.
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string Le mot de passe haché de l'utilisateur.
     */
    public function getMotDePasse(): string
    {
        return $this->motDePasse;
    }

    /**
     * @param string $motDePasseHash Le mot de passe haché de l'utilisateur.
     */
    public function setMotDePasseHash(string $motDePasseHash): void
    {
        $this->motDePasse = $motDePasseHash; // Pas de validation ici, car il s'agit d'un hash.
    }

    /**
     * @param string $motDePasse Le mot de passe en clair de l'utilisateur.
     * @throws \InvalidArgumentException Si le mot de passe est inférieur à 8 caractères.
     */
    public function setMotDePasse(string $motDePasse): void
    {
        //TO DO Améliorer les critères de sécurité
        if (strlen($motDePasse) < 8) {
            throw new \InvalidArgumentException(
                "Le mot de passe doit contenir au moins 8 caractères."
            );
        }

        $this->motDePasse = password_hash($motDePasse, PASSWORD_DEFAULT);
    }
}
