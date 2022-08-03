# Steps To Install the project in Dev Branch

## Git clone and install the project

* Checkout to dev branch (connect to remote dev branch locally) : `git checkout dev`
* install all dependencies : `composer install`

## Create the database

* Create `.env.local` file in the project root directory
* Add `DATABASE_URL` variable with your own DB information : `DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=mariadb-10.3.25"`
* Create database with Doctrine : `bin/console doctrine:database:create`
* Execute database migrations : `bin/console doctrine:migrations:migrate` + `yes`
* Load data fixtures : `bin/console doctrine:fixtures:load --no-interaction`

## Lancer le serveur PHP

`php -S 0.0.0.0:8000 -t public`

