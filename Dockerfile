# On part d'une image officielle PHP avec Apache
FROM php:8.2-apache

# Installation des extensions PHP nécessaires 
# (mysqli est obligatoire pour le projet car je l'ai utilisé)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Activation du module de réécriture d'URL d'Apache
# (Utile si vous utilisez des fichiers .htaccess plus tard)
RUN a2enmod rewrite

# Copie de votre code source dans le conteneur
# COPY . /var/www/html/

# Gestion des droits pour le dossier d'uploads (CRUCIAL pour votre projet)
# On s'assure que le serveur web peut écrire dedans
RUN chown -R www-data:www-data /var/www/html