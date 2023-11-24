import { createRouter, createWebHistory } from "vue-router";

import ConfirmationPage from "@auth/Pages/Confirmation/Confirmation.vue";
import LoginPage from "@auth/Pages/Login/Login.vue";
import NotFoundPage from "@auth/Pages/NotFound/NotFound.vue";
import RegisterPage from "@auth/Pages/Register/Register.vue";
import VerificationPage from "@auth/Pages/Verification/Verification.vue";
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

    if (to.name === "LoginPage" || to.name === "RegisterPage") {
        try {
            await axios.get("/sanctum/csrf-cookie");

            const response = await axios.get(`/api/v1/me`);
            authStore.updateUserData({
                userFullName: response.data.user.name,
                userEmail: response.data.user.email,
                userId: response.data.user.id,
                phoneNumber: response.data.user.phone_number
                    .toString()
                    .replace(`${import.meta.env.VITE_CALLING_CODE}`, ""),
                userRole: response.data.role,
            });

            // Update patient data if user role is 'patient'
            if (response.data.role === "patient") {
                authStore.updateUserPatientData({
                    ssn: response.data.patient_data.ssn,
                    patientId: response.data.patient_data.patient_id,
                    birthDate: response.data.patient_data.birth_date,
                    gender: response.data.patient_data.gender,
                    medicalNo: response.data.patient_data.medical_no,
                });
                window.location.href = `/patient/home`;
            }

            if (response.data.role === "doctor") {
                authStore.updateUserDoctorData({
                    doctorId: response.data.doctor_data.doctor_id,
                    smfName: response.data.doctor_data.smf_name,
                });
                window.location.href = `/doctor/home`;
            }
        } catch (error) {
            //
        }
    }

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
