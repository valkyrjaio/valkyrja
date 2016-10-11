FROM php:7.0-apache

# Install php7.0-fpm with needed extensions
#RUN apt-get -y install php7.0-fpm php7.0-cli php7.0-common php7.0-json php7.0-opcache php7.0-mysql php7.0-phpdbg php7.0-mbstring php7.0-gd php7.0-imap php7.0-ldap php7.0-pgsql php7.0-pspell php7.0-recode php7.0-snmp php7.0-tidy php7.0-dev php7.0-intl php7.0-gd php7.0-curl php7.0-zip php7.0-xml

# Install redis
RUN apt-get update && apt-get -y --force-yes install redis-server && service redis-server stop

# PHP INI
COPY docker/php.ini /usr/local/etc/php/php.ini

# Install git
RUN apt-get -y --force-yes install git

# Install composer for PHP dependencies
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Node.js
RUN curl -sL https://deb.nodesource.com/setup_6.x | bash - && apt-get install -y --force-yes build-essential nodejs
RUN npm install -g bower gulp-cli

# Xdebug
RUN pecl install xdebug-2.4.0
COPY docker/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Manually set up the apache environment variables
ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data
ENV APACHE_LOG_DIR /var/log/apache2
ENV APACHE_LOCK_DIR /var/lock/apache2
ENV APACHE_PID_FILE /var/run/apache2.pid

# Update the default apache2 config
COPY docker/apache2.conf /etc/apache2/apache2.conf

# Copy sites-enabled
ADD docker/sites-enabled /etc/apache2/sites-enabled

# Apache
RUN a2enmod rewrite

RUN rm -Rf /var/www/html && chown www-data:www-data /var/www

# Copy this repo into place.
ADD ./ /var/www/site

# By default start up apache in the foreground, override with /bin/bash for interative.
CMD /usr/sbin/apache2ctl -D FOREGROUND
