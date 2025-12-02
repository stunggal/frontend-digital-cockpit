# Docker deployment (quickstart)

This file explains how to run the project using Docker and Docker Compose.

Prerequisites
- Docker and Docker Compose installed on your machine.

Build and run
1. Build and start containers:

```bash
docker compose up --build -d
```

2. Open the app in a browser: `http://localhost:8080`

Common tasks
- Install PHP dependencies (if needed):

```bash
docker compose run --rm app composer install
```

- Install Node dependencies and build assets (local or run inside container):

```bash
# locally
npm ci && npm run build

# or using the node image (if you prefer containerized build)
docker run --rm -v "$PWD":/app -w /app node:18-alpine sh -c "npm ci && npm run build"
```

- Set API URL and generate app key (no local DB required):

Make sure the backend API is reachable and `API_URL` is set. You can set it in your local `.env` (do not commit) or in `docker-compose.yml` environment for the `app` service. Example:

```dotenv
API_URL=http://47.129.32.35:9000
```

Generate the app key if needed:

```bash
docker compose exec app php artisan key:generate
```

- To enter a shell inside the PHP container:

```bash
docker compose exec app bash
```

Notes
 - This setup does not run a local database in Docker. The application expects all data to come from the configured backend API (`API_URL`).
 - The `docker-compose.yml` mounts the project directory into the container. That makes iterative development easier but means container image build steps that install `vendor` or `node_modules` may be shadowed by the mounted volume. Use `docker compose run --rm app composer install` to install vendor in the container-managed volume when needed.
 - If you deploy to a remote server, consider building images in CI and pushing to a registry rather than mounting your workspace at runtime.