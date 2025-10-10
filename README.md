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

## Run with Docker (Recommended for a quick demo)

There are two options.

### Option A — Helper script
Requires a POSIX shell (macOS/Linux or Git Bash on Windows).
```bash
cp .env.docker.example .env
# Important when using Docker:
#   DB_HOST=mysql

bash deploy/docker-start.sh
```
This will build containers, install Composer/NPM deps, generate app key, run migrations with seeders, and build assets. When done:
- App: `http://localhost:8000`

Useful Docker commands:
```bash
docker compose logs -f
docker compose exec laravel php artisan tinker
docker compose down
```

Environment notes for Docker:
- Set `APP_URL=http://localhost:8000`
- Set `DB_HOST=mysql`

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