<script setup>
import { useAuthStore } from "@shared/+store/auth.store.js";
import Logo from "@resources/static/images/logo.svg";
import {useNotificationStore} from "@doctor/+store/notification.store.js";
import {storeToRefs} from "pinia";
import axios from "axios";
import {onMounted, watch} from "vue";
import apiRequest from "@shared/utils/axios.js";

const notificationStore = useNotificationStore();
const { count } = storeToRefs(notificationStore);
const authStore = useAuthStore();
const {doctorId} = storeToRefs(authStore);

const fetchNotifications = () => {
  apiRequest.get('/api/v1/doctor/notifications', {
    params: {doctor_id: doctorId.value}
  }).then((response) => {
    const data = response.data;
    notificationStore.updateCount(data.count);
    notificationStore.updateNotifications(data.notifications);
  }).catch(() => {}).finally(() => {});
}

watch(doctorId, (newValue, oldValue) => {
  fetchNotifications();
});

onMounted(() => {
  fetchNotifications();
});

</script>

<template>
    <div class="header header-dokter">
        <router-link to="/home">
            <img :src="Logo" alt="Logo Aviat" width="33" height="33">
        </router-link>
        <router-link to="/notification" class="notifikasi">
            <i class="bi bi-bell-fill fs-3"></i>

            <div class="info" v-if="count > 0">{{ count }}+</div>
        </router-link>
    </div>
</template>
