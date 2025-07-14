FROM dunglas/frankenphp:1-php8.3-alpine

WORKDIR /app

# Install build-time dependencies to compile the extension,
# AND runtime dependencies that must stay in the final image.
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS postgresql-dev \
    && apk add --no-cache postgresql-libs \
    && docker-php-ext-install pdo_pgsql \
    && apk del .build-deps

# Copy Composer executable, then install dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --optimize-autoloader

# Copy the rest of the application code
COPY . .
