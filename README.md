# Start app
```
composer install
cp .env.test .env
symfony server:start
```

# Auto refresh browser
```
npm install browser-sync --save-dev
./node_modules/.bin/browser-sync  --version

./node_modules/.bin/browser-sync start --config bs-config.js
```

# Commands for devleop
## Loading fixtures data
php bin/console doctrine:fixtures:load
## Update schema
php bin/console doctrine:schema:update --force
## Reload full database (ba careful)
```
php bin/console doctrine:database:drop -f
php bin/console doctrine:database:create
php bin/console doctrine:schema:update -f
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load --append
```

# To regenerate routes in twig
```
php bin/console fos:js-routing:dump
```