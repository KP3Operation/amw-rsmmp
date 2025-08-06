import { createRouter, createWebHistory } from "vue-router";

import AppointmentPage from "@patient/Pages/Appointment/Appointment.vue";
import CreateAppointmentPage from "@patient/Pages/Appointment/CreateAppointment.vue";
import DoctorSchedulePage from "@patient/Pages/DoctorSchedule/DoctorSchedule.vue";
import DoctorScheduleDetailPage from "@patient/Pages/DoctorSchedule/DoctorScheduleDetail.vue";
import EditProfilePage from "@patient/Pages/EditProfile/EditProfile.vue";
import DataConfirmationPage from "@patient/Pages/Family/DataConfirmation.vue";
import FamilyPage from "@patient/Pages/Family/Family.vue";
import UpsertFamilyPage from "@patient/Pages/Family/UpsertFamily.vue";
import EncounterDetailPage from "@patient/Pages/History/EncounterDetail.vue";
import HistoryPage from "@patient/Pages/History/History.vue";
import LabResultDetailPage from "@patient/Pages/History/LabResultDetail.vue";
import LabResultViewPage from "@patient/Pages/History/LabResultView.vue";
import RadResultDetailPage from "@patient/Pages/History/RadResultDetail.vue";
import RadResultViewPage from "@patient/Pages/History/RadResultView.vue";

import PrescriptionDetailPage from "@patient/Pages/History/PrescriptionDetail.vue";
import HomePage from "@patient/Pages/Home/Home.vue";
import ProfilePage from "@patient/Pages/Profile/Profile.vue";
import DeleteAccountPage from "@patient/Pages/Profile/AccountDelete.vue";
import { useAuthStore } from "@shared/+store/auth.store.js";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import NotFoundPage from "@shared/Pages/NotFound/NotFound.vue";

const routes = [
    {
        path: "/home",
        name: "HomePage",
        component: HomePage,
    },
    {
        path: "/appointment/create",
        name: "CreateAppointmentPage",
        component: CreateAppointmentPage,
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
        path: "/history/labresultview",
        name: "LabResultViewPage",
        component: LabResultViewPage,
    },
    {
        path: "/history/radresult",
        name: "RadResultDetailPage",
        component: RadResultDetailPage,
    },
    {
        path: "/history/radresultview",
        name: "RadResultViewPage",
        component: RadResultViewPage,
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
        path: "/profile/delete",
        name: "DeleteAccountPage",
        component: DeleteAccountPage,
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

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();
    const layoutStore = useLayoutStore();

    if (
        authStore.userData.phoneNumber === null ||
        authStore.userData.userId === 0
    ) {
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
            }

            if (authStore.userData.userRole !== "patient") {
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

    if (to.name === "DeleteAccountPage") {
        layoutStore.patientActiveMenu = "Delete Account";
        layoutStore.isFullView = true;
    }

    next();
});

export default router;
