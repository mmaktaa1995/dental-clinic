# Dental Clinic Management System

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel">
  
  <p align="center">
    <a href="https://packagist.org/packages/laravel/framework">
      <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Laravel Version">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
      <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
    </a>
    <a href="https://github.com/psf/black">
      <img alt="Code style: PSR-12" src="https://img.shields.io/badge/code%20style-PSR--12-1f425f.svg">
    </a>
  </p>
</p>

A comprehensive dental clinic management system built with Laravel 10, featuring patient management, appointment scheduling, treatment tracking, and more.

## Features

- **Patient Management**: Comprehensive patient records and history
- **Appointment Scheduling**: Easy booking and management of appointments
- **Treatment Planning**: Create and track treatment plans for patients
- **Billing & Invoicing**: Generate and manage invoices and payments
- **Role-Based Access Control**: Secure access control with fine-grained permissions
- **API-First Design**: RESTful API for all functionality
- **Modern Frontend**: Built with Vue.js and Inertia.js
- **Responsive Design**: Works on all devices

## Development Setup

### Prerequisites

- PHP 8.1+
- Composer
- Node.js 16+
- MySQL 8.0+

### Installation

1. Clone the repository:
   ```bash
   git clone [repository-url]
   cd dental-clinic
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Install JavaScript dependencies:
   ```bash
   npm install
   ```

4. Copy the environment file:
   ```bash
   cp .env.example .env
   ```

5. Generate application key:
   ```bash
   php artisan key:generate
   ```

6. Configure your `.env` file with database credentials and other settings.

7. Run migrations and seed the database:
   ```bash
   php artisan migrate --seed
   ```

8. Start the development server:
   ```bash
   php artisan serve
   ```

9. In a new terminal, start Vite for frontend assets:
   ```bash
   npm run dev
   ```

## Code Quality

This project follows PSR-12 coding standards and includes several tools to maintain code quality:

### PHP_CodeSniffer

Check for coding standard violations:
```bash
composer check-style
```

Automatically fix fixable issues:
```bash
composer fix-style
```

### PHP-CS-Fixer

Automatically fix coding style issues:
```bash
./vendor/bin/php-cs-fixer fix
```

### PHPStan

Run static analysis:
```bash
composer analyse
```

### Pre-commit Hook

A pre-commit hook is included to automatically check code style before each commit. It will:
1. Run PHP-CS-Fixer to fix style issues
2. Run PHP_CodeSniffer to check for remaining issues
3. Prevent the commit if there are unfixable issues

## Testing

Run the test suite:

```bash
php artisan test
```

## Security Vulnerabilities

Please review [our security policy](SECURITY.md) on how to report security vulnerabilities.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Development Scripts

### Setup Development Environment

Run the setup script to prepare your development environment:

```bash
./setup-dev.sh
```

This will:
1. Install PHP and Node.js dependencies
2. Generate application key
3. Create storage link
4. Run database migrations
5. Seed the database
6. Clear caches
7. Install pre-commit hook

### Code Style Fixes

#### Fix Test Method Names

To fix test method names to follow camelCase convention:

```bash
./update-test-methods.sh
```

#### Fix Line Lengths

To fix line length issues in test files:

```bash
php fix-test-line-lengths.php
```

## Contributing

Thank you for considering contributing to the project! Please read the [contribution guidelines](CONTRIBUTING.md) before submitting pull requests.

## Learning Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Laracasts](https://laracasts.com)
- [Laravel News](https://laravel-news.com)
- [Vue.js Documentation](https://vuejs.org/guide/)
- [Inertia.js Documentation](https://inertiajs.com/)

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

#### remember to change line 9094 in Mpdf.php to $c = explode("\xbb\xa4\xac", $t, 3);
