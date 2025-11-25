# Online Shop - Laravel 11 + Vue.js 3

E-commerce приложение: Laravel 11 (backend) + Vue.js 3 (frontend)

## Stack
**Backend:** Laravel 11, MySQL, Sanctum, Jobs/Queues, Services layer
**Frontend:** Vue 3 (Composition API), Vue Router, Axios, Tailwind CSS

## Запуск

```bash
# 1. Установка зависимостей
composer install
npm install

# 2. Настройка окружения
cp .env.example .env
php artisan key:generate

# 3. Настройка БД в .env
DB_CONNECTION=mysql
DB_DATABASE=online_shop
DB_USERNAME=root
DB_PASSWORD=

# 4. Миграции и сиды
php artisan migrate
php artisan db:seed

# 5. Установка Sanctum (важно!)
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate

# 6. Запуск серверов (в двух терминалах)
php artisan serve     # http://localhost:8000
npm run dev
```

## Тестовые аккаунты
- **Admin:** admin@example.com / password
- **User:** john@example.com / password

## Функционал

**Покупатели:** регистрация, каталог с поиском/фильтрами, корзина, заказы
**Админ:** CRUD товаров, управление заказами, изменение статусов

## API
- `POST /api/register|login|logout`
- `GET /api/products` (поиск, фильтр по категории)
- `GET /api/products/{id}`, `GET /api/categories`
- `POST /api/orders`, `GET /api/orders`
- Admin: `POST|PUT|DELETE /api/products`, `PATCH /api/orders/{id}/status`

## Структура

**Backend:** Controllers → Services → Models
**Frontend:** 7 reusable компонентов, 10 страниц, composables (useAuth, useCart)

Детали: [INSTALLATION.md](INSTALLATION.md)
