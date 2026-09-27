---
name: laundry-runtime-testing
description: Start the Laravel laundry app locally and test API bearer auth and web-session login against a compatible database.
---

# Laundry runtime testing

Run project commands from `app-laundry` under the repository root.

## Runtime
- Check composer.json and composer.lock PHP requirements. PHP 8.4 works with the checked-in Laravel 13 dependencies.
- On Ubuntu 22.04, install from `ppa:ondrej/php`: `php8.4-cli php8.4-sqlite3 php8.4-mysql php8.4-mbstring php8.4-xml php8.4-curl php8.4-zip php8.4-gd`, plus Composer and MariaDB server.
- Run `composer install --no-interaction --prefer-dist`. Ubuntu's older Composer may emit deprecation notices on PHP 8.4 even when installation succeeds; prefer a current compatible Composer for quieter setup.
- Copy `.env.example` to `.env` only when absent, then run `php artisan key:generate` for a new local environment.

## Database
- Existing migrations may contain MySQL-specific ALTER/MODIFY/ENUM statements. If SQLite migrations fail there, use a dedicated local MySQL/MariaDB database rather than modifying migrations for the test.
- Start MariaDB (`sudo -n service mariadb start` when passwordless sudo is available); create a dedicated database/user and grant privileges only on that database.
- Configure ignored `.env` with `DB_CONNECTION=mysql`, host 127.0.0.1, port 3306, and local DB name/user/password.
- Run `php artisan migrate --seed`; do not reset a shared database.
- Seeded local users are admin@example.com, kasir@example.com and operator@example.com, all with password `password`. Never assume these are production credentials.

## Start and test
- `php artisan serve --host=0.0.0.0 --port=8000`; readiness endpoint `/up`.
- Login/dashboard views use CDN assets; they do not require a Vite build.
- API requests use `Accept: application/json`, `Content-Type: application/json`.
- POST `/api/register` requires name, unique email, password, matching password_confirmation and role kasir/operator/admin. POST `/api/login` returns a bearer token.
- POST `/api/logout` uses `Authorization: Bearer <token>`. Test acceptance before revocation and rejection afterward. Do not use browser cookies for API testing.
- A subsequent login replaces the previous token. Tokens in saved evidence should be revoked before sharing.
- Regression browser path: navigate `/`, expect redirect `/login`; fill Email and Password, click Masuk; expect `/` with Dashboard and Administrator. Record only the browser flow, not shell HTTP requests.

## Devin Secrets Needed
None for a dedicated local seeded database. Do not use production credentials for local testing.
