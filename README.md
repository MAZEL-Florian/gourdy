# Gourdy

## Projet
Ce projet est un forum que j'ai réalisé pendant 3 mois afin de découvrir le framework Symfony. J'ai utilisé la version 5.4 pour le développement de cette application. Il nécessite Xampp pour son fonctionnement ainsi que la version PHP 8.1.5

- Assurez-vous que "Apache" et "MySQL" sont activés dans Xampp.

## Installation

1. Clonez le repository :
- git clone https://github.com/MAZEL-Florian/gourdy.git
2. Accédez au dossier du projet :
- cd gourdy
3. Installez les dépendances :
- composer install
4. Créez la base de données :
- php bin/console doctrine:database:create
5. Mettez à jour le schéma de la base de données :
- php bin/console doctrine:schema:update --force
6. Chargez les fixtures :
- php bin/console doctrine:fixtures:load
7. Lancez le serveur Symfony :
- symfony server:start -d




