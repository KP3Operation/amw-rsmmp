<script setup>
import { useAuthStore } from "@shared/+store/auth.store.js";
import LogoWhite from "@resources/static/images/logo-white.svg";

const authStore = useAuthStore();
</script>

<template>
    <div class="header header-pasien">
        <router-link to="/home">
            <img :src="LogoWhite" alt="Logo Aviat" width="33" height="33">
        </router-link>

        <p>{{ $t('header.greeting') }}, <span>{{ authStore.userFullName }}</span></p>
    </div>
</template>
