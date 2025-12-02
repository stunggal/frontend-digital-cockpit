# Frontend Digital Cockpit

A modern front-end for the Digital Cockpit — a lightweight, Laravel-based UI that
connects to backend APIs to show patient vitals, schedules, and health recommendations.

**Subtitle:** A hospital-focused dashboard and patient management frontend.

**Slogan:** Empower clinicians with actionable patient data, fast.

---
<!-- LAST_COMMIT_START -->
**Last commit:** `f2ad882e` - commit: Deployment: Auto-push 2025-12-02 16:07:18 (auto-filled)
<!-- LAST_COMMIT_END -->

**Primary languages:** PHP (Laravel), Blade templates, JavaScript, CSS, JSON

**How many languages used (approx):** 5

**Languages used in this repository:**

-   PHP (Laravel controllers, models, helpers)
-   Blade (Laravel view templates)
-   JavaScript (frontend interactivity, AJAX)
-   CSS (stylesheets and UI frameworks)
-   JSON (config, package manifests)

---

## Table of Contents

-   [Overview](#overview)
-   [Getting Started](#getting-started)
    -   [Prerequisites](#prerequisites)
    -   [Installation](#installation)
-   [Usage](#usage)
-   [Testing](#testing)
-   [Folder structure highlights](#folder-structure-highlights)
-   [Configuration](#configuration)
-   [Contributing](#contributing)
-   [License](#license)

---

## Overview

This repository holds the frontend for Digital Cockpit (hospital dashboard).
It is built on top of Laravel and uses Blade templates for server-rendered pages
with JavaScript for dynamic behavior (AJAX requests, realtime widgets).

The frontend expects an API backend (configured through environment variables
such as `API_URL`) that provides authentication and patient-related endpoints
(heart rate, blood pressure, SpO2, LLM-based food recommendations, schedules).

## Getting Started

Follow these steps to run the frontend locally for development.

### Prerequisites

-   PHP 8.x (matching the project's composer.json requirement)
-   Composer (for PHP dependencies)
-   Node.js and npm (for building frontend assets with Vite)
-   A configured backend API reachable from your environment (set `API_URL` in `.env`)

### Installation

1. Clone the repository:

```bash
git clone <repo-url> && cd digital-cockpit
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install frontend dependencies and build assets (development):

```bash
npm install
npm run dev
```

4. Copy and edit `.env` file — you can copy the example and set your API URL and
   app keys:

```bash
cp .env.example .env
php artisan key:generate
# Edit .env -> set API_URL, DB config (if used), and other env settings
```

5. (Optional) Run migrations/seeding if you use local DB for sessions or caching.

```bash
php artisan migrate
php artisan db:seed
```

6. Serve the app locally:

```bash
php artisan serve
```

Navigate to `http://127.0.0.1:8000` in your browser.

## Usage

-   Login at `/login` (the app delegates authentication to the configured `API_URL`).
-   Dashboard widgets request patient vitals via AJAX routes (e.g. `/get-heart-rate`).
-   The `HomeController` provides the main dashboard view; `PasienController` and
    `DokterController` provide patient and doctor-related views and AJAX endpoints.

Developer tips:

-   Replace hardcoded/demo data in views (many templates use `rand()` or sample values)
    with real backend data.
-   Keep your API token in a secure store; the app currently stores the token in
    session under `api_token` (see `AuthController`).

## Testing

-   There are basic PHPUnit/Pest tests in the `tests/` directory. To run tests:

```bash
./vendor/bin/phpunit
# or if you use Pest:
./vendor/bin/pest
```

## Folder structure highlights

-   `app/Http/Controllers` — controllers for pages and API proxy endpoints
-   `resources/views` — Blade templates for all pages (dashboard, pasien, dokter)
-   `resources/js` and `resources/css` — frontend assets compiled by Vite
-   `app/Helpers` — project helpers (e.g. `MyHelper.php`)

## Configuration

-   `API_URL` in `.env` must point to the backend API used for authentication and
    patient data endpoints.
-   The project uses session-based storage for the API token by default.

## Contributing

Contributions welcome. Please follow these rules:

-   Open an issue to discuss significant changes before making them.
-   Fork the repo and create feature branches for pull requests.
-   Keep commits small and focused; write clear commit messages.

## License

This project follows the MIT license. See the `LICENSE` file for details.

---

If you'd like, I can also:

-   Auto-fill the `Last commit` field from the repo (I found a recent commit hash in `.git`),
-   Add VS Code workspace recommendations, or
-   Generate a quick `docs/` folder with more in-depth developer notes. Let me know which.
