FROM phpdockerio/php:8.4-fpm
WORKDIR "/application"

# Instala dependências e extensões PHP
RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        php8.4-gd \
        php8.4-memcached \
        php8.4-mongodb \
        php8.4-redis \
        php8.4-mysql \
        php8.4-exif \
        php8.4-mbstring \
        wget \
        gnupg2 \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Instala o agente PHP da New Relic
RUN wget -O - https://download.newrelic.com/548C16BF.gpg | apt-key add - \
    && echo "deb http://apt.newrelic.com/debian/ newrelic non-free" > /etc/apt/sources.list.d/newrelic.list \
    && apt-get update \
    && apt-get install -y newrelic-php5 \
    && export NR_INSTALL_SILENT=1 \
    && newrelic-install install \
    && echo 'newrelic.license = "fa499b09bfe3d68e021430adf52f1c29FFFFNRAL"' > /etc/php/8.4/fpm/conf.d/99-newrelic.ini \
    && echo 'newrelic.appname = "fastpay_php_docker"' >> /etc/php/8.4/fpm/conf.d/99-newrelic.ini \
    && cp /etc/php/8.4/fpm/conf.d/99-newrelic.ini /etc/php/8.4/cli/conf.d/99-newrelic.ini \
    && rm -rf /var/lib/apt/lists/*
