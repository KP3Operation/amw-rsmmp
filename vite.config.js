import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import path from "path";
import { defineConfig } from "vite";

export default defineConfig({
    define: {
        __VUE_I18N_FULL_INSTALL__: true,
        __VUE_I18N_LEGACY_API__: false,
        __INTLIFY_PROD_DEVTOOLS__: false,
    },
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
                "resources/js/doctor/doctor.js",
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
            "@doctor": path.resolve(__dirname, "resources/js/doctor"),
        },
    },
    test: {
        environment: "happy-dom",
        setupFiles: ["resources/js/shared/unit.setup.js"],
    },
});
