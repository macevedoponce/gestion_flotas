#!/bin/bash

echo "🔧 Fixing storage and permissions..."

chmod -R 777 storage
chmod -R 777 public/storage
chmod -R 777 bootstrap/cache

if [ -d "public/storage" ]; then
    echo "🔗 Storage link exists."
else
    echo "🔗 Creating storage link..."
    php artisan storage:link --force
fi

echo "👌 Storage is now fully operational."
