<?php

define("HTML_EOL", "<br>");

require_once "../src/Entity/Produit/Produit.php";
require_once "../src/Entity/Produit/ProduitPhysique.php";
require_once "../src/Entity/Produit/ProduitNumerique.php";
require_once "../src/Entity/Produit/ProduitPerissable.php";
require_once "../src/Entity/Panier.php";
require_once "../src/Entity/Utilisateur/Utilisateur.php";
require_once "../src/Entity/Utilisateur/Client.php";
require_once "../src/Entity/Utilisateur/Admin.php";
require_once "../src/Entity/Utilisateur/Vendeur.php";

use Entity\ProduitPhysique;
use Entity\ProduitNumerique;
use Entity\ProduitPerissable;
use Entity\Panier;
use Entity\Client;
use Entity\Admin;
use Entity\Vendeur;

/** --------------DAY 1--------------- */

/**
$produit = new Produit("Téléphone", "Un super téléphone", 500, 10);
echo "Prix TTC : " . $produit->calculerPrixTTC() . HTML_EOL;
echo "Stock suffisant ? " .
    ($produit->verifierStock(5) ? "Oui" : "Non") .
    HTML_EOL;

$utilisateur = new Utilisateur("Charly", "charly@example.com", "securePass123");
echo "Email : " . $utilisateur->getEmail() . HTML_EOL;
echo "Mot de passe valide ? " .
    ($utilisateur->verifierMotDePasse("securePass123") ? "Oui" : "Non") .
    HTML_EOL;
*/

/** --------------DAY 2--------------- */

// -------------------- Tests Produits --------------------
echo "=== Tests Produits === ©" . HTML_EOL;

// Produit Physique
$produitPhysique = new ProduitPhysique(
    "Table",
    "Une belle table",
    9,
    150,
    10,
    120,
    60,
    75
);
echo "Produit Physique : " . $produitPhysique->getNom() . HTML_EOL;
echo "Volume : " . $produitPhysique->calculerVolume() . " cm3" . HTML_EOL;
echo "Frais Livraison : " .
    $produitPhysique->calculerFraisLivraison() .
    " €" .
    HTML_EOL;

// Produit Numérique
$produitNumerique = new ProduitNumerique(
    "E-book",
    "Un livre numérique",
    10,
    2,
    "PDF"
);

echo "Produit Numérique : " . $produitNumerique->getNom() . HTML_EOL;
echo "Lien Téléchargement : " .
    $produitNumerique->genererLienTelechargement() .
    HTML_EOL;
echo "Frais Livraison : " .
    $produitNumerique->calculerFraisLivraison() .
    " €" .
    HTML_EOL;

// Produit Périssable
$produitPerissable = new ProduitPerissable(
    "Lait",
    "Lait frais",
    2.5,
    50,
    new DateTime("2024-12-01"),
    4.0
);
echo "Produit Périssable : " . $produitPerissable->getNom() . HTML_EOL;
echo "Est Périmé ? " .
    ($produitPerissable->estPerime() ? "Oui" : "Non") .
    HTML_EOL;
echo "Frais Livraison : " .
    $produitPerissable->calculerFraisLivraison() .
    " €" .
    HTML_EOL;

echo HTML_EOL;

// -------------------- Tests Panier --------------------
echo "=== Tests Panier ===" . HTML_EOL;

$panier = new Panier();
$panier->ajouterArticle($produitPhysique, 2);
$panier->ajouterArticle($produitNumerique, 1);

echo "Total Panier : " . $panier->calculerTotal() . " €" . HTML_EOL;
echo "Nombre d'articles : " . $panier->compterArticles() . HTML_EOL;

$panier->retirerArticle($produitPhysique, 1);
echo "Nombre d'articles après retrait : " .
    $panier->compterArticles() .
    HTML_EOL;

echo HTML_EOL;

// -------------------- Tests Utilisateurs --------------------
echo "=== Tests Utilisateurs ===" . HTML_EOL;

// Client
$client = new Client(
    "Alice",
    "alice@example.com",
    "password123",
    "123 Rue Exemple",
    $panier
);
echo "Client : " . $client->getNom() . HTML_EOL;
echo "Adresse de livraison : " . $client->getAdresseLivraison() . HTML_EOL;

// Admin
$admin = new Admin("Bob", "bob@example.com", "adminPass", 5);
echo "Admin : " . $admin->getNom() . HTML_EOL;
echo "Niveau d'accès : " . $admin->getNiveauAcces() . HTML_EOL;

// Vendeur
$vendeur = new Vendeur("Charly", "charly@example.com", "vendeurPass", 10.0);
$vendeur->ajouterProduit($produitPhysique);
echo "Vendeur : " . $vendeur->getNom() . HTML_EOL;

echo HTML_EOL;

echo "=== Fin des tests ===" . HTML_EOL;
