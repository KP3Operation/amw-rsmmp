<script setup>
import Header from "@shared/Components/Header/Header.vue";
import NoNotifications from "@resources/static/images/not-found.png";
import { useNotificationStore } from "@doctor/+store/notification.store.js";
import { storeToRefs } from "pinia";

const notificationStore = useNotificationStore();
const { count } = storeToRefs(notificationStore);
</script>

<template>
    <Header :title="$t('notification.title')" :with-back-url="true"></Header>

    <div class="d-flex flex-column rows-gap-16 px-4 pt-8" v-if="count < 1">
        <div class="text-center mt-5">
            <img :src="NoNotifications" alt="Ilustrasi Not Found" width="280" height="209" class="d-inline-block">
            <p class="fw-bold fs-4 mt-3">Anda Tidak Memiliki Notifikasi</p>
        </div>
    </div>
</template>
