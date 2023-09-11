<script setup>
import HeaderComponent from "@patient/Components/Header/Header.vue";
import ProfileHeaderComponent from "@shared/Components/ProfileHeader/ProfileHeader.vue";
import { useLayoutStore } from "@shared/+store/layout.store.js";

const layoutStore = useLayoutStore();

</script>

<template>
    <div>
        <nav v-if="!layoutStore.isFullView">
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

        <div v-if="!layoutStore.isFullView">
            <HeaderComponent v-if="layoutStore.patientActiveMenu === 'home'" />
            <HeaderComponent v-if="layoutStore.patientActiveMenu === 'history'" />
            <HeaderComponent v-if="layoutStore.patientActiveMenu === 'appointment'" />
            <ProfileHeaderComponent v-if="layoutStore.patientActiveMenu === 'profile'" />
        </div>

        <router-view></router-view>

        <div :class="layoutStore.showSuccessAlert ? '' : 'd-none'"
            class="alert alert-success alert-dismissible d-flex col-gap-16 shadow fs-5 fw-semibold mt-6" role="alert">
            <p>{{ layoutStore.successAlertMessage }}</p>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="bi bi-x icon-red-600 fs-2 fw-bold"></i>
            </button>
        </div>
    </div>
</template>
