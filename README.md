# 404 Clicker

-----------------

## Table des matières
1.[Environnement](#environnement)

2.[Installation du projet](#installation)

-----------------

## Environnement

***
Informations sur l'environnement du site


_php_ : 7.4.26

_symfony_ : 5.4.6

_mysql_ : 8.0.28

_adminer_ : 4.8.1

_apache_ : 2.4.38

_maildev_ : 1.1.0

-----------------

## Installation

***
Explication sur l'installation des containers de Docker par la récupération du .zip

Docker sf5 projet github : https://github.com/nicolasvauche/docker_sf5

Récupérez le et initialisez le en suivant le read.me

Emlpacement où mettre le site :
```
$ docker exec -it nom de l'image_www bash
$ cd app
```

Pour l'installation de git et du clonage du site :
```
$ git clone https://github.com/mick-us/404_Clicker .
```

Faire une copie du .env dans le dossier du site qui s'appellera env.local et modifier le fichier pour la base de données et utilisez bien l'adresse du mailer:

###> symfony/mailer ###
MAILER_DSN=smtp://docker_sf5_maildev:25
###< symfony/mailer ###

###> doctrine/doctrine-bundle ###
 # DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
DATABASE_URL="mysql://nomduuser:motdepasseduuser@docker_sf5_mysql:3306/nomdevotrebdd?serverVersion=5.7"
 # DATABASE_URL="postgresql://db_user:db_password@127.0.0.1:5432/db_name?serverVersion=13&charset=utf8"
###< doctrine/doctrine-bundle ###


Installer les dépendances sur le projet composer et node dans votre docker :
```
$ composer install
$ curl -sL https://deb.nodesource.com/setup_16.x | bash -
$ apt update && apt-get install -y nodejs
```

Pour voir la version de votre node :
```
$ node -v
```

Installation de yarn dans le projet pour l'utilisation de webpack encore :
```
$ curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
$ echo "deb https://dl.yarnpkg.com/debian/ stable main" | sudo tee /etc/apt/sources.list.d/yarn.list
$ apt install yarn
```

Pour voir la version de votre yarn :
```
$ yarn -v
```

Utiliser la commande si dessous pour actualiser le scss et le js sur le site :
```
$ yarn watch
```

Mise en place la base de données :
```
$ php bin/console doctrine:database:create
$ php bin/console doctrine:migrations:migrate
$ php bin/console doctrine:fixtures:load
```

Pour la documentation avec PHPDocumentor (on active cela avec son .phar) :
```
$ php phpDocumentor.phar -d ./src -t docs/api
```
Bravo vous avez fini l'installation de notre projet, maintenant faites vous plaisir avec ^^!
