import { createRouter, createWebHistory } from "vue-router";

import HomePage from "@patient/Pages/Home/Home.vue";
import AppointmentPage from "@patient/Pages/Appointment/Appointment.vue";
import HistoryPage from "@patient/Pages/History/History.vue";
import ProfilePage from "@patient/Pages/Profile/Profile.vue";
import EditProfilePage from "@patient/Pages/EditProfile/EditProfile.vue";
import FamilyPage from "@patient/Pages/Family/Family.vue";
import UpsertFamilyPage from "@patient/Pages/Family/UpsertFamily.vue";
import DataConfirmationPage from "@patient/Pages/Family/DataConfirmation.vue";
import NotFoundPage from "@shared/Pages/NotFound/NotFound.vue";
import PrescriptionDetailPage from "@patient/Pages/History/PrescriptionDetail.vue";
import { useAuthStore } from "@shared/+store/auth.store.js";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import LabResultDetailPage from "@patient/Pages/History/LabResultDetail.vue";
import EncounterDetailPage from "@patient/Pages/History/EncounterDetail.vue";
import DoctorSchedulePage from "@patient/Pages/DoctorSchedule/DoctorSchedule.vue";
import DoctorScheduleDetailPage from "@patient/Pages/DoctorSchedule/DoctorScheduleDetail.vue";

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
        path: "/history/labresult",
        name: "LabResultDetailPage",
        component: LabResultDetailPage,
    },
    {
        path: "/history/prescriptions",
        name: "PrescriptionDetailPage",
        component: PrescriptionDetailPage,
    },
    {
        path: "/history/encounters",
        name: "EncounterDetailsPage",
        component: EncounterDetailPage,
    },
    {
        path: "/history",
        name: "HistoryPage",
        component: HistoryPage,
    },

    {
        path: "/doctor/schedules/detail",
        name: "DoctorScheduleDetailPage",
        component: DoctorScheduleDetailPage,
    },
    {
        path: "/doctor/schedules",
        name: "DoctorSchedulePage",
        component: DoctorSchedulePage,
    },
    {
        path: "/profile/edit",
        name: "EditProfilePage",
        component: EditProfilePage,
    },
    {
        path: "/profile",
        name: "ProfilePage",
        component: ProfilePage,
    },
    {
        path: "/family/edit/:id",
        name: "UpdateFamilyPage",
        component: UpsertFamilyPage,
    },
    {
        path: "/family/confirm/:id",
        name: "FamilyDataConfirmationPage",
        component: DataConfirmationPage,
    },
    {
        path: "/family/create",
        name: "CreateFamilyPage",
        component: UpsertFamilyPage,
    },
    {
        path: "/family",
        name: "FamilyPage",
        component: FamilyPage,
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
        layoutStore.isFullView = false;
    }

    if (to.name === "HistoryPage") {
        layoutStore.patientActiveMenu = "history";
        layoutStore.isFullView = false;
    }

    if (to.name === "AppointmentPage") {
        layoutStore.patientActiveMenu = "appointment";
        layoutStore.isFullView = false;
    }

    if (to.name === "ProfilePage") {
        layoutStore.patientActiveMenu = "profile";
        layoutStore.isFullView = false;
    }

    if (to.name === "EditProfilePage") {
        layoutStore.patientActiveMenu = "profile";
        layoutStore.isFullView = true;
    }

    if (authStore.phoneNumber === null || authStore.userId === 0) {
        axios.get("/sanctum/csrf-cookie").then(() => {
            axios
                .get(`/api/v1/me`)
                .then((response) => {
                    authStore.userFullName = response.data.user.name;
                    authStore.userEmail = response.data.user.email;
                    authStore.userId = response.data.user.id;
                    authStore.phoneNumber =
                        response.data.user.phone_number.replace(
                            `${import.meta.env.VITE_CALLING_CODE}`,
                            ""
                        );
                    authStore.userRole = response.data.role;

                    // patient data
                    if (response.data.role === 'patient') {
                        authStore.ssn = response.data.patient_data.ssn;
                        authStore.patientId = response.data.patient_data.patient_id;
                        authStore.birthDate = response.data.patient_data.birth_date;
                        authStore.gender = response.data.patient_data.gender;
                    }

                    if (authStore.userRole !== "patient") {
                        authStore.$reset();
                        window.location.href = `/doctor/home`;
                    }
                })
                .catch((error) => {
                    if (error?.response?.status === 401) {
                        authStore.$reset();
                        window.location.href = `/auth/login`;
                    }
                });
        });
    }


});

export default router;
