import { createRouter, createWebHistory } from "vue-router";

import LoginPage from "@auth/Pages/Login/Login.vue";
import NotFoundPage from "@auth/Pages/NotFound/NotFound.vue";
import VerificationPage from "@auth/Pages/Verification/Verification.vue";
import {useAuthStore} from "@shared/+store/auth.store.js";

const routes = [
    {
        path: "/login",
        name: "LoginPage",
        component: LoginPage,
    },
    {
        path: "/verification",
        name: "VerificationPage",
        component: VerificationPage,
    },
    { path: "/:pathMatch(.*)*", name: "NotFoundPage", component: NotFoundPage },
];

const router = createRouter({
    history: createWebHistory("/auth"),
    routes,
});

router.beforeEach(async (to, from) => {
    const authStore = useAuthStore();

    if (to.name === "LoginPage") {
        authStore.$reset();
    }

    if (to.name === "VerificationPage") {
        if (authStore.phoneNumber === null) {
            return { name: "LoginPage" }
        }
    }
});

export default router;
