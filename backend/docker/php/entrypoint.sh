#!/bin/sh
##-- DESACTIVADO --##
set -e

APP_UID=${APP_UID:-1000}
APP_GID=${APP_GID:-1000}
APP_USER=${APP_USER:-dev}
APP_GROUP=${APP_GROUP:-dev}

# Elegir nombre de grupo para el GID dado (si ya existe, usamos ese)
EXISTING_GROUP="$(getent group "$APP_GID" | cut -d: -f1 || true)"
if [ -n "$EXISTING_GROUP" ]; then
  RUN_GROUP="$EXISTING_GROUP"
else
  groupadd -g "$APP_GID" "$APP_GROUP"
  RUN_GROUP="$APP_GROUP"
fi

# Elegir usuario para el UID dado (si ya existe, usamos ese)
EXISTING_USER="$(getent passwd "$APP_UID" | cut -d: -f1 || true)"
if [ -n "$EXISTING_USER" ]; then
  RUN_USER="$EXISTING_USER"
  # Intentar alinear grupo primario y shell (no es crítico si falla)
  usermod -g "$APP_GID" "$RUN_USER" 2>/dev/null || true
  usermod -s /bin/sh "$RUN_USER" 2>/dev/null || true
else
  useradd -u "$APP_UID" -g "$APP_GID" -m -s /bin/sh "$APP_USER"
  RUN_USER="$APP_USER"
fi

# Asegurar permisos de /var/www (ignorar errores si es bind mount read-only)
mkdir -p /var/www
chown -R "$APP_UID:$APP_GID" /var/www 2>/dev/null || true

# Ejecutar el entrypoint oficial con el usuario elegido
exec docker-php-entrypoint "$@"
