<script>
import { useAppointmentStore } from "@doctor/+store/appointment.store.js";
import { useNotificationStore } from "@doctor/+store/notification.store.js";
import router from "@doctor/router.js";
import { useAuthStore } from "@shared/+store/auth.store.js";
import Header from "@shared/Components/Header/Header.vue";
import apiRequest from "@shared/utils/axios.js";
import { mapActions, mapState } from "pinia";

export default {
    name: "Notifications",
    components: { Header },
    computed: {
        ...mapState(useNotificationStore, {
            count: "count",
            notifications: "notifications",
        }),
        ...mapState(useAppointmentStore, {
            selectedDate: "selectedDate",
        }),
        ...mapState(useAuthStore, {
            userDoctorData: "userDoctorData",
        }),
    },
    methods: {
        ...mapActions(useNotificationStore, {
            updateCount: "updateCount",
            updateNotifications: "updateNotifications",
        }),
        ...mapActions(useAppointmentStore, {
            updateSelectedDate: "updateSelectedDate",
        }),
        getNotifications() {
            apiRequest
                .get("/api/v1/doctor/notifications", {
                    params: { doctor_id: this.userDoctorData.doctorId },
                })
                .then((response) => {
                    const data = response.data;
                    this.updateCount(data.count);
                    this.updateNotifications(data.notifications);
                })
                .catch(() => {})
                .finally(() => {});
        },
        markAsRead(notification) {
            apiRequest
                .put(`/api/v1/doctor/notifications/${notification.id}`)
                .then((response) => {
                    if (notification.context === "3") {
                        this.updateSelectedDate(notification.appointment_date);
                        router.push({ name: "AppointmentPage" });
                    }
                });
        },
    },
    mounted() {
        this.getNotifications();
    },
};
</script>

<template>
    <Header :title="$t('notification.title')" :with-back-url="true"></Header>
    <div class="d-flex flex-column rows-gap-16 px-4 pt-8" v-if="count < 1">
        <div class="text-center mt-5">
            <img
                src="@resources/static/images/not-found.png"
                alt="Ilustrasi Not Found"
                width="280"
                height="209"
                class="d-inline-block"
            />
            <p class="fw-bold fs-4 mt-3">{{ $t('notification.no_notification') }}</p>
        </div>
    </div>
    <main class="bg-gray-100 pb-7 pt-8">
        <div class="d-flex flex-column rows-gap-16 px-4 pt-4" v-if="count > 0">
            <div
                class="d-flex col-gap-20 bg-white rounded-3 px-4 py-3"
                v-for="notification in notifications"
            >
                <div>
                    <p>{{ notification.message }}</p>
                    <p class="fs-6 mt-2 text-gray-700">
                        {{ $convertDateTimeToDateTime(notification.created_at) }}
                    </p>
                </div>
                <a
                    href="#"
                    @click="markAsRead(notification)"
                    class="d-flex align-items-center text-blue-500 text-end text-decoration-none fw-semibold"
                    >Lihat</a
                >
            </div>
        </div>
    </main>
</template>
