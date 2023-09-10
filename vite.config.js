import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import path from "path";

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: [
                // assets related
                "resources/css/app.css",
                "resources/css/style.css",
                // "resources/css/style.css.map",
                "resources/js/libs/alert.js",
                "resources/js/libs/multiselect.js",
                "resources/js/libs/notification.js",
                // vue related - auth
                "resources/js/auth/auth.js",
                "resources/js/patient/patient.js",
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            "~bootstrap": path.resolve(__dirname, "node_modules/bootstrap"),
            "@resources": path.resolve(__dirname, "resources"),
            "@auth": path.resolve(__dirname, "resources/js/auth"),
            "@shared": path.resolve(__dirname, "resources/js/shared"),
            "@patient": path.resolve(__dirname, "resources/js/patient"),
        },
    },
});
