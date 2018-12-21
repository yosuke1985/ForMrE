FROM php:7.2.12-fpm
RUN apt-get update && apt-get install -y \
        git \
        zip \
        unzip \
        vim

RUN docker-php-ext-install \
        pdo_mysql \
        pdo \
        mysqli

RUN mkdir -p /var/www
WORKDIR /var/www

# install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

#php.iniの設置
#COPY php/php.ini /usr/local/etc/php/php.ini
