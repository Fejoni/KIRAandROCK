FROM php:7.4-fpm-alpine

ARG DOCK_USER_ID=1000
ENV DOCK_USER_ID ${DOCK_USER_ID}
ARG DOCK_GROUP_ID=1000
ENV DOCK_GROUP_ID ${DOCK_GROUP_ID}

# persistent dependencies
RUN apk add --no-cache \
# in theory, docker-entrypoint.sh is POSIX-compliant, but priority is a working, consistent image
		bash \
# BusyBox sed is not sufficient for some of our sed expressions
		sed \
# Ghostscript is required for rendering PDF previews
		ghostscript \
		curl \
		&& curl -sS https://getcomposer.org/installer | php \
		&& chmod +x composer.phar && mv composer.phar /usr/local/bin/composer \
		&& apk add --no-cache --repository http://dl-cdn.alpinelinux.org/alpine/edge/community/ --allow-untrusted gnu-libiconv
 
ENV LD_PRELOAD /usr/lib/preloadable_libiconv.so php

RUN set -xe; \
   	addgroup -g ${DOCK_GROUP_ID} dock && adduser -D -G www-data -s /bin/bash  -u ${DOCK_USER_ID} dock && addgroup dock dock;

RUN set -ex; \
	\
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		freetype-dev \
		libjpeg-turbo-dev \
		libpng-dev \
		libwebp-dev \
		libzip-dev \
	; \
	\
	docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp; \
	docker-php-ext-install -j "$(nproc)" \
		bcmath \
		exif \
		gd \
		mysqli \
		opcache \
		zip \
		pdo_mysql \
	; \
	pecl install redis apcu; \
	docker-php-ext-enable redis opcache apcu pdo_mysql; \
	\
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --virtual .laravel-phpexts-rundeps $runDeps; \
	apk del .build-deps

RUN apk add ffmpeg

COPY "php.ini-production" "$PHP_INI_DIR/php.ini"
COPY "audiowaveform" /usr/local/bin/audiowaveform
# set recommended PHP.ini settings
# see https://secure.php.net/manual/en/opcache.installation.php
RUN { \
		echo 'opcache.memory_consumption=128'; \
		echo 'opcache.interned_strings_buffer=8'; \
		echo 'opcache.max_accelerated_files=4000'; \
		echo 'opcache.revalidate_freq=2'; \
		echo 'opcache.fast_shutdown=1'; \
	} > /usr/local/etc/php/conf.d/opcache-recommended.ini
RUN { \
# https://www.php.net/manual/en/errorfunc.constants.php
		echo 'error_reporting = E_ERROR | E_WARNING | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING | E_RECOVERABLE_ERROR'; \
		echo 'display_errors = Off'; \
		echo 'display_startup_errors = Off'; \
		echo 'log_errors = On'; \
		echo 'error_log = /dev/stderr'; \
		echo 'log_errors_max_len = 1024'; \
		echo 'ignore_repeated_errors = On'; \
		echo 'ignore_repeated_source = Off'; \
		echo 'html_errors = Off'; \
	} > /usr/local/etc/php/conf.d/error-logging.ini

USER dock
VOLUME /var/www/html
WORKDIR /var/www/html

CMD ["php-fpm"]
