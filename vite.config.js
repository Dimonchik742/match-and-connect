import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: "127.0.0.1", // Використовуємо стандартний IPv4 замість [::1]
        cors: true, // Дозволяємо завантажувати скрипти з інших доменів (нашого leonardo)
    },
});
