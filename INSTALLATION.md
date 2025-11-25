# Online Shop - Installation Guide

## Requirements

- PHP 8.3+ (Laravel 11 requirement)
- Composer
- Node.js 18+
- MySQL 8.0+ or PostgreSQL
- Docker (optional, for Laravel Sail)

## Installation Steps

### 1. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 2. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3. Database Setup

Update `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=online_shop
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Run Migrations and Seeders

```bash
# Run migrations
php artisan migrate

# Seed database with test data
php artisan db:seed
```

This will create:
- 3 users (1 admin, 2 regular users)
- 5 product categories
- 15 products

**Test Credentials:**
- Admin: `admin@example.com` / `password`
- User 1: `john@example.com` / `password`
- User 2: `jane@example.com` / `password`

### 5. Install Laravel Sanctum (API Authentication)

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 6. Start Development Servers

In separate terminal windows:

```bash
# Start Laravel development server
php artisan serve

# Start Vite development server (for frontend)
npm run dev
```

Application will be available at: `http://localhost:8000`

## Using Docker (Laravel Sail)

If you prefer using Docker:

```bash
# Start containers
./vendor/bin/sail up -d

# Run migrations
./vendor/bin/sail artisan migrate

# Seed database
./vendor/bin/sail artisan db:seed

# Install npm dependencies
./vendor/bin/sail npm install

# Start Vite
./vendor/bin/sail npm run dev
```

## Queue Worker

To process background jobs (order notifications):

```bash
# Start queue worker
php artisan queue:work

# Or with Sail
./vendor/bin/sail artisan queue:work
```

## Features

### Customer Features
- User registration and authentication
- Browse products with search and category filter
- Product detail pages
- Shopping cart with quantity management
- Order placement
- View order history

### Admin Features
- Product management (CRUD operations)
- Order management (view and update status)
- Admin dashboard

## API Endpoints

All API routes are prefixed with `/api`:

### Public
- `POST /api/register` - Register new user
- `POST /api/login` - Login
- `GET /api/products` - List products (with search and filter)
- `GET /api/products/{id}` - Get product details
- `GET /api/categories` - List categories

### Protected (requires authentication)
- `POST /api/logout` - Logout
- `GET /api/user` - Get authenticated user
- `POST /api/orders` - Create order
- `GET /api/orders` - List user orders (admin sees all)
- `GET /api/orders/{id}` - Get order details
- `PATCH /api/orders/{id}/status` - Update order status (admin only)

### Admin Only
- `POST /api/products` - Create product
- `PUT /api/products/{id}` - Update product
- `DELETE /api/products/{id}` - Delete product

## Technology Stack

### Backend
- Laravel 11
- PHP 8.3+
- MySQL/PostgreSQL
- Laravel Sanctum (API authentication)
- Laravel Jobs/Queues

### Frontend
- Vue.js 3 (Composition API)
- Vue Router 4
- Axios
- Tailwind CSS
- Vite

## Architecture

### Backend
- **Controllers**: Handle HTTP requests (API controllers in `app/Http/Controllers/Api`)
- **Services**: Business logic layer (`app/Services`)
- **Models**: Eloquent models with relationships (`app/Models`)
- **Requests**: Form validation (`app/Http/Requests`)
- **Jobs**: Queue jobs for async tasks (`app/Jobs`)
- **Migrations**: Database schema (`database/migrations`)
- **Seeders**: Test data (`database/seeders`)

### Frontend
- **Components**: Reusable Vue components (`resources/js/components`)
- **Pages**: Route-level components (`resources/js/pages`)
- **Composables**: Shared logic (auth, cart) (`resources/js/composables`)
- **Services**: API communication (`resources/js/services`)
- **Router**: Navigation (`resources/js/router`)

## Troubleshooting

### PHP Version Issues
If you encounter PHP version errors, ensure you're using PHP 8.3+:
```bash
php -v
```

### Permission Issues
```bash
chmod -R 775 storage bootstrap/cache
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Build for Production
```bash
npm run build
php artisan config:cache
php artisan route:cache
```
