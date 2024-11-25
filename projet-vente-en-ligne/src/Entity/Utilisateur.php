<?php
namespace Entity;

use DateTime;

/**
 * Class Utilisateur
 * Représente un utilisateur dans le système.
 */
class Utilisateur
{
    private ?int $id = null;
    private string $nom;
    private string $email;
    private string $motDePasse;
    private DateTime $dateInscription;

    public function __construct(string $nom, string $email, string $motDePasse)
    {
        $this->setNom($nom);
        $this->setEmail($email);
        $this->setMotDePasse($motDePasse);
        $this->dateInscription = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        if (empty($nom)) {
            throw new \InvalidArgumentException(
                "Le nom ne peut pas être vide."
            );
        }
        $this->nom = $nom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(
                "L'adresse email est invalide."
            );
        }
        $this->email = $email;
    }

    public function getMotDePasse(): string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): void
    {
        if (strlen($motDePasse) < 8) {
            throw new \InvalidArgumentException(
                "Le mot de passe doit contenir au moins 8 caractères."
            );
        }
        $this->motDePasse = $motDePasse;
    }

    public function verifierMotDePasse(string $motDePasse): bool
    {
        return $this->motDePasse === $motDePasse;
    }

    public function mettreAJourProfil(
        string $nom,
        string $email,
        string $motDePasse
    ): void {
        $this->setNom($nom);
        $this->setEmail($email);
        $this->setMotDePasse($motDePasse);
    }
}
