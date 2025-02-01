# Pour configurer MongoDB avec Symfony

1. **commence par installer MongoDB sur le PC si c'est pas déjà fait**
   - Tu l'installe en allant sur le site de mongoDB : https://www.mongodb.com/try/download/community

2. **Ensuite t'installe l'extension MongoDB pour PHP**
   - tu récuperes la bonne version ici : https://pecl.php.net/package/mongodb/1.20.1/windows
   - puis tu récuperes le fichier php_mongodb.dll et tu le place dans le dossier de xampp/php/ext
   - fait bien attention a pas te tromper de php. Si t'utilises bien xampp, ca veut dire que tu utilises le php de xampp et pas le php qu'on avaient installer localement sur le pc en cours.
   - une fois que t'as placer le dll, tu vas devoir le configurer dans le fichier php.ini de xampp. tu vas chercher dans le fichier php.ini de xampp le texte suivant : extension=php_mongodb.dll en supprimant le ; qui est devant. si y a pas la ligne tu la rajoutes à la fin de la section Dynamic Extensions. tu sauvegardes le fichier et tu relances le serveur web de xampp.

3. **ensuite tu configures Symfony pour utiliser MongoDB**
   - t'installes les dépendances nécessaires avec `composer require doctrine/mongodb-odm-bundle`.
   - et tu configure MongoDB dans le fichier `config/packages/doctrine_mongodb.yaml`. le mien est comme ca pour la partie de connection :doctrine_mongodb:
    auto_generate_proxy_classes: true
    auto_generate_hydrator_classes: true
    connections:
        default:
            server: '%env(resolve:MONGODB_URL)%'
            options: {}
    default_database: '%env(resolve:MONGODB_DB)%'
    document_managers:
        default:
            auto_mapping: true
            mappings:
                App:
                    dir: "%kernel.project_dir%/src/Document"
                    mapping: true
                    type: attribute
                    prefix: 'App\Document'
                    is_bundle: false
                    alias: App


fait gaffe à l'indentation.

4. **tu créer un document `ContactMessage` que tu places dans le dossier `src/Document`**
   - si le dossier `src/Document` n'existe pas tu le crées.
   - tu défini la classe `ContactMessage` avec les annotations ODM pour mapper les champs de la collection `contact`.
   - Faut s'assurer que les types de retour des méthodes sont corrects, notamment pour l'ID qui n'est pas en int sur mongodb mais en string (`?string`). j'avais fait l'erreur de mettre en int.

5. **tu créer un contrôleur `ContactController` qui va gérer les routes et les actions**
   - t'ajoute au moins une méthode `index` pour gérer l'affichage du formulaire de contact et l'envoi des messages.
   - apres tu peux ajouter d'autres routes pour pouvoir voir les messages enregistrer.

6. **tu créer une vue Twig pour l'index**
   - tu peux l'appeler `index.html.twig`.

7. **Tu démarres MongoDB**
   - avec la commande `net start MongoDB` ou `net start MongoDB` sur windows.

8. **tu vas sur la page de contact pour vérifier que tout fonctionne**
   - si t'as route est bien définié sur `/contact` tu peux aller sur la page de contact avec le lien `http://localhost:8000/contact`