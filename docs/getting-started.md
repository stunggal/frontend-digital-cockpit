# Getting Started (Developer)

Quick checklist to get the project running locally for development:

1. Copy `.env` and set `API_URL` to the backend endpoint.
2. Install PHP dependencies: `composer install`.
3. Install JS dependencies: `npm install`.
4. Build assets: `npm run dev` (or `npm run build` for production).
5. Run migrations if needed: `php artisan migrate`.
6. Start the dev server: `php artisan serve`.

Notes:
- The app expects a backend API that provides authentication and patient endpoints.
- The project stores API token in session (`api_token`) after login.
