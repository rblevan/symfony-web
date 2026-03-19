# Projet Web L3 - Application de Vente en Ligne

⚠️ **Statut : En cours de développement (Work In Progress)** ⚠️

Ce projet est réalisé dans le cadre de l'UE Web de la Licence 3 Informatique.
Il s'agit d'une application e-commerce développée avec **Symfony 7.3** et **PHP 8.2+**, imposant des contraintes strictes d'architecture et de base de données.

## 🛠️ Technologies utilisées
* **Framework :** Symfony 7.3
* **Langage :** PHP 8.2+
* **Base de données :** SQLite
* **Templating :** Twig (Architecture à 3 niveaux)

## ⚙️ Installation locale (Pour la correction)

Si vous clonez ce projet sur une nouvelle machine, voici la marche à suivre pour le lancer :

1.  **Installer les dépendances :**
    ```bash
    composer install
    ```
2.  **Créer et mettre à jour la base de données :**
    ```bash
    php bin/console doctrine:migrations:migrate
    ```
3.  **Charger les comptes utilisateurs de test (Fixtures) :**
    ```bash
    php bin/console doctrine:fixtures:load
    ```
4.  **Lancer le serveur :**
    ```bash
    symfony server:start -d
    ```