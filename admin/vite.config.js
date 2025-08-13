// admin/vite.config.js
import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
  base: '/app/',             // misma base en dev y build
  plugins: [react(), tailwindcss()],
  server: {
    host: true,              // 0.0.0.0 dentro del contenedor
    port: 5174,
    strictPort: true,
    // Si el WS de HMR pasa por Nginx :80 y ves aviso de fallback:
    // hmr: { clientPort: 80 }
  }
})
