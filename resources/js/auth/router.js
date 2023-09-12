import { createRouter, createWebHistory } from "vue-router";

import LoginPage from "@auth/Pages/Login/Login.vue";
import RegisterPage from "@auth/Pages/Register/Register.vue";
import NotFoundPage from "@auth/Pages/NotFound/NotFound.vue";
import VerificationPage from "@auth/Pages/Verification/Verification.vue";
import ConfirmationPage from "@auth/Pages/Confirmation/Confirmation.vue";
import { useAuthStore } from "@shared/+store/auth.store.js";
import axios from "axios";

const routes = [
    {
        path: "/login",
        name: "LoginPage",
        component: LoginPage,
    },
    {
        path: "/register",
        name: "RegisterPage",
        component: RegisterPage,
    },
    {
        path: "/verification",
        name: "VerificationPage",
        component: VerificationPage,
    },
    {
        path: "/confirmation",
        name: "ConfirmationPage",
        component: ConfirmationPage,
    },
    { path: "/:pathMatch(.*)*", name: "NotFoundPage", component: NotFoundPage },
];

const router = createRouter({
    history: createWebHistory("/auth"),
    routes,
});

router.beforeEach(async (to, from) => {
    const authStore = useAuthStore();

    if (
        authStore.phoneNumber === null &&
        to.name !== "LoginPage" &&
        to.name !== "RegisterPage"
    ) {
        axios.get("/sanctum/csrf-cookie").then(() => {
            axios
                .get(`/api/v1/me`)
                .then((response) => {
                    if (response.data.role === "patient") {
                        window.location.href = `/patient/home`;
                    }

                    if (response.data.role === "doctor") {
                        window.location.href = `/doctor/home`;
                    }
                })
                .catch((error) => {
                    if (error.response.status === 401) {
                        authStore.$reset();
                        window.location.href = `/auth/login`;
                    }
                });
        });
    }
});

export default router;
