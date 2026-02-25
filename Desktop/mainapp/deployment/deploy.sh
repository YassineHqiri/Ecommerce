#!/bin/bash

# AtlasTech Solutions - Deployment Script
# Run this script on your VPS server

set -e

echo "========================================"
echo "AtlasTech Solutions Deployment Script"
echo "========================================"

# Update system
echo "[1/10] Updating system packages..."
sudo apt update && sudo apt upgrade -y

# Install required packages
echo "[2/10] Installing required packages..."
sudo apt install -y curl git unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev

# Install PHP and extensions
echo "[3/10] Installing PHP and extensions..."
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-curl php8.2-gd php8.2-mbstring php8.2-xml php8.2-zip php8.2-bcmath

# Install Composer
echo "[4/10] Installing Composer..."
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
echo "[5/10] Installing Node.js..."
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install MySQL
echo "[6/10] Installing MySQL..."
sudo apt install -y mysql-server
sudo mysql_sec_installation

# Create database and user
echo "[7/10] Setting up database..."
sudo mysql -e "CREATE DATABASE atlastech CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER 'atlastech_user'@'localhost' IDENTIFIED BY 'YOUR_STRONG_PASSWORD';"
sudo mysql -e "GRANT ALL PRIVILEGES ON atlastech.* TO 'atlastech_user'@'localhost';"
sudo mysql -e "FLUSH PRIVILEGES;"

# Install Nginx
echo "[8/10] Installing Nginx..."
sudo apt install -y nginx

# Configure firewall
echo "[9/10] Configuring firewall..."
sudo ufw allow 'Nginx Full'
sudo ufw allow OpenSSH
sudo ufw --force enable

echo "[10/10] Deployment preparation complete!"
echo ""
echo "Next steps:"
echo "1. Upload your Laravel backend to /var/www/atlastech-api"
echo "2. Upload your React build to /var/www/atlastech-frontend"
echo "3. Configure Nginx with deployment/nginx-ssl.conf"
echo "4. Set up SSL with Let's Encrypt"
echo "5. Run: php artisan migrate --force"
echo "6. Run: php artisan db:seed"
echo ""
echo "========================================"
