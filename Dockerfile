FROM ubuntu:22.04
RUN apt-get update && apt-get install -y software-properties-common
RUN add-apt-repository ppa:ondrej/php -y
RUN apt-get install -y \
    php8.2 \
    php8.2-cli \
    php8.2-mysql \
    php8.2-xml \
    php8.2-mbstring \
    php8.2-zip \
    
COPY . /var/www/html
WORKDIR /var/www/html
RUN composer install --no-dev
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=${PORT}"]