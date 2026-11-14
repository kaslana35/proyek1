# Deployment & Operational Guide — Sewa Laptop Enterprise Core

## 1. Environment Setup (Production)

Copy `.env.example` to `.env` and set:

```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
APP_KEY=base64:...   # php artisan key:generate
SESSION_SECURE_COOKIE=true
SANCTUM_TOKEN_EXPIRATION=1440
```

## 2. Database

```bash
php artisan migrate --force
php artisan db:seed --force   # first deploy only
```

## 3. Storage Link

```bash
php artisan storage:link
```

## 4. Scheduler (Cron Job)

Add to crontab (`crontab -e`):

```cron
* * * * * cd /path/to/sewa-laptop && php artisan schedule:run >> /dev/null 2>&1
```

This runs `laptop:check-expired` daily to mark overdue rentals.

## 5. Queue Worker (if QUEUE_CONNECTION=database)

```bash
php artisan queue:work --daemon --tries=3
```

Or use Supervisor:

```ini
[program:sewa-laptop-worker]
command=php /path/to/sewa-laptop/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
```

## 6. Nginx Configuration (File Upload Hardening)

```nginx
server {
    listen 443 ssl http2;
    server_name your-domain.com;
    root /path/to/sewa-laptop/public;
    index index.php;

    # Prevent execution of scripts in storage
    location ~* ^/storage/.*\.(php|php3|php4|php5|phtml|pl|py|jsp|asp|sh|cgi)$ {
        deny all;
        return 403;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Force HTTPS
    if ($scheme != "https") {
        return 301 https://$host$request_uri;
    }
}
```

## 7. Apache .htaccess (already in storage/app/public)

The `.htaccess` in `storage/app/public` blocks script execution. Ensure `AllowOverride All` is set in Apache config.

## 8. Optimization

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 9. Security Checklist

- [ ] `APP_DEBUG=false`
- [ ] `SESSION_SECURE_COOKIE=true`
- [ ] `SESSION_DOMAIN=null` (scope cookie to exact host, prevent subdomain sharing)
- [ ] HTTPS enforced (ForceHttps middleware active in production)
- [ ] Cron job configured
- [ ] Storage link created
- [ ] `.env` not committed to git
- [ ] `composer audit` passed
- [ ] TrustHosts configured (no subdomain trust)
- [ ] CDN/Proxy configured to not cache based on untrusted headers (cache poisoning prevention)

