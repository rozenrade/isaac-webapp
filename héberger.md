# Hébergement d'un projet Symfony sur un VPS IONOS

## 1. Configuration du domaine

1. **Récupérer l'adresse IP du VPS** :
   - Se connecter à IONOS.
   - Aller dans **Server Cloud**.
   - Copier l'adresse IP du VPS.

2. **Associer le domaine au VPS** :
   - Aller dans **Domaine et SSL**.
   - Sélectionner le domaine souhaité.
   - Associer le domaine avec le VPS.
   - Aller dans **DNS**.
   - Modifier l'enregistrement `A @` pour remplacer l'adresse IP.
   - Enregistrer les modifications.

## 2. Connexion au serveur

1. **Se connecter au VPS via SSH** :
   - Ouvrir un terminal (PowerShell ou Putty).
   - Entrer la commande :
     ```sh
     ssh user@IP
     ```
   - Accepter la connexion (`yes`).
   - Entrer le mot de passe du serveur.

## 3. Installation d'Apache et configuration du serveur

1. **Mettre à jour le système** :
   ```sh
   apt update
   apt upgrade
   ```
2. **Installer Apache** :
   ```sh
   apt install apache2
   ```
3. **Vérifier l'installation** :
   - Ouvrir un navigateur et entrer l'adresse IP du serveur.
   - La page Apache par défaut doit s'afficher.

## 4. Déploiement des fichiers du site

1. **Déposer les fichiers sur le serveur avec FileZilla** :
   - Aller dans **SFTP**.
   - Récupérer :
     - Hôte (IP)
     - Utilisateur (`root`)
     - Mot de passe
   - Se connecter et transférer les fichiers.

2. **Organiser les fichiers sur le serveur** :
   ```sh
   cd /
   mkdir /root/nom-du-site
   cd /root/nom-du-site
   ```

## 5. Changement d'OS si nécessaire

1. **Vérifier l'OS installé** :
   - Aller dans **Lecteur CD**.
   - Vérifier si Debian est installé par défaut.
   - Si besoin, changer pour **Ubuntu 20.04 LTS**.
   - Aller dans **Actions > Suspendre**.
   - Réinitialiser avec l'image d'Ubuntu.

## 6. Installation de MySQL et configuration de la base de données

1. **Installer MySQL** :
   ```sh
   apt install mysql-server
   ```
2. **Créer une base de données** :
   ```sh
   mysql -u root -p
   CREATE DATABASE isaac_db;
   exit
   ```

## 7. Configuration Apache et activation HTTPS

1. **Créer un VirtualHost** :
   ```sh
   vim /etc/apache2/sites-available/nom-du-site.conf
   ```
   Ajouter le contenu suivant :
   ```apache
   <VirtualHost *:80>
       ServerName nom-du-site.fr
       ServerAlias www.nom-du-site.fr
       ServerAdmin webmaster@localhost
       DocumentRoot /root/nom-du-repertoire
       <Directory /root/nom-du-repertoire>
           AllowOverride All
       </Directory>
   </VirtualHost>
   ```
   - Sauvegarder et quitter (`Échap`, puis `:wq`).

2. **Activer le site et redémarrer Apache** :
   ```sh
   a2ensite nom-du-site.conf
   systemctl restart apache2
   ```

3. **Installer un certificat SSL avec Let's Encrypt** :
   ```sh
   apt install certbot python3-certbot-apache -y
   certbot --apache -d nom-du-site.fr -d www.nom-du-site.fr
   ```
   - Entrer une adresse email.
   - Renouveler le certificat tous les 3 mois.

## 8. Installation de PHP et Composer

1. **Installer PHP et ses extensions** :
   ```sh
   sudo apt install php php-cli php-mbstring php-xml php-curl php-zip php-intl php-bcmath unzip -y
   ```
2. **Installer Composer** :
   ```sh
   php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
   php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
   php composer-setup.php --install-dir=/usr/local/bin --filename=composer
   composer -v
   ```

---

## 🎯 Le serveur est maintenant prêt pour héberger un projet Symfony ! 🚀

