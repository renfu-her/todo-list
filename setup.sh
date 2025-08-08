#!/bin/bash

# ToDo List Application Setup Script
# This script will set up the entire application automatically

echo "🚀 Starting ToDo List Application Setup..."
echo "=========================================="

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    echo "❌ PHP is not installed. Please install PHP 8.2 or higher."
    exit 1
fi

# Check if Composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer."
    exit 1
fi

echo "✅ PHP and Composer are installed"

# Install Composer dependencies
echo "📦 Installing Composer dependencies..."
composer install --no-interaction

if [ $? -ne 0 ]; then
    echo "❌ Failed to install Composer dependencies"
    exit 1
fi

echo "✅ Composer dependencies installed"

# Copy environment file
if [ ! -f .env ]; then
    echo "📝 Creating .env file..."
    cp .env.example .env
    echo "✅ .env file created"
else
    echo "ℹ️  .env file already exists"
fi

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Create SQLite database
echo "🗄️  Setting up database..."
touch database/database.sqlite

# Update .env for SQLite
sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env
sed -i 's/DB_DATABASE=.*/DB_DATABASE='$(pwd)'\/database\/database.sqlite/' .env

# Run migrations
echo "🔄 Running database migrations..."
php artisan migrate --force

if [ $? -ne 0 ]; then
    echo "❌ Failed to run migrations"
    exit 1
fi

echo "✅ Database migrations completed"

# Seed the database
echo "🌱 Seeding database with initial data..."
php artisan db:seed --force

if [ $? -ne 0 ]; then
    echo "❌ Failed to seed database"
    exit 1
fi

echo "✅ Database seeded"

# Install Filament
echo "🎛️  Installing Filament admin panel..."
php artisan filament:install --panels --no-interaction

if [ $? -ne 0 ]; then
    echo "❌ Failed to install Filament"
    exit 1
fi

echo "✅ Filament installed"

# Publish Filament config
echo "📋 Publishing Filament configuration..."
php artisan vendor:publish --tag=filament-config --force

# Install additional packages
echo "📦 Installing additional packages..."

# Install image processing
composer require intervention/image --no-interaction

# Install Flatpickr
composer require coolsam/flatpickr:^3.2 --no-interaction
php artisan vendor:publish --tag="coolsam-flatpickr-config" --force

# Install TinyMCE
composer require mohamedsabil83/filament-forms-tinyeditor --no-interaction
php artisan vendor:publish --tag="filament-forms-tinyeditor-config" --force

echo "✅ Additional packages installed"

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# Set permissions
echo "🔐 Setting file permissions..."
chmod -R 775 storage bootstrap/cache

echo "✅ File permissions set"

# Create admin user
echo "👤 Creating admin user..."
echo "Please follow the prompts to create your admin user:"
php artisan make:filament-user

echo ""
echo "🎉 Setup completed successfully!"
echo "=========================================="
echo ""
echo "📱 Your application is ready to use!"
echo ""
echo "🌐 Frontend: http://localhost:8000"
echo "🎛️  Admin Panel: http://localhost:8000/admin"
echo ""
echo "🚀 To start the development server, run:"
echo "   php artisan serve"
echo ""
echo "📚 For more information, check the README.md file"
echo ""
echo "Happy coding! 🎯"
