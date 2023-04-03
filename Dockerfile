FROM php:8.1.4-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid
ARG WITH_XDEBUG
# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libzip-dev \
    unzip \
    libpq-dev \
    libcurl4-openssl-dev \
    libssl-dev

RUN apt-get install -y iputils-ping

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd pdo_pgsql pgsql curl sockets zip
# RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd opcache pdo simplexml xml xmlrpc xmlwriter calendar \
#     ctype curl dom exif fileinfo ftp gettext iconv intl json mysqli pdo_mysql pgsql phar posix shmop \
#     soap sockets sysvmsg sysvsem sysvshm tokenizer zip

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

RUN if [ $WITH_XDEBUG = 1 ] ; then \
        pecl install xdebug; \
        docker-php-ext-enable xdebug; \
        echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
        echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    fi ;

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

USER $user