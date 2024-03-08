#!/bin/bash
#
# Init script for PHP admin
#


# Composer + NPM install
echo "Installing composer items..."
cd /var/www/html/
composer selfupdate --1
composer install


# Copy config files for local developement (don't overwrite files when already exists)
if [ ! -f ./config/autoload/local.php ]; then
 echo "Copying config files..."
 cd /var/www/html/
 cp -n ./config/autoload/local.php.dist ./config/autoload/local.php
 cp -n ./config/autoload/adminaut.local.php.dist ./config/autoload/adminaut.local.php
 cp -n ./config/autoload/development.local.php.dist ./config/autoload/development.local.php
 cp -n ./config/development.config.php.dist ./config/development.config.php
 cp -n ./public/.htaccess.dist ./public/.htaccess
fi

if [ ! -d ./www_root ]; then
 echo "Creating symlinks..."
 cd /var/www/html/
 ln -s public www_root
 ln -s ../vendor/adminaut/adminaut/public/adminaut www_root/adminaut
fi

echo "Fixing file permissions"
chmod a+rw data/ -R
chmod a+rw public/_cache -R

echo "Waiting for database"
while ! mysql -h db -u root -proot  -e ";" ; do
    echo ""
done

echo "Running migrations"
php vendor/bin/doctrine-module mi:mi --no-interaction --allow-no-migration

echo "#################################################################################################################";
echo "##################################################   READY    ###################################################";
echo "#################################################################################################################";

# run Apache
apache2-foreground
