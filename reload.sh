php bin/console doctrine:database:drop -f
php bin/console doctrine:database:create
php bin/console doctrine:schema:update -f
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --append