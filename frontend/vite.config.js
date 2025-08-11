
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import tailwindcss from "@tailwindcss/vite"

export default defineConfig({
  plugins: [tailwindcss(), react()],
  server: {
    host: true,           // 0.0.0.0 dentro del contenedor
    port: 5173,
    strictPort: true,

    // ✅ Permite el host "frontend" (nombre del servicio Docker)
    allowedHosts: ['frontend', 'localhost'],

    // ✅ HMR detrás de Nginx escuchando en :80
    hmr: {
      host: 'localhost',
      clientPort: 80,
      protocol: 'ws'
    },

    // ✅ Origin que verá el navegador cuando accedes vía Nginx
    origin: 'http://localhost'
  }
})

