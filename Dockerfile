# Use the PHP 8.2 CLI image based on Alpine Linux
FROM php:8.2-cli-alpine

# Install dependencies for PCNTL extension and install the extension
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && docker-php-ext-install pcntl \
    && apk del .build-deps

# Copy application files
COPY . /var/www

# Set the working directory
WORKDIR "/var/www"

# Set the command to run your main script
CMD [ "php", "Main.php", "start" ]
