<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# SSLaiss API

1. Clonar el proyecto
```
git clone https://github.com/jhon78963/ssl-backend.git
```
2. Instalar dependencias
```
composer install
```
3. Levantar postgresql con docker
```
docker-compose up --build -d
```
4. Clonar el archivo ```.env.example``` y renombrar a ```.env```
5. Cambiar las variables de entorno
6. Ejecutar migraciones y/o seeders
```
php artisan migrate --seed
```
7. Levantar servidor
```
php artisan serve
```

# Comandos Bootstrap y Cache
```
sudo a2enmod rewrite
sudo chown -R www-data:www-data /var/www/html
sudo chown -R $USER:www-data storage
sudo chown -R $USER:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
sudo systemctl restart apache2
php artisan optimize:clear
php artisan route:cache
php artisan view:cache
php artisan config:cache
```
