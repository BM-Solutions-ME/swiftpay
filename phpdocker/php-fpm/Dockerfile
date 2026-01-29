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

# Instala o agente PHP da New Relic manualmente
RUN set -eux; \
    cd /tmp; \
    wget https://download.newrelic.com/php_agent/release/newrelic-php5-12.4.0.29-linux.tar.gz; \
    tar -xzf newrelic-php5-12.4.0.29-linux.tar.gz; \
    cd newrelic-php5-12.4.0.29-linux; \
    export NR_INSTALL_SILENT=1; \
    ./newrelic-install install; \
    rm -rf /tmp/newrelic-*; \
    echo 'newrelic.license = ${NEW_RELIC_LICENSE_KEY}' > /etc/php/8.4/fpm/conf.d/99-newrelic.ini; \
    echo 'newrelic.appname = ${NEW_RELIC_APP_NAME}' >> /etc/php/8.4/fpm/conf.d/99-newrelic.ini; \
    cp /etc/php/8.4/fpm/conf.d/99-newrelic.ini /etc/php/8.4/cli/conf.d/99-newrelic.ini


