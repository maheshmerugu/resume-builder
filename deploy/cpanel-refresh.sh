#!/bin/bash
# Run on cPanel after git pull to refresh routes, views, and SEO pages.
set -e
cd "$HOME/public_html"

php artisan migrate --force --no-interaction || true
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Done. Test: https://airesumebuilder.co.in/sitemap.xml"
