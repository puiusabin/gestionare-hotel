FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Force MPM Prefork and disable others to prevent "More than one MPM loaded"
# This is done in one step to be sure
RUN a2dismod mpm_event mpm_worker || true && a2enmod mpm_prefork || true

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files
COPY composer.json ./

# Install dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Copy application files
COPY . .

# Update Apache configuration for public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Final permissions fix
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

# Use the default entrypoint
CMD ["apache2-foreground"]
