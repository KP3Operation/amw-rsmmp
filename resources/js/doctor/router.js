import { createRouter, createWebHistory } from "vue-router";

import HomePage from "@doctor/Pages/Home/Home.vue";
import NotificationPage from "@doctor/Pages/Notification/Notification.vue";
import AppointmentPage from "@doctor/Pages/Appointment/Appointment.vue";
import FeePage from "@doctor/Pages/Fee/Fee.vue";
import ProfilePage from "@doctor/Pages/Profile/Profile.vue";
import NotFoundPage from "@shared/Pages/NotFound/NotFound.vue";
import InpatientListPage from "@doctor/Pages/InpatientList/InpatientList.vue";
import PatientDetailPage from "@doctor/Pages/InpatientList/PatientDetail.vue";
import { useAuthStore } from "@shared/+store/auth.store.js";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import AppointmentDetailPage from "@doctor/Pages/Appointment/AppointmentDetail.vue";
import apiRequest from "@shared/utils/axios.js";


const routes = [
    {
        path: "/home",
        name: "HomePage",
        component: HomePage,
    },
    {
        path: "/notification",
        name: "NotificationPage",
        component: NotificationPage,
    },
    {
        path: "/fee",
        name: "FeePage",
        component: FeePage,
    },
    {
        path: "/appointment/detail",
        name: "AppointmentDetailPage",
        component: AppointmentDetailPage,
    },    {
        path: "/appointment",
        name: "AppointmentPage",
        component: AppointmentPage,
    },
    {
        path: "/inpatient/list",
        name: "InpatientListPage",
        component: InpatientListPage,
    },
    {
        path: "/inpatient/detail",
        name: "PatientDetailPage",
        component: PatientDetailPage,
    },
    {
        path: "/profile",
        name: "ProfilePage",
        component: ProfilePage,
    },
    { path: "/:pathMatch(.*)*", name: "NotFoundPage", component: NotFoundPage },
];

const router = createRouter({
    history: createWebHistory("/doctor"),
    routes,
});

router.beforeEach(async (to, from) => {
    const authStore = useAuthStore();
    const layoutStore = useLayoutStore();

    if (authStore.userData.phoneNumber === null || authStore.userData.userId === 0) {
        try {
            await axios.get("/sanctum/csrf-cookie");
            const response = await axios.get(`/api/v1/me`);
            authStore.updateUserData({
                userFullName: response.data.user.name,
                userEmail: response.data.user.email,
                userId: response.data.user.id,
                phoneNumber: response.data.user.phone_number.toString().replace(
                    `${import.meta.env.VITE_CALLING_CODE}`,
                    ""
                ),
                userRole: response.data.role,
            });

            if (response.data.role === "doctor") {
                authStore.updateUserDoctorData({
                    doctorId: response.data.doctor_data.doctor_id,
                    smfName: response.data.doctor_data.smf_name,
                });
            }

            if (authStore.userData.userRole !== "doctor") {
                authStore.$reset();
                window.location.href = `/doctor/home`;
                return;
            }
        } catch (error) {
            if (
                error.response &&
                error.response.status &&
                error.response.status === 401
            ) {
                authStore.$reset();
                window.location.href = `/auth/login`;
                return;
            }
        }
    }

    if (to.name === "HomePage") {
        layoutStore.doctorActiveMenu = "home";
    }

    if (to.name === "FeePage") {
        layoutStore.doctorActiveMenu = "fee";
    }

    if (to.name === "AppointmentPage") {
        layoutStore.doctorActiveMenu = "appointment";
    }

    if (to.name === "ProfilePage") {
        layoutStore.doctorActiveMenu = "profile";
    }

    // if (authStore.userData.phoneNumber === null || authStore.userData.userId === 0) {
    //     apiRequest.get("/sanctum/csrf-cookie").then(() => {
    //         apiRequest
    //             .get(`/api/v1/me`)
    //             .then((response) => {
    //                 authStore.updateUserData({
    //                     userFullName: response.data.user.name,
    //                     userEmail: response.data.user.email,
    //                     userId: response.data.user.id,
    //                     phoneNumber: response.data.user.phone_number.toString().replace(
    //                         `${import.meta.env.VITE_CALLING_CODE}`,
    //                         ""
    //                     ),
    //                     userRole: response.data.role,
    //                 });
    //
    //                 if (response.data.role === 'doctor') {
    //                     authStore.updateUserDoctorData({
    //                         doctorId: response.data.doctor_data.doctor_id,
    //                         smfName: response.data.doctor_data.smf_name,
    //                     })
    //                 } else {
    //                     authStore.$reset();
    //                     window.location.href = `/patient/home`;
    //
    //                 }
    //             })
    //             .catch((error) => {
    //                 if (error?.response?.status === 401) {
    //                     authStore.$reset();
    //                     window.location.href = `/auth/login`;
    //                 }
    //             });
    //     });
    // }
});

export default router;
