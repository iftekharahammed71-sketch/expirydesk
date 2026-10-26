# ExpiryDesk

ExpiryDesk is a white-label employee document expiry tracker for HR teams, PRO/government-services teams, staffing companies, construction firms, clinics, and HR consultancies.

## Phase 1 features

- Admin, HR, and Manager authentication with policy-based authorization.
- Multi-company and multi-branch data structure.
- Employee profiles, departments, nationality, job title, and employee status.
- System document types plus company-specific custom types.
- Private employee document uploads with validation and authorized downloads.
- Valid, Expiring Soon, and Expired status badges.
- Responsive Livewire dashboard with company, branch, department, type, and status filters.
- Configurable reminder lead times with duplicate-prevention logs.
- White-label company name, logo, brand colour, and sender settings.
- Seeded UAE/GCC-style demo data and demo credentials.

Phase 2+ extension points are intentionally not implemented yet. Export/history/activity-log work can be added without changing the document and reminder relationships.

## Requirements

- PHP 8.3 or newer with the required Laravel extensions.
- Composer 2.x.
- Node.js 20+ and npm, or an equivalent compatible JavaScript runtime.
- MySQL 8.0+ or MariaDB with Laravel-compatible InnoDB support.
- A web server that can point its document root to the `public` directory.

The application is built for Laravel 13, Blade, Livewire, and Tailwind CSS. Keep the framework and package versions from `composer.json` and `package.json` locked by the generated lock files in deployment.

## Local installation

Run these commands from the project root:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

On Windows PowerShell, use this instead of `cp`:

```powershell
Copy-Item .env.example .env
```

Create an empty MySQL database, then set these values in `.env`:

```dotenv
APP_NAME=ExpiryDesk
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000
APP_TIMEZONE=Asia/Dubai

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=expirydesk
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

Install frontend dependencies, migrate, seed, and build:

```bash
npm install
php artisan migrate --seed
npm run build
```

Start the local application:

```bash
php artisan serve
```

Open `http://127.0.0.1:8000`.

## Demo login

The seeded login details are also shown on the login page. They are controlled by `.env` and default to:

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@expirydesk.test` | `password` |
| HR | `hr@expirydesk.test` | `password` |
| Manager | `manager@expirydesk.test` | `password` |

Change `DEMO_PASSWORD` before using seeded data outside a local/demo environment. The `.test` addresses are placeholders and are not delivery addresses.

## Mail setup

For local development, the default `MAIL_MAILER=log` writes messages to the Laravel log. To use SMTP, set the following in `.env`:

```dotenv
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.test
MAIL_PORT=587
MAIL_USERNAME=your_smtp_username
MAIL_PASSWORD=your_smtp_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=notifications@example.test
MAIL_FROM_NAME="ExpiryDesk"
```

The administrator can also configure per-company sender details under **Companies → White-label settings**. Reminder notifications use the configured company sender when mail is sent.

## Scheduled reminders

Reminder lead times are stored per company. New companies receive 90, 60, 30, and 7-day defaults. The command is:

```bash
php artisan documents:send-expiry-reminders
```

The command only sends a lead-time reminder when the document expiry date matches that threshold and a successful reminder log does not already exist for the document/threshold pair.

### VPS cron

Laravel's scheduler should be called every minute. Replace `/var/www/expirydesk` with the real release path and use the PHP binary for the deployed runtime:

```cron
* * * * * cd /var/www/expirydesk && php artisan schedule:run >> /dev/null 2>&1
```

The schedule runs the reminder command daily at 01:00 in the application timezone. Set `APP_TIMEZONE` to the business timezone you want the scheduler to use.

### Shared hosting

Create a cron job in the hosting control panel with the same command. If the host does not allow `cd`, use absolute paths:

```cron
* * * * * /usr/local/bin/php /home/account/expirydesk/artisan schedule:run >> /dev/null 2>&1
```

Confirm the host PHP version is compatible and that the cron user can write to `storage` and `bootstrap/cache`.

## Production deployment

1. Upload the repository outside the public web root when possible.
2. Point the web server document root to `public/`, never the repository root.
3. Run `composer install --no-dev --optimize-autoloader`.
4. Create a production `.env` with `APP_ENV=production` and `APP_DEBUG=false`.
5. Run `php artisan key:generate` only for a new installation; preserve an existing `APP_KEY` during upgrades.
6. Configure the production MySQL database and SMTP mail settings.
7. Run `php artisan migrate --force`.
8. Run `npm ci && npm run build` during the release build, or deploy the generated `public/build` directory.
9. Create the first installation administrator with `php artisan expirydesk:create-admin`.
10. Run `php artisan optimize`.
11. Ensure `storage/` and `bootstrap/cache/` are writable by the web/CLI user.
12. Add the scheduler cron entry and test the reminder command manually.
13. If a queue worker is introduced in a later phase, supervise it separately; Phase 1 reminder sending is performed by the scheduled command.

## Security notes

- Employee files use the private local filesystem disk and are not exposed through `storage:link`.
- Downloads pass through an authenticated controller and the employee/document policies.
- Uploads accept only PDF, JPG, JPEG, PNG, and WEBP files up to 10 MB.
- Laravel validation, escaped Blade output, CSRF middleware, Eloquent bindings, password hashing, and database constraints are used throughout.
- Do not commit `.env`, real mail credentials, uploaded documents, or production keys.
- Keep `APP_DEBUG=false` in production.

## Checks

Run the focused tests and formatting check:

```bash
php artisan test
composer lint
npm run build
```

The test suite covers authentication, inactive-account blocking, manager branch isolation, and document status boundaries.

## Useful commands

```bash
php artisan route:list
php artisan about
php artisan migrate:fresh --seed
php artisan documents:send-expiry-reminders
```

`migrate:fresh --seed` destroys existing database data and is intended only for local/demo resets.
