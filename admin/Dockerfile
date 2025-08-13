FROM node:20

WORKDIR /app
ENV NODE_ENV=development
EXPOSE 5174

RUN chown -R node:node /app
USER node

# Arranque idempotente: Verificar que exista package.json en /app
#                       Si /app/node_modules NO existe o está vacío, instala; luego ejecuta Vite.
CMD ["sh","-lc","\
  if [ ! -f package.json ]; then \
    echo 'ERROR: falta /app/package.json (¿montaste ./frontend:/app?)' >&2; \
    exit 1; \
  fi; \
  if [ ! -d node_modules ] || [ -z \"$(ls -A node_modules 2>/dev/null)\" ]; then \
    echo '>> Instalando dependencias (dev)...'; \
    if [ -f package-lock.json ]; then npm ci --include=dev; else npm install --include=dev; fi; \
  fi; \
  npm run dev \
"]
