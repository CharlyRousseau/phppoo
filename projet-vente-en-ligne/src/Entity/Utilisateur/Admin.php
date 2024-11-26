<?php
namespace Entity;

use Entity\Utilisateur;
use DateTime;

class Admin extends Utilisateur
{
    private int $niveauAcces;
    private DateTime $derniereConnexion;

    public function __construct(
        string $nom,
        string $email,
        string $motDePasse,
        int $niveauAcces
    ) {
        parent::__construct($nom, $email, $motDePasse);
        $this->niveauAcces = $niveauAcces;
        $this->derniereConnexion = new DateTime();
    }

    public function getNiveauAcces(): int
    {
        return $this->niveauAcces;
    }

    public function setNiveauAcces(int $niveauAcces): self
    {
        $this->niveauAcces = $niveauAcces;
        return $this;
    }

    public function getDerniereConnexion(): DateTime
    {
        return $this->derniereConnexion;
    }

    public function setDerniereConnexion(): self
    {
        $this->derniereConnexion = new DateTime();
        return $this;
    }

    public function gererUtilisateurs(): void
    {
        echo "vide";
    }

    public function accederJournalSysteme(): array
    {
        return [];
    }

    public function afficherRoles(): void
    {
        echo "Admin";
    }
}
