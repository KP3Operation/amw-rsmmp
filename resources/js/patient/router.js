import { createRouter, createWebHistory } from "vue-router";

import HomePage from "@patient/Pages/Home/Home.vue";
import AppointmentPage from "@patient/Pages/Appointment/Appointment.vue";
import HistoryPage from "@patient/Pages/History/History.vue";
import ProfilePage from "@patient/Pages/Profile/Profile.vue";
import NotFoundPage from "@shared/Pages/NotFound/NotFound.vue";
import { useAuthStore } from "@shared/+store/auth.store.js";
import { useLayoutStore } from "@shared/+store/layout.store.js";

const routes = [
    {
        path: "/home",
        name: "HomePage",
        component: HomePage,
    },
    {
        path: "/appointment",
        name: "AppointmentPage",
        component: AppointmentPage,
    },
    {
        path: "/history",
        name: "HistoryPage",
        component: HistoryPage,
    },
    {
        path: "/profile",
        name: "ProfilePage",
        component: ProfilePage,
    },
    { path: "/:pathMatch(.*)*", name: "NotFoundPage", component: NotFoundPage },
];

const router = createRouter({
    history: createWebHistory("/patient"),
    routes,
});

router.beforeEach(async (to, from) => {
    const authStore = useAuthStore();
    const layoutStore = useLayoutStore();

    if (to.name === "HomePage") {
        layoutStore.patientActiveMenu = "home";
    }

    if (to.name === "HistoryPage") {
        layoutStore.patientActiveMenu = "history";
    }

    if (to.name === "AppointmentPage") {
        layoutStore.patientActiveMenu = "appointment";
    }

    if (to.name === "ProfilePage") {
        layoutStore.patientActiveMenu = "profile";
    }

    if (authStore.userEmail === null || authStore.userId === 0) {
        axios.get("/sanctum/csrf-cookie").then(() => {
            axios.get(`/api/v1/me`).then((response) => {
                authStore.userFullName = response.data.user.name;
                authStore.userEmail = response.data.user.email;
                authStore.userId = response.data.user.id;
                authStore.phoneNumber = response.data.user.phone_number.replace(`${import.meta.env.VITE_CALLING_CODE}`, "");

            }).catch((error) => {
                if (error?.response?.status === 401) {
                    authStore.$reset();
                    window.location.href = `/auth/login`;
                }
            });
        });
    }
});

export default router;
