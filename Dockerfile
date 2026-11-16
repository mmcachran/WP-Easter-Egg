ARG PHP_VERSION=8.2
ARG WORDPRESS_VERSION=latest

FROM php:${PHP_VERSION}-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    && docker-php-ext-install -j$(nproc) \
    zip \
    gd \
    mysqli \
    && a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Download WordPress
RUN curl -o /tmp/wordpress.tar.gz https://wordpress.org/wordpress-${WORDPRESS_VERSION}.tar.gz \
    && tar -xzf /tmp/wordpress.tar.gz --strip-components=1 -C /var/www/html \
    && rm /tmp/wordpress.tar.gz

# Copy plugin into WordPress plugins directory
COPY . /var/www/html/wp-content/plugins/wp-easter-egg/

# Install Composer dependencies for the plugin
RUN cd /var/www/html/wp-content/plugins/wp-easter-egg \
    && composer install --no-interaction --prefer-dist --no-dev

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/wp-content

# Enable Apache rewrite
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
