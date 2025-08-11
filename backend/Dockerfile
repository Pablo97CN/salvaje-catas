FROM php:8.2-fpm

# Dependencias del sistema
RUN apt-get update && apt-get install -y \
    zip unzip curl git libpq-dev libzip-dev \
 && docker-php-ext-install pdo pdo_pgsql zip \
 && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
#----------------------------------------------------------------
# --- Alinear UID/GID con el host (por defecto 1000:1000) ---
ARG APP_UID=1000
ARG APP_GID=1000
# Cambiar GID y UID de www-data para que coincidan con tu usuario de WSL
RUN groupmod -o -g ${APP_GID} www-data && \
    usermod  -o -u ${APP_UID} -g ${APP_GID} www-data

# Opcional: asegurar un umask colaborativo (664/775)
ENV UMASK=0002
SHELL ["/bin/sh", "-lc"]
RUN echo 'umask ${UMASK}' >> /etc/profile
#----------------------------------------------------------------

# Si más adelante existen composer.json/lock, esto no fallará
COPY --chown=www-data:www-data . .

# Permisos razonables
RUN chown -R www-data:www-data /var/www

# Entrypoint que crea el usuario con APP_UID/APP_GID y arranca php-fpm como ese usuario
# COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
# RUN chmod +x /usr/local/bin/entrypoint.sh

# ENTRYPOINT ["entrypoint.sh"]

# php-fpm por defecto
CMD ["php-fpm"]


