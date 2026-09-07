FROM php:8.2-apache

# Install required PHP extensions for MySQL (PDO & Mysqli)
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable Apache rewrite module for clean URLs (.htaccess)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy all project files into Apache root
COPY . /var/www/html/

# Set DocumentRoot to the public folder
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf

# Set directory permissions for public uploads
RUN chown -R www-data:www-data /var/www/html/public/uploads \
    && chmod -R 755 /var/www/html/public/uploads

EXPOSE 80

CMD ["apache2-foreground"]
