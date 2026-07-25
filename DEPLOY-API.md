# Deploy headless API to api.labaykph.com

## Problem

If the PWA shows **CORS / Network Error** but the browser console says:

`No 'Access-Control-Allow-Origin' header is present`

the API request may be failing **before Laravel runs** (nginx 404). Verify:

```bash
curl -I https://api.labaykph.com/api/v1/home
```

Expected: `HTTP/1.1 200` with JSON (and CORS headers when `Origin` is sent).

If you get `404` from **nginx** (plain HTML), the server document root is wrong.

## Server requirements

1. Upload **`labayk-api-headless-v13`** to the server.
2. **Document root must be** `.../labayk-api-headless-v13/public` (the `public` folder).
3. Do **not** expose the project root or `/vendor`.

## Nginx

Use `deploy/nginx-api.labaykph.com.conf` as a template. Critical line:

```nginx
root /path/to/labayk-api-headless-v13/public;
```

Reload nginx after changes.

## Laravel setup on server

```bash
cd /path/to/labayk-api-headless-v13
composer install --no-dev --optimize-autoloader
cp .env.example .env   # then edit
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
```

## Production `.env`

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.labaykph.com

# Website for email links (login / reset). NEVER use https://api.labaykph.com here.
FRONTEND_URL=https://labaykph.com
SANCTUM_STATEFUL_DOMAINS=test.labaykph.com,labaykph.com,www.labaykph.com
```

## Update code on the live server (required after git push)

Pushing to GitHub does **not** update `api.labaykph.com` by itself. On the server:

```bash
cd /path/to/labayk-api-headless-v13
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
```

After deploy, a new registration email must show **Verify my email** and link to  
`https://labaykph.com/registration-verify/{email}/{token}` (not `api.labaykph.com`).  
New accounts stay **pending** (`status = 0`) until that link is clicked.

## Verify after deploy

```bash
curl -I https://api.labaykph.com/api/v1/home
curl -I -X OPTIONS https://api.labaykph.com/api/v1/home \
  -H "Origin: https://test.labaykph.com" \
  -H "Access-Control-Request-Method: GET"
```

Both should succeed (OPTIONS → 204 with CORS headers).

## PWA

Production build uses `https://api.labaykph.com/api/v1` (see `labaykph-pwa/.env.production`).
