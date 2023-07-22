# Tersea test setup
```
cd backend
cp .env.example .env
composer install
php artisan migrate --force --seed
cd ../frontend
npm i
cd ..
npm i
npm run dev