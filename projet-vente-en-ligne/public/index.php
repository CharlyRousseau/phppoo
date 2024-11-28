<?php

define("HTML_EOL", "<br>");

require "../vendor/autoload.php";

echo "udi";

use App\Database\DatabaseConnection;
use App\Repository\ProduitRepository;
use App\Entity\Produit\ProduitPhysique;
use App\Entity\Produit\ProduitNumerique;
use App\Entity\Produit\ProduitPerissable;
use App\Entity\Panier;
use App\Entity\Utilisateur\Client;
use App\Entity\Utilisateur\Admin;
use App\Entity\Utilisateur\Vendeur;
use App\Factory\ProduitFactory;
use App\Config\ConfigurationManager;
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

// Exemple de création de produit via la Factory
$factory = new ProduitFactory();

// Créer un produit physique
$produitPhysique = $factory->creerProduit("physique", [
    "nom" => "Table",
    "description" => "Table en bois",
    "prix" => 100,
    "stock" => 20,
    "poids" => 15.5,
    "longueur" => 150,
    "largeur" => 80,
    "hauteur" => 75,
]);

// Créer un produit numérique
$produitNumerique = $factory->creerProduit("numerique", [
    "nom" => "Ebook PHP",
    "description" => "Un guide complet sur PHP",
    "prix" => 25,
    "stock" => 100,
    "fichier" => "ebook_php.pdf",
]);

echo $produitPhysique->getNom() . HTML_EOL;
echo $produitNumerique->getNom() . HTML_EOL;
echo $produitPerissable->getNom() . HTML_EOL;

$configManager = ConfigurationManager::getInstance();
echo $configManager->get("tva") . HTML_EOL; // Affiche 20
$configManager->set("tva", 25); // Met à jour la TVA
echo $configManager->get("tva") . HTML_EOL; // Affiche 20

// Créer un produit
$produitRepo = new ProduitRepository();

$productId = $produitRepo->create($produitPhysique);

// Lire un produit
$produitRecupere = $produitRepo->read($productId);

// Mettre à jour un produit
$produitRecupere->setStock(45);
$produitRepo->update($produitRecupere);

// Supprimer un produit
$produitRepo->delete($productId);
