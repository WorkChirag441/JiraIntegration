# 1. Clone the repo
git clone https://github.com/WorkChirag441/Jira-Interview-Tasks
cd Jira-Interview-Tasks
# 2. Install dependencies
composer install

# 3. Copy .env and set credentials
cp .env.backup .env

# 4. Generate app key
Change Jira Credentials in .env

# 5. Run database migrations
php artisan migrate

# 6. Add one user to test
php artisan db:seed --class=TestUserSeeder

# 7.1 (Optional) Install frontend assets if using Vite
npm install && npm run dev

# 7.2 (Optional) if Vite manifest not found! 
npm run build

# 8. Run server
php artisan serve

# 9. Login with following credentials:
email: ahujachirag441@gmail.com
password: 12345678
