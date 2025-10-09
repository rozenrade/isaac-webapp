# Installation de l'application web Isaac's Ultimate Bravery

*Cette application n'est actuellement disponible qu'en local.*

## 📌 Prérequis
Avant de commencer, assurez-vous d'avoir installé les éléments suivants :

- [PHP](https://www.php.net/downloads) (version 8.1 ou supérieure recommandée)
- [Composer](https://getcomposer.org/download/)
- [Symfony CLI](https://symfony.com/download)
- [Node.js](https://nodejs.org/)
- [Git](https://git-scm.com/downloads)
- Une base de données compatible (MySQL, PostgreSQL, SQLite, etc.)

## 🚀 Installation

### 1️⃣ Installer PHP et Composer

Assurez-vous que PHP est bien installé en exécutant :

```sh
php -v
```

Puis installez Composer (gestionnaire de dépendances PHP) :

```sh
composer -V
```

Si Composer n'est pas installé, téléchargez-le avec :

```sh
curl -sS https://getcomposer.org/installer
```

### 2️⃣ Récupérer le projet

Clonez le projet depuis son dépôt Git :

```sh
git clone https://github.com/rozenrade/isaac-webapp.git
```

### 3️⃣ Installer les dépendances

Exécutez la commande suivante pour installer les dépendances PHP :

```sh
composer install
```

Installer également les dépendances JavaScript telles que Tailwind :

```sh
npm install
```

### 5️⃣ Générer la base de données

```sh
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### 6️⃣ Lancer le serveur Symfony

Démarrez le serveur de développement avec la commande :

```sh
symfony serve
```

Le projet sera accessible à l'adresse : `http://127.0.0.1:8000`

Enfin, exécutez cette commande pour initier Tailwind :

```sh
npm run dev
```
---

Avec toutes ces commandes effectuées vous aurez la possibilité de naviguer librement depuis votre Windows sur l'application web. 
