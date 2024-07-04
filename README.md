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