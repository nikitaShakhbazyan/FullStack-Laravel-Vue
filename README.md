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

## Архитектурные решения

### Backend Architecture
В проекте я вынес всю бизнес-логику в сервисный слой. Контроллеры ничем «тяжёлым» не занимаются- они просто принимают запрос, вызывают нужный сервис и возвращают ответ. Вся настоящая работа происходит в сервисах, так код чище и легче поддерживать.

Создание заказа построено на событийной архитектуре. Когда создаётся заказ, автоматически триггерится событие OrderCreated. Его слушатель отправляет задачу в очередь, например для отправки уведомлений. Это позволяет обрабатывать такие вещи асинхронно и не тормозить пользователя.

Чтобы не ловить N+1 запросы, я везде использую with() и заранее подгружаю связанные сущности. Например, заказы сразу приходят вместе с товарами и пользователем -без десятков лишних запросов к базе.

Для защиты от «шумных» клиентов добавлен Rate Limit:
- публичные маршруты — 60 запросов в минуту,
- авторизованные — 120,
- админские — 30.

Ещё одна важная вещь -транзакции. Создание заказа обёрнуто в DB::transaction, чтобы всё выполнялось атомарно: либо весь заказ создаётся успешно, либо ничего не записывается, если что-то пошло не так.

### Frontend Architecture
На фронте я использую Composition API. Общие вещи вроде авторизации или корзины вынесены в отдельные composables- useAuth, useCart. Они держат всё состояние внутри себя, и их можно подключать в любых компонентах, что делает код намного чище.

Корзина хранится в localStorage, поэтому после перезагрузки страницы товары никуда не пропадают.

Для общения с сервером есть отдельный API-слой. В нём стоят перехватчики, которые автоматически добавляют токен к запросам и ловят 401 ошибки например, чтобы разлогинить пользователя или обновить токен

Детали: [INSTALLATION.md](INSTALLATION.md)

## Дополнительные возможности
- ✅ **Events & Listeners** - событийная архитектура для уведомлений
- ✅ **Rate Limiting** - защита API (60/120/30 req/min)
- ✅ **N+1 Optimization** - eager loading везде где есть relationships
- ✅ **Docker Compose** - готовая конфигурация для MySQL, Redis
