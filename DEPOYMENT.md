-CREATED A DATABASE IN HOSTINGER

-CHANGES in .env file for the databse name, id, password and the site.
DATABASE_URL="mysql://DB_USER:DB_PASSWORD@IP_HOST/DB_NAME"

- the images the img folder should be inside the public folder.

- GO TO HOSTINGER tableau aboard, then gestionnaire des fichier

  Drop all the files of symfony project of local inside public_html

- transfer all the neccessary files excluding .git, vendor, .gitignore with 
  .env: APP_ENV=dev always for the moment

- login to the hostinger SSH using the connection SSH provided by the hostinger on the cmd line terminal in your computer

- enter the password for the db which you have set for the db_codexpress

- and then lanch the command to change the permissions to the dossier public_html
```
chmod -R 755 public_html
cd public_html
```

  php bin/console doctrine:migrations:migrate --env=prod
 // BUT THIS WAS NOT WORKING SAID PDO DRIVER NOT FOUND
- THEN use phpmyadmin of the hostinger and import the db directly using phpMyAdmin

- add the .htaccess in root folder of the symfony project with # Redirect all traffic to the public     directory
  
```
  RewriteEngine On
  RewriteRule ^(.\*)$ /public/$1 [L]
  php_value display_errors 1
  php_value display_startup_errors 1
```
- add the .htaccess inside the public folder of the symfony project with # Enable URL rewriting
  RewriteEngine On

 ```
 # Enable URL rewriting
 RewriteEngine On 
 # Rediriger toutes les requêtes HTTP vers HTTPS (facultatif : décommentez si vous souhaitez utiliser HTTPS)
 # RewriteCond %{HTTPS} !=on
 # RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301] 
 # Rediriger toutes les requêtes vers le contrôleur frontal de Symfony (index.php) si le fichier ou le répertoire demandé n'existe pas
 RewriteCond %{REQUEST_FILENAME} !-f
 RewriteCond %{REQUEST_FILENAME} !-d
 RewriteRule ^(.*)$ index.php [QSA,L] 
 # Pages d'erreur personnalisées pour Symfony (facultatif)
 ErrorDocument 404 /index.php
 ErrorDocument 500 /index.php 
 # Paramètres PHP pour Symfony (ajustez selon les besoins)
 php_value memory_limit 256M
 php_value upload_max_filesize 50M
 php_value post_max_size 50M
 php_value max_execution_time 300
 php_value mysql.connect_timeout 60
 php_value mysqli.reconnect 1 
 # Entêtes de sécurité (facultatif)
 <IfModule mod_headers.c>
     Header set X-Content-Type-Options "nosniff"
     Header set X-Frame-Options "SAMEORIGIN"
     Header set X-XSS-Protection "1; mode=block"
 </IfModule> 
 # Mise en cache des ressources statiques (facultatif, améliore la vitesse de chargement)
 <IfModule mod_expires.c>
     ExpiresActive On
     ExpiresByType image/jpg "access plus 1 month"
     ExpiresByType image/jpeg "access plus 1 month"
     ExpiresByType image/gif "access plus 1 month"
     ExpiresByType image/png "access plus 1 month"
     ExpiresByType text/css "access plus 1 week"
     ExpiresByType application/javascript "access plus 1 week"
     ExpiresByType text/javascript "access plus 1 week"
 </IfModule>
 ```

  // this is generally to redirect the website to the index.php of the public folder which will do the necessary to do the routing of the routes in symfony
  //Inside the public folder, this .htaccess file will manage Symfony’s URL rewriting, handle PHP settings, set up custom error pages, and set up caching for assets.

- connect to SSH using terminus /git bash
  and go to file public_html and launch
  ```
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
  php composer-setup.php
  php -r "unlink('composer-setup.php');"
```
- php bin/console cache:clear --env=dev
  cleared the cache as the website was always in dev mode for the moment

-added this code to display the error at the PHP level which was not visible earlier with the incapatibility of the php versions
php_value display_errors 1
php_value display_startup_errors 1

- with the code above i got to know that i didnt do the do the installation of it dependencies which en error of the autoloading when i went to the site codexpress( vendor was missing)

- installation of all the dependencies via git bash ( connecting using ssh)
```
  php composer.phar install --no-dev --optimize-autoloader
  ```

- but with the commande above i used to have a problem of the debug_bunddle so i change the .env file
.env: APP_ENV=prod
 APP_DEBUG=false

MAILER_DSN

- the contact page of the website was not working as there was no smtp de mail
  so i have added my mailtrap.io accounts smtp to be able to access the contact page