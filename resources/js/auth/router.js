import { createRouter, createWebHistory } from "vue-router";

import LoginPage from "@auth/Pages/Login/Login.vue";
import NotFoundPage from "@auth/Pages/NotFound/NotFound.vue";

const routes = [
    {
        path: "/login",
        name: "LoginPage",
        component: LoginPage,
    },
    { path: "/:pathMatch(.*)*", name: "NotFoundPage", component: NotFoundPage },
];

const router = createRouter({
    history: createWebHistory("/auth"),
    routes,
});

export default router;
