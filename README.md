# 1. Clone the repo
git clone https://github.com/WorkChirag441/Jira-Interview-Tasks
cd Jira-Interview-Tasks
# 2. Install dependencies
composer install

# 3. Copy .env and set credentials
cp .env.example .env

# 4. Generate app key
php artisan key:generate

# 5. Setup permissions (if needed)
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# 6. Run database migrations
php artisan migrate

# 7. Add one user to test
php artisan db:seed --class=TestUserSeeder

# 8.1 (Optional) Install frontend assets if using Vite
npm install && npm run dev
# 8.2 (Optional) if Vite manifest not found! 
npm run build
# 9. Run server
php artisan serve

# 10. Login with following credentials:
email: ahujachirag441@gmail.com
password: 12345678