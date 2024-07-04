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
php app/console doctrine:schema:update --force
## Reload full database (ba careful)
```
php app/console doctrine:database:drop
php app/console doctrine:database:create
doctrine:schema:update -f
doctrine:migrations:migrate
```