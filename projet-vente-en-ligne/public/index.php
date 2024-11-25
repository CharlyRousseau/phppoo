<?php
require_once "../src/Entity/Utilisateur.php";
require_once "../src/Entity/Produit.php";

use Entity\Utilisateur;
use Entity\Produit;

$produit = new Produit("Téléphone", "Un super téléphone", 500, 10);
echo "Prix TTC : " . $produit->calculerPrixTTC() . PHP_EOL;
echo "Stock suffisant ? " .
    ($produit->verifierStock(5) ? "Oui" : "Non") .
    PHP_EOL;

$utilisateur = new Utilisateur("Charly", "charly@example.com", "securePass123");
echo "Email : " . $utilisateur->getEmail() . PHP_EOL;
echo "Mot de passe valide ? " .
    ($utilisateur->verifierMotDePasse("securePass123") ? "Oui" : "Non") .
    PHP_EOL;
