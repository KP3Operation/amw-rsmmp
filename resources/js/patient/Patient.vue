<script setup>
import HeaderComponent from "@patient/Components/Header/Header.vue";
import { useLayoutStore } from "@shared/+store/layout.store.js";

const layoutStore = useLayoutStore();

</script>

<template>
    <div>
        <nav>
            <router-link to="/home" :class="(layoutStore.patientActiveMenu === 'home') ? 'active' : ''">
                <i class="bi bi-house-door-fill"></i>
                <p>{{ $t('menus.home') }}</p>
            </router-link>
            <router-link to="/history" :class="(layoutStore.patientActiveMenu === 'history') ? 'active' : ''">
                <i class="bi bi-clipboard-heart-fill"></i>
                <p>{{ $t('menus.history') }}</p>
            </router-link>
            <router-link to="/appointment" :class="(layoutStore.patientActiveMenu === 'appointment') ? 'active' : ''">
                <i class="bi bi-calendar-event-fill"></i>
                <p>{{ $t('menus.appointment') }}</p>
            </router-link>
            <router-link to="/profile" :class="(layoutStore.patientActiveMenu === 'profile') ? 'active' : ''">
                <i class="bi bi-person-circle"></i>
                <p>{{ $t('menus.profile') }}</p>
            </router-link>
        </nav>

        <HeaderComponent />

        <router-view></router-view>
    </div>
</template>
