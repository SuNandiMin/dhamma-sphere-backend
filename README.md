<<<<<<< HEAD
# dhamma-sphere-backend
=======
# Dhamma Sphere Backend

Laravel REST API for the Vue frontend in `C:\Users\User\Downloads\dhamma-sphere`.

## Structure

The backend is API-only and follows the same layered style as `conceptx-sms`:

- `app/Http/Controllers/Api/V1` thin API controllers
- `app/Http/Requests/Api/V1` validation requests
- `app/Http/Resources/Api/V1` response resources
- `app/Services` business logic
- `app/Traits/ResponseHelper.php` consistent JSON responses
- `app/helpers.php` shared helper functions
- `app/Models` relationship methods for every domain model

## Requirements

- PHP 8.2+ with the `pdo_mysql` extension
- Composer
- MySQL 8+ (or MariaDB)

## PHP on Ubuntu / WSL (FPM and extensions)

Install PHP-FPM, CLI, and common Laravel extensions (replace `8.2` with `8.3` etc. if your distro only ships a newer series):

```bash
sudo apt update
sudo apt install -y \
  php8.2-fpm \
  php8.2-cli \
  php8.2-common \
  php8.2-mysql \
  php8.2-xml \
  php8.2-mbstring \
  php8.2-curl \
  php8.2-zip \
  php8.2-bcmath \
  php8.2-intl \
  php8.2-readline \
  php8.2-opcache \
  composer
```

Start FPM (for Nginx/Apache; not used by `php artisan serve`):

```bash
sudo systemctl enable --now php8.2-fpm
```

If `php8.2-*` packages are missing, run `apt search php8.2-fpm` (or `php8.3-fpm`) and install the matching `php8.x-*` set your Ubuntu release provides.

## Setup

Create an empty database in MySQL whose name matches `DB_DATABASE` in `.env`, then:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan install:api --passport
php artisan migrate --seed
php artisan serve
```

Edit `.env` and set `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` for your MySQL server before running migrations.

The API runs at `http://localhost:8000/api`, which matches the frontend Axios config. Passport bearer tokens are returned as `token` so the current frontend can keep using `localStorage.getItem('token')`.

If Passport reports that a personal access client is missing when logging in, run:

```bash
php artisan passport:client --personal
```

## Demo Accounts

All seeded demo accounts use password `password`.

- `user@demo.com`
- `author@demo.com`
- `publisher@demo.com`

## Main Endpoints

Every endpoint is available under `/api/v1/...`. The current Vue-compatible aliases also remain available under `/api/...`.

- `POST /api/register`
- `POST /api/login`
- `GET /api/me`
- `POST /api/logout`
- `GET /api/doctrines`
- `GET /api/doctrines-options`
- `GET /api/doctrines/{id}`
- `POST /api/doctrines/{id}/translate`
- `POST /api/doctrines` author/publisher only
- `PUT /api/doctrines/{id}` author/publisher only
- `DELETE /api/doctrines/{id}` author/publisher only
- `GET /api/posts`
- `POST /api/posts`
- `PUT /api/posts/{id}`
- `DELETE /api/posts/{id}`
- `POST /api/posts/{id}/like`
- `POST /api/posts/{id}/share`
- `POST /api/posts/{id}/comments`
- `PUT /api/comments/{id}`
- `DELETE /api/comments/{id}`
- `GET /api/books`
- `GET /api/books/{id}`
- `POST /api/books` author/publisher only
- `PUT /api/books/{id}` author/publisher only
- `PATCH /api/books/{id}/stock` author/publisher only
- `DELETE /api/books/{id}` author/publisher only
- `GET /api/shop/profile` author/publisher only
- `PUT /api/shop/profile` author/publisher only
- `GET /api/shop/analytics` author/publisher only
- `GET /api/shop/orders` author/publisher only
- `GET /api/payment-methods`
- `POST /api/checkout`
- `GET /api/orders`

## Notes

- Register accepts `role` as `user`, `author`, or `publisher`.
- Author and publisher accounts can manage books and shop routes.
- Everyone with an account can post, comment, like, and share.
- Each account has exactly one role.
- Authors and publishers automatically get one shop profile.
- Payments are mocked but stored in the `payments` table, so Stripe can be added behind `OrderController::checkout`.
- Doctrine AI translation is mocked in `DoctrineController::translate`; replace that method with an OpenAI service when you are ready.
>>>>>>> d826eaa (Initial Commit: Dhamma Sphere Laravel Backend)
