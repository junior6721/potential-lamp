# Inventix — Application de gestion de stock

Application web de gestion de stock développée avec **Laravel** (PHP) et **MySQL**, permettant de gérer des produits, des catégories et des mouvements d'entrée/sortie de stock, avec un tableau de bord et des rapports.

---

## 🎯 Objectif de l'application

Inventix permet à une entreprise de :
- Suivre en temps réel les quantités de produits en stock
- Enregistrer chaque entrée (réception de marchandise) et chaque sortie (vente, livraison)
- Visualiser l'état du stock et son évolution via des graphiques
- Générer des documents PDF (bons de mouvement, rapports mensuels)
- Gérer les accès selon deux rôles : **Administrateur** et **Employé**

---

## 👥 Rôles et accès

L'application utilise un système de connexion (login) avec deux niveaux d'accès :

| Rôle | Ce qu'il peut faire |
|---|---|
| **Administrateur** | Accès complet : gestion des produits, catégories, mouvements, utilisateurs, rapports |
| **Employé** | Accès aux opérations courantes : consulter le stock, enregistrer des mouvements |

Chaque utilisateur dispose de son propre compte et de sa propre page de profil.

---

## 🗺️ Visite guidée — page par page

### 1. Page de connexion (Login)
Point d'entrée de l'application. Chaque utilisateur se connecte avec son email et son mot de passe. Selon son rôle (Admin ou Employé), il accède à des fonctionnalités différentes.

### 2. Tableau de bord (Dashboard)
Page d'accueil après connexion. Elle affiche :
- Les statistiques générales en temps réel (nombre de produits, mouvements récents, etc.)
- **Graphique en barres** : comparaison des entrées et sorties de stock sur les 7 derniers jours
- **Graphique en donut** : top 5 des produits les plus mouvementés

Cette page donne une vue d'ensemble instantanée de l'activité du stock.

### 3. Produits
Gestion complète (créer, consulter, modifier, supprimer) de la liste des produits de l'entreprise. Chaque produit possède un nom, une catégorie, une quantité en stock et un prix.
- Un bouton permet d'**exporter en PDF** l'état complet du stock (liste des produits avec quantités et valeurs).

### 4. Catégories
Gestion complète des catégories de produits (ex : Fournitures, Électronique, etc.), permettant d'organiser les produits par famille.

### 5. Entrées / Sorties (Mouvements de stock)
C'est le cœur de l'application. Chaque mouvement de stock y est enregistré :
- **Entrée** : ajout de marchandise au stock (ex : réception fournisseur)
- **Sortie** : retrait de marchandise du stock (ex : vente, livraison)

Chaque mouvement met à jour automatiquement la quantité du produit concerné. Un bouton sur chaque ligne permet d'imprimer un **bon de mouvement officiel** (PDF avec zones de signature), utile comme preuve de réception ou de livraison.

### 6. Nouveau mouvement
Formulaire dédié pour enregistrer rapidement une nouvelle entrée ou sortie de stock, sans devoir passer par la liste complète.

### 7. Rapport mensuel
Page qui calcule et affiche automatiquement, pour le mois en cours :
- Le total des entrées et des sorties
- Le nombre de mouvements
- La valeur actuelle du stock
- Le solde du mois (entrées − sorties)

Un bouton **« Exporter en PDF »** permet de télécharger ce même rapport sous forme de document, utile pour l'archivage ou la présentation à la direction.

### 8. Mon profil
Chaque utilisateur peut consulter et modifier ses informations personnelles :
- **Mes infos** : nom et email
- **Mot de passe** : modification avec indicateur de force du mot de passe
- **Historique** : ses 5 derniers mouvements de stock effectués

### 9. Thème sombre / clair
Un bouton dans la barre latérale permet de basculer entre un thème sombre et un thème clair, selon la préférence visuelle de l'utilisateur. Ce choix est mémorisé automatiquement.

---

## 📄 Exports PDF disponibles

L'application génère 3 types de documents PDF (via la librairie DomPDF) :

1. **État du stock** — liste complète des produits avec quantités et valeurs (depuis la page Produits)
2. **Bon de mouvement** — document individuel pour une entrée ou sortie, avec zones de signature (depuis la page Mouvements)
3. **Rapport mensuel** — résumé chiffré du mois en cours (depuis la page Rapport mensuel)

---

## 🛠️ Aspect technique (résumé)

| Élément | Choix technique |
|---|---|
| Framework backend | Laravel 12 (PHP 8.2) |
| Base de données | MySQL |
| Génération de PDF | DomPDF (`barryvdh/laravel-dompdf`) |
| Graphiques | Chart.js |
| Interface | Bootstrap (icônes Bootstrap Icons) |
| Authentification | Système de login natif Laravel avec gestion de rôles (middleware `CheckRole`) |

### Structure des données principales
- **Users** — comptes utilisateurs avec rôle (admin / employé)
- **Catégories** — familles de produits
- **Produits** — articles en stock (nom, catégorie, quantité, prix)
- **Mouvements** — historique des entrées et sorties, liés à un produit et à un utilisateur

---

## 🚀 Lancer l'application en local

```bash
# Installer les dépendances
composer install

# Configurer le fichier .env (base de données MySQL)
# DB_DATABASE=stock_db
# DB_USERNAME=root
# DB_PASSWORD=

# Lancer les migrations et les données de test
php artisan migrate:fresh --seed

# Démarrer le serveur
php artisan serve
```

L'application est alors accessible sur `http://127.0.0.1:8000`.

---

## 📌 Pistes d'évolution possibles

- Import Excel pour ajouter plusieurs produits en une seule fois
- Notifications automatiques en cas de rupture de stock
- Gestion de plusieurs dépôts/entrepôts séparés
- Recherche globale dans la barre de navigation