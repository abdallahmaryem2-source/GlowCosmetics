# Projet Glow Cosmetics

Application de e-commerce PHP / MySQL pour une boutique de cosmétiques.

## 🧩 Présentation

Cette application permet à un client de :
- consulter le catalogue de produits
- ajouter des produits au panier
- passer des commandes
- enregistrer des favoris

Elle permet à un administrateur de :
- ajouter, modifier et supprimer des produits
- appliquer une promo sur un produit
- gérer le statut des commandes

## 📁 Structure du dépôt

- `assets/` : styles CSS
- `config/` : configuration de la base de données
- `controllers/` : logique de traitement des formulaires
- `models/` : classes métiers et accès aux données
- `public/uploads/products/` : images produits
- `views/` : pages frontend
- `projet.sql` : dump SQL initial
- `database_migrations.sql` : migration promo/favoris

## ✅ Prérequis

- XAMPP ou WAMP
- PHP 8+
- MySQL/MariaDB
- Navigateur web

## 🚀 Installation rapide

1. Placez le dossier `projet_mariem` dans `C:\xampp\htdocs\`.
2. Lancez Apache et MySQL depuis XAMPP.
3. Créez la base de données `projet` dans phpMyAdmin ou avec MySQL.

### Importer la base

Via phpMyAdmin :
- sélectionnez la base `projet`
- importez `projet.sql`
- si vous avez déjà importé `projet.sql`, exécutez `database_migrations.sql` pour ajouter `promo_price`, `promo_label` et la table `favorites`.

Via terminal MySQL :
```powershell
mysql -u root -p projet < projet.sql
mysql -u root -p projet < database_migrations.sql
```

4. Vérifiez `config/db_connect.php` :
```php
$host = 'localhost';
$dbname = 'projet';
$username = 'root';
$password = '';
```
Modifiez si nécessaire selon votre configuration.

5. Ouvrez le projet dans le navigateur :

`http://localhost/projet_mariem/views/catalogue.php`

## 🔧 Comptes par défaut

- Admin : `ameni / ameni@gmail.com`
- Client : `splinter / splinter@gmail.com`

## 🛠 Fonctionnalités

### Client
- recherche et filtre par catégorie
- ajout au panier
- gestion du panier
- passage de commande
- ajout / suppression de favoris

### Admin
- ajout produit avec image
- modification produit
- possibilité de définir une promotion (`promo_price` / `promo_label`)
- gestion du statut des commandes (`pending`, `confirmed`, `shipped`, `cancelled`)

## 📌 Déploiement GitHub rapide

1. Ouvrez le terminal dans `c:\xampp\htdocs\projet_mariem`
2. Initialisez Git et ajoutez les fichiers :
```powershell
git init
git add .
git commit -m "Initial commit – Glow Cosmetics project"
```
3. Créez un dépôt sur GitHub et copiez l’URL du dépôt.
4. Ajoutez la remote et poussez :
```powershell
git remote add origin https://github.com/TON_UTILISATEUR/NOM_DU_REPO.git
git branch -M main
git push -u origin main
```

## 📄 Livrables à fournir

- `README.md` dans le dépôt GitHub
- `projet.sql` et `database_migrations.sql`
- PDF du cahier des charges
- PDF du rapport d’utilisation de l’IA

## 💡 Astuce rapide

Pour le cahier des charges, incluez :
- analyse des besoins
- schéma relationnel
- capture d’écran de chaque page importante

Pour le rapport IA, décrivez :
- prompt initial
- réponse reçue
- ajustements que vous avez faits
- pourquoi vous avez choisi d’utiliser l’IA
