@echo off
REM ToDo List Application Setup Script for Windows
REM This script will set up the entire application automatically

echo 🚀 Starting ToDo List Application Setup...
echo ==========================================

REM Check if PHP is installed
php --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ PHP is not installed. Please install PHP 8.2 or higher.
    pause
    exit /b 1
)

REM Check if Composer is installed
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Composer is not installed. Please install Composer.
    pause
    exit /b 1
)

echo ✅ PHP and Composer are installed

REM Install Composer dependencies
echo 📦 Installing Composer dependencies...
composer install --no-interaction

if %errorlevel% neq 0 (
    echo ❌ Failed to install Composer dependencies
    pause
    exit /b 1
)

echo ✅ Composer dependencies installed

REM Copy environment file
if not exist .env (
    echo 📝 Creating .env file...
    copy .env.example .env
    echo ✅ .env file created
) else (
    echo ℹ️  .env file already exists
)

REM Generate application key
echo 🔑 Generating application key...
php artisan key:generate

REM Create SQLite database
echo 🗄️  Setting up database...
type nul > database\database.sqlite

REM Update .env for SQLite (Windows version)
powershell -Command "(Get-Content .env) -replace 'DB_CONNECTION=mysql', 'DB_CONNECTION=sqlite' | Set-Content .env"
powershell -Command "(Get-Content .env) -replace 'DB_DATABASE=.*', 'DB_DATABASE=%cd%\database\database.sqlite' | Set-Content .env"

REM Run migrations
echo 🔄 Running database migrations...
php artisan migrate --force

if %errorlevel% neq 0 (
    echo ❌ Failed to run migrations
    pause
    exit /b 1
)

echo ✅ Database migrations completed

REM Seed the database
echo 🌱 Seeding database with initial data...
php artisan db:seed --force

if %errorlevel% neq 0 (
    echo ❌ Failed to seed database
    pause
    exit /b 1
)

echo ✅ Database seeded

REM Install Filament
echo 🎛️  Installing Filament admin panel...
php artisan filament:install --panels --no-interaction

if %errorlevel% neq 0 (
    echo ❌ Failed to install Filament
    pause
    exit /b 1
)

echo ✅ Filament installed

REM Publish Filament config
echo 📋 Publishing Filament configuration...
php artisan vendor:publish --tag=filament-config --force

REM Install additional packages
echo 📦 Installing additional packages...

REM Install image processing
composer require intervention/image --no-interaction

REM Install Flatpickr
composer require coolsam/flatpickr:^3.2 --no-interaction
php artisan vendor:publish --tag="coolsam-flatpickr-config" --force

REM Install TinyMCE
composer require mohamedsabil83/filament-forms-tinyeditor --no-interaction
php artisan vendor:publish --tag="filament-forms-tinyeditor-config" --force

echo ✅ Additional packages installed

REM Create storage link
echo 🔗 Creating storage link...
php artisan storage:link

echo ✅ File permissions set

REM Create admin user
echo 👤 Creating admin user...
echo Please follow the prompts to create your admin user:
php artisan make:filament-user

echo.
echo 🎉 Setup completed successfully!
echo ==========================================
echo.
echo 📱 Your application is ready to use!
echo.
echo 🌐 Frontend: http://localhost:8000
echo 🎛️  Admin Panel: http://localhost:8000/admin
echo.
echo 🚀 To start the development server, run:
echo    php artisan serve
echo.
echo 📚 For more information, check the README.md file
echo.
echo Happy coding! 🎯
pause
