import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/js/common.js",
                "resources/css/table_docen.css",
                "resources/css/pasivo/pasivo.css",
                "resources/css/filtracion/filtracion.css",
                "resources/css/filtracion/table.css",
                "resources/css/activo/activo.css",
                "resources/css/activo/loader.css",
                "resources/css/table_docen.css",
                "resources/css/pasivo/pasivo.css",
                "resources/css/table_docen.css",
               "resources/css/table_docen.css",
                "resources/css/incidente/form.css",
                "resources/css/resultado/card.css",
                "resources/css/resultado/resultado.css",

               
            ], // Agregar common.js aquí
            refresh: true,
        }),
    ],
    server: {
        host: "0.0.0.0",
        port: 5173,
        hmr: {
            host: "localhost",
        },
    },
});
