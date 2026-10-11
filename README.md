# SmartBiz Hub — One Optics Clinic

Laravel optical-shop prototype using XAMPP Apache and MariaDB (mysql driver).

See [XAMPP setup](XAMPP-SETUP.txt) for installation, database creation, Apache configuration and demo accounts. PHP 8.2+ and Composer are required. Run `composer install --no-dev` for this source copy.

Start Apache and MySQL in XAMPP, then open http://127.0.0.1:8081.

See SCOPE-AND-LIMITATIONS.txt and ERD-ENTITY-MAP.txt for the scope and 11 entities. Payments and delivery updates are entered by staff. No live gateway, real-time tracking or production automation is included.

Local .env, live databases, uploads, vendor dependencies and runtime files are excluded. Keep migrations and seeders to recreate a fresh demo database.

## Deploying to Vercel

The frontend (Blade views) and backend (Laravel) deploy together as one serverless PHP app using `vercel.json`, `api/index.php` and the `vercel-php` runtime. Vercel does not host MySQL, so an external MySQL/MariaDB database is required (e.g. Aiven, TiDB Cloud, Railway).

1. On vercel.com, **Add New → Project** and import this GitHub repository. Leave Framework Preset as *Other*.
2. In **Settings → Environment Variables** add:

   | Name | Value |
   |------|-------|
   | `APP_KEY` | output of `php artisan key:generate --show` |
   | `APP_URL` | your Vercel URL, e.g. `https://smart-biz-app-dev.vercel.app` |
   | `DB_CONNECTION` | `mysql` |
   | `DB_HOST` / `DB_PORT` / `DB_DATABASE` / `DB_USERNAME` / `DB_PASSWORD` | from your database provider |
   | `MYSQL_ATTR_SSL_CA` | `/etc/pki/tls/certs/ca-bundle.crt` if the provider requires TLS (Aiven, TiDB) |

3. Create the tables and demo data from your computer using the same credentials:
   `php artisan migrate --seed --force` (put the DB_* values in a local `.env` first, or prefix them on the command line).
4. Deploy. Every push to `main` redeploys automatically.

Limitations on Vercel: the filesystem is read-only, so **Settings → Clinic configuration** cannot be saved and **product image uploads** will fail. Sessions use cookies and cache is in-memory per request. Everything else (login, inventory, orders, billing, payments) works against the external database.
