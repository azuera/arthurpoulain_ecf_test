installation env

Pense à lancer les premières commandes habituelles après un clone, à savoir :
composer install

Tu peux ensuite créer ton fichier .env.local en renseignant tes identifiants de BDD pour MYSQL. Et si tu souhaites voir ce que donne le projet dans l'état actuel, effectue la série :
php bin/console d:d:c
php bin/console d:m:m
php bin/console d:f:l
symfony server:start

mise en place environnement de test

Comme cela est expliqué sur la documentation de Symfony, crée un fichier .env.test.local à partir du fichier .env.test et ajoutes-y la ligne suivante en prenant soin de modifier db_user, db_password et db_name par les valeurs appropriées.

DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=8.0"
Tu peux ensuite effectuer les commandes suivantes pour terminer la configuration :

php bin/console --env=test doctrine:database:create
php bin/console --env=test doctrine:migration:migrate
php bin/console --env=test doctrine:fixtures:load

lancer la commande pour lancer les test php bin/phpunit   
