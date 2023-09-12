<script setup>
import LogoWhite from "@resources/static/images/logo-white.svg";
import { useAuthStore } from "@shared/+store/auth.store.js";
import { getUserFirstName } from "@shared/utils/helpers.js";

const authStore = useAuthStore();
</script>

<template>
    <div>
        <div class="header header-pasien">
            <router-link to="/home">
                <img :src="LogoWhite" alt="Logo Aviat" width="33" height="33">
            </router-link>

            <p>{{ $t('header.greeting') }}, <span v-text="getUserFirstName(authStore.userFullName)"></span></p>
        </div>
    </div>
    <h1>Home</h1>
</template>
