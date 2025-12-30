# Recruivo — Job Board & Candidate Management

Recruivo is a full‑featured job board and candidate management platform built with Laravel 12. It includes role‑based access (Admin, Recruiter, Candidate), email verification, job postings, applications, and an admin area.

Application runs locally or via Docker (PHP 8.2 + Apache, MySQL 8, Redis 7). This README covers features, setup, Docker deployment, demo accounts, and common commands.

## Features

- Role-based access with `spatie/laravel-permission` (Admin, Recruiter, Candidate)
- Recruiters: create, publish/unpublish, and manage jobs; review applications, download resumes
- Candidates: browse/search jobs, apply, manage profile and resume
- Admin: basic user management dashboard
- Email verification flow
- Modern asset pipeline with Vite and Tailwind CSS

## Tech Stack

- PHP 8.2, Laravel 12
- MySQL 8, Redis 7
- Node.js 20, Vite, Tailwind
- Packages: Sanctum, Spatie Permission, Translatable

---

## Quick Start (Local Development)

Prerequisites: PHP 8.2+, Composer, Node.js 18/20+, MySQL 8, Redis (optional)

1) Install dependencies
```bash
composer install
npm install
```

2) Configure environment
```bash
cp .env.example .env
php artisan key:generate
```
Update `.env` with your local DB credentials. For email verification to work, configure your SMTP credentials:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@recruivo.work
MAIL_FROM_NAME="${APP_NAME}"
```
If you plan to run the separate frontend, also set `FRONTEND_URL` (defaults to `http://localhost:3000`).

3) Database and storage
```bash
php artisan migrate --seed
php artisan storage:link
```

4) Run the app
```bash
php artisan serve
```
Vite (choose one):
```bash
npm run dev
# or
npm run build
```

Open: `http://localhost:8000/`.

---

## Run with Docker (Recommended)

The easiest way to get Recruivo running. A single command handles environment setup, container building, and database initialization.

### Quick Start

```bash
./deploy/docker-start.sh --fresh
```

This automatically:
- Creates `.env` from `.env.docker.example` (if missing)
- Generates `APP_KEY` (if empty)
- Builds the multi-stage Docker image
- Waits for MySQL and Redis health checks
- Runs database migrations and seeders

Application available at: **http://localhost:8000**

### CLI Options

| Option | Description |
|--------|-------------|
| `--fresh` | Fresh install with migrations and seeders |
| `--seed` | Run database seeders |
| `--no-migrate` | Skip database migrations |
| `--build-only` | Build containers only, skip setup |
| `--down` | Stop and remove containers |
| `--logs` | View container logs |
| `--help` | Show help message |

### Architecture

| Container | Description |
|-----------|-------------|
| `recruivo` | PHP 8.2 + Apache (Laravel app with pre-built Vite assets) |
| `recruivo_mysql` | MySQL 8.0 |
| `recruivo_redis` | Redis 7 (cache, sessions, queues) |

### Common Operations

```bash
# Shell access
docker compose exec laravel bash

# Artisan commands
docker compose exec laravel php artisan tinker

# View logs
docker compose logs -f

# Rebuild after code changes
docker compose up -d --build

# Full reset (wipe data)
docker compose down -v && ./deploy/docker-start.sh --fresh
```

### Production

For production deployments, set these in `.env`:

```env
APP_ENV=production
APP_DEBUG=false
INSTALL_DEV_DEPS=false
```

Then rebuild: `docker compose up -d --build`

---

## Demo Accounts (Seeded)

Use these to explore the app:

- Admin: `admin@recruivo.work` / `password`
- Recruiter: `recruiter@recruivo.work` / `password`
- Candidate: `candidate@recruivo.work` / `password`

---

## Testing

```bash
php artisan test
```

---

## License

This project is licensed under the GNU General Public License v3.0 (GPL-3.0). See the [LICENSE](LICENSE) file for details.