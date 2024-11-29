<?php

define("HTML_EOL", "<br>");

require "../vendor/autoload.php";

use App\Entity\Categorie;
use App\Entity\Produit\ProduitPhysique;
use App\Entity\Produit\ProduitNumerique;
use App\Entity\Produit\ProduitPerissable;
use App\Entity\Utilisateur\Client;
use App\Repository\UtilisateurRepository;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;

$utilisateurRepo = new UtilisateurRepository();

// -------------------- Création --------------------
echo "=== Création d'un utilisateur ===" . HTML_EOL;

$nouvelUtilisateur = new Client(
    "John Doe",
    "john.doe@example.com",
    "adresse",
    "password123Z2"
);
$userId = $utilisateurRepo->create($nouvelUtilisateur);

echo "Utilisateur créé avec l'ID : " . $userId . HTML_EOL;

// -------------------- Lecture --------------------
echo "=== Lecture d'un utilisateur ===" . HTML_EOL;

$utilisateur = $utilisateurRepo->read($userId);
if ($utilisateur) {
    echo "Nom : " . $utilisateur->getNom() . HTML_EOL;
    echo "Email : " . $utilisateur->getEmail() . HTML_EOL;
} else {
    echo "Utilisateur non trouvé." . HTML_EOL;
}

// -------------------- Mise à jour --------------------
echo "=== Mise à jour d'un utilisateur ===" . HTML_EOL;

$utilisateur->setNom("John Updated");
$utilisateur->setEmail("updated.john@example.com");
$utilisateur->setMotDePasse("newPassword123");
$utilisateurRepo->update($utilisateur);

echo "Utilisateur mis à jour." . HTML_EOL;

$utilisateurMisAJour = $utilisateurRepo->read($userId);
echo "Nom après mise à jour : " . $utilisateurMisAJour->getNom() . HTML_EOL;
echo "Email après mise à jour : " . $utilisateurMisAJour->getEmail() . HTML_EOL;

// -------------------- Recherche par critères --------------------
echo "=== Recherche par critères ===" . HTML_EOL;

$criteria = ["email" => "updated.john@example.com"];
$utilisateurs = $utilisateurRepo->findBy($criteria);

foreach ($utilisateurs as $u) {
    echo "Utilisateur trouvé : " .
        $u->getNom() .
        " (" .
        $u->getEmail() .
        ")" .
        HTML_EOL;
}

// -------------------- Lecture de tous les utilisateurs --------------------
echo "=== Lecture de tous les utilisateurs ===" . HTML_EOL;

$tousUtilisateurs = $utilisateurRepo->findAll();

foreach ($tousUtilisateurs as $u) {
    echo "Utilisateur : " .
        $u->getNom() .
        " (" .
        $u->getEmail() .
        ")" .
        HTML_EOL;
}

// -------------------- Suppression --------------------
echo "=== Suppression d'un utilisateur ===" . HTML_EOL;

$utilisateurRepo->delete($userId);
echo "Utilisateur avec l'ID $userId supprimé." . HTML_EOL;

// Vérification de la suppression
$utilisateurSupprime = $utilisateurRepo->read($userId);
if (!$utilisateurSupprime) {
    echo "L'utilisateur a bien été supprimé." . HTML_EOL;
}

echo "=== Fin des tests ===" . HTML_EOL;

$categorieRepo = new CategorieRepository();

// -------------------- Création --------------------
echo "=== Création d'une catégorie ===" . PHP_EOL;

$nouvelleCategorie = new Categorie(
    "Catégorie 1",
    "Description de la catégorie 1"
);

$categorieId = $categorieRepo->create($nouvelleCategorie);

echo "Catégorie créée avec l'ID : " . $categorieId . PHP_EOL;

// -------------------- Lecture --------------------
echo "=== Lecture d'une catégorie ===" . PHP_EOL;

$categorie = $categorieRepo->read($categorieId);

if ($categorie) {
    echo "Nom : " . $categorie->getNom() . PHP_EOL;
    echo "Description : " . $categorie->getDescription() . PHP_EOL;
} else {
    echo "Catégorie non trouvée." . PHP_EOL;
}

// -------------------- Mise à jour --------------------
echo "=== Mise à jour d'une catégorie ===" . PHP_EOL;

$categorie->setNom("Catégorie 1 Mise à Jour");
$categorie->setDescription("Description mise à jour de la catégorie 1");
$categorieRepo->update($categorie);

echo "Catégorie mise à jour." . PHP_EOL;

$categorieMiseAJour = $categorieRepo->read($categorieId);
echo "Nom après mise à jour : " . $categorieMiseAJour->getNom() . PHP_EOL;
echo "Description après mise à jour : " .
    $categorieMiseAJour->getDescription() .
    PHP_EOL;

// -------------------- Recherche par critères --------------------
echo "=== Recherche par critères ===" . PHP_EOL;

$criteria = ["nom" => "Catégorie 1 Mise à Jour"];
$categories = $categorieRepo->findBy($criteria);

foreach ($categories as $cat) {
    echo "Catégorie trouvée : " .
        $cat->getNom() .
        " (" .
        $cat->getDescription() .
        ")" .
        PHP_EOL;
}

// -------------------- Lecture de toutes les catégories --------------------
echo "=== Lecture de toutes les catégories ===" . PHP_EOL;

$toutesCategories = $categorieRepo->findAll();

foreach ($toutesCategories as $cat) {
    echo "Catégorie : " .
        $cat->getNom() .
        " (" .
        $cat->getDescription() .
        ")" .
        PHP_EOL;
}

// -------------------- Suppression --------------------
echo "=== Suppression d'une catégorie ===" . PHP_EOL;

$categorieRepo->delete($categorieId);
echo "Catégorie avec l'ID $categorieId supprimée." . PHP_EOL;

// Vérification de la suppression
$categorieSupprimee = $categorieRepo->read($categorieId);
if (!$categorieSupprimee) {
    echo "La catégorie a bien été supprimée." . PHP_EOL;
}

echo "=== Fin des tests ===" . PHP_EOL;

$produitRepo = new ProduitRepository();

// -------------------- Création --------------------
echo "=== Création d'un produit physique ===" . HTML_EOL;

$produitPhysique = new ProduitPhysique(
    "Produit Physique 1",
    "Produit Physique Description",
    29.99, //prix
    2, //stock
    500, // poids
    10, // longueur
    5, // largeur
    20 // hauteur
);
$produitId = $produitRepo->create($produitPhysique);

echo "Produit créé avec l'ID : " . $produitId . HTML_EOL;

// -------------------- Lecture --------------------
echo "=== Lecture d'un produit ===" . HTML_EOL;

$produit = $produitRepo->read($produitId);
if ($produit) {
    echo "Nom : " . $produit->getNom() . HTML_EOL;
    echo "Prix : " . $produit->getPrix() . HTML_EOL;
    echo "Description : " . $produit->getDescription() . HTML_EOL;
} else {
    echo "Produit non trouvé." . HTML_EOL;
}

// -------------------- Mise à jour --------------------
echo "=== Mise à jour d'un produit ===" . HTML_EOL;

$produit->setNom("Produit Physique 1 Mis à Jour");
$produit->setPrix(39.99);
$produit->setDescription("Produit Physique Description Mise à Jour");
$produitRepo->update($produit);

echo "Produit mis à jour." . HTML_EOL;

$produitMisAJour = $produitRepo->read($produitId);
echo "Nom après mise à jour : " . $produitMisAJour->getNom() . HTML_EOL;
echo "Prix après mise à jour : " . $produitMisAJour->getPrix() . HTML_EOL;
echo "Description après mise à jour : " .
    $produitMisAJour->getDescription() .
    HTML_EOL;

// -------------------- Recherche par critères --------------------
echo "=== Recherche par critères ===" . HTML_EOL;

$criteria = ["nom" => "Produit Physique 1 Mis à Jour"];
$produits = $produitRepo->findBy($criteria);

foreach ($produits as $p) {
    echo "Produit trouvé : " .
        $p->getNom() .
        " (" .
        $p->getPrix() .
        ")" .
        HTML_EOL;
}

// -------------------- Lecture de tous les produits --------------------
echo "=== Lecture de tous les produits ===" . HTML_EOL;

$tousProduits = $produitRepo->findAll();

foreach ($tousProduits as $p) {
    echo "Produit : " . $p->getNom() . " (" . $p->getPrix() . ")" . HTML_EOL;
}

// -------------------- Suppression --------------------
echo "=== Suppression d'un produit ===" . HTML_EOL;

$produitRepo->delete($produitId);
echo "Produit avec l'ID $produitId supprimé." . HTML_EOL;

// Vérification de la suppression
$produitSupprime = $produitRepo->read($produitId);
if (!$produitSupprime) {
    echo "Le produit a bien été supprimé." . HTML_EOL;
}

echo "=== Fin des tests ===" . HTML_EOL;

// -------------------- Création d'un produit numérique --------------------
echo "=== Création d'un produit numérique ===" . HTML_EOL;

$produitNumerique = new ProduitNumerique(
    "Produit Numérique 1",
    "Produit Numérique Description",
    1500,
    19.99,
    "application/zip" // formatFichier
);
$produitId = $produitRepo->create($produitNumerique);

echo "Produit numérique créé avec l'ID : " . $produitId . HTML_EOL;

// -------------------- Lecture d'un produit numérique --------------------
echo "=== Lecture d'un produit numérique ===" . HTML_EOL;

$produit = $produitRepo->read($produitId);
if ($produit) {
    echo "Nom : " . $produit->getNom() . HTML_EOL;
    echo "Prix : " . $produit->getPrix() . HTML_EOL;
    echo "Description : " . $produit->getDescription() . HTML_EOL;

    if ($produit instanceof ProduitNumerique) {
        echo "Lien de téléchargement : " .
            $produit->getLienTelechargement() .
            PHP_EOL;
    } else {
        echo "Ce produit n'est pas numérique." . HTML_EOL;
    }
} else {
    echo "Produit non trouvé." . HTML_EOL;
}

// -------------------- Création d'un produit périssable --------------------
echo "=== Création d'un produit périssable ===" . HTML_EOL;

$produitPerissable = new ProduitPerissable(
    "Produit Périssable 1",
    "Produit Périssable Description",
    9.99,
    3,
    new DateTime("2025-12-31"),
    4
);
$produitId = $produitRepo->create($produitPerissable);

echo "Produit périssable créé avec l'ID : " . $produitId . HTML_EOL;

// -------------------- Lecture d'un produit périssable --------------------
echo "=== Lecture d'un produit périssable ===" . HTML_EOL;

$produit = $produitRepo->read($produitId);

if ($produit) {
    echo "Nom : " . $produit->getNom() . PHP_EOL;
    echo "Prix : " . $produit->getPrix() . PHP_EOL;
    echo "Description : " . $produit->getDescription() . PHP_EOL;

    if ($produit instanceof ProduitPerissable) {
        echo "Date d'expiration : " . $produit->getDateExpiration() . PHP_EOL;
        echo "Température de stockage : " .
            $produit->getTemperatureStockage() .
            PHP_EOL;
    } else {
        echo "Ce produit n'est pas périssable." . PHP_EOL;
    }
} else {
    echo "Produit non trouvé." . PHP_EOL;
}

echo "=== Fin des tests ===" . HTML_EOL;
?>
