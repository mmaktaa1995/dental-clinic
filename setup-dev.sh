#!/bin/bash

# Exit on error
set -e

echo "🚀 Setting up development environment..."

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

# Install NPM dependencies
echo "📦 Installing Node.js dependencies..."
npm install

# Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# Create storage link
echo "🔗 Creating storage link..."
php artisan storage:link

# Run database migrations
echo "💾 Running database migrations..."
php artisan migrate --force

# Seed the database
echo "🌱 Seeding database..."
php artisan db:seed --force

# Clear all caches
echo "🧹 Clearing caches..."
php artisan optimize:clear

# Install pre-commit hook
echo "🔧 Installing pre-commit hook..."
chmod +x .git/hooks/pre-commit

echo "✨ Development environment setup complete!"
echo "You can now start the development server with: php artisan serve"
