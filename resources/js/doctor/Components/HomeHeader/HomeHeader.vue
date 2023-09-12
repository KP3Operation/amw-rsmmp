<script setup>
import { useAuthStore } from "@shared/+store/auth.store.js";
import Logo from "@resources/static/images/logo.svg";
import { ref } from "vue";

const notifcationsCount = ref(99);

const authStore = useAuthStore();
</script>

<template>
    <div class="header header-dokter">
        <router-link to="/home">
            <img :src="Logo" alt="Logo Aviat" width="33" height="33">
        </router-link>
        <router-link to="/notification" class="notifikasi">
            <i class="bi bi-bell-fill fs-3"></i>

            <div class="info">{{ notifcationsCount }}+</div>
        </router-link>
    </div>
</template>
