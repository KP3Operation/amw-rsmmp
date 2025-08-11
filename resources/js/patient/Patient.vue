<script setup>
import { useLayoutStore } from "@shared/+store/layout.store.js";
import { storeToRefs } from "pinia";

const layoutStore = useLayoutStore();
const { patientActiveMenu,
    isFullView,
    showSuccessAlert,
    successAlertMessage,
    showErrorAlert,
    errorAlertMessage,
    showInfoAlert,
    infoAlertMessage
} = storeToRefs(layoutStore);

</script>

<template>
    <nav v-if="!isFullView">
        <router-link to="/home" :class="(patientActiveMenu === 'home') ? 'active' : ''">
            <i class="bi bi-house-door-fill"></i>
            <p>{{ $t('menus.home') }}</p>
        </router-link>
        <router-link to="/history" :class="(patientActiveMenu === 'history') ? 'active' : ''">
            <i class="bi bi-clipboard-heart-fill"></i>
            <p>{{ $t('menus.history') }}</p>
        </router-link>
        <router-link to="/appointment" :class="(patientActiveMenu === 'appointment') ? 'active' : ''">
            <i class="bi bi-calendar-event-fill"></i>
            <p>{{ $t('menus.appointment') }}</p>
        </router-link>
        <router-link to="/profile" :class="(patientActiveMenu === 'profile') ? 'active' : ''">
            <i class="bi bi-person-circle"></i>
            <p>{{ $t('menus.profile') }}</p>
        </router-link>
    </nav>

    <router-view>
</router-view>

    <div :class="showSuccessAlert ? '' : 'd-none'"
        class="alert alert-success alert-dismissible d-flex col-gap-16 shadow fs-5 fw-semibold mt-6" role="alert">
        <p>{{ successAlertMessage }}</p>

        <button type="button" class="btn-close" @click="layoutStore.toggleSuccessAlert('')" aria-label="Close">
            <i class="bi bi-x icon-red-600 fs-2 fw-bold"></i>
        </button>
    </div>
    <div :class="showErrorAlert ? '' : 'd-none'"
        class="alert alert-danger alert-dismissible d-flex col-gap-16 shadow fs-5 fw-semibold mt-6" role="alert">
        <p>{{ errorAlertMessage }}</p>

        <button type="button" class="btn-close" @click="layoutStore.toggleErrorAlert('')" aria-label="Close">
            <i class="bi bi-x icon-red-600 fs-2 fw-bold"></i>
        </button>
    </div>
    <div :class="showInfoAlert ? '' : 'd-none'"
        class="alert alert-blue alert-dismissible d-flex col-gap-16 shadow fs-5 fw-semibold mt-6" role="alert">
        <p>{{ infoAlertMessage }}</p>

        <button type="button" class="btn-close" @click="layoutStore.toggleInfoAlert('')" aria-label="Close">
            <i class="bi bi-x icon-red-600 fs-2 fw-bold"></i>
        </button>
    </div>
</template>
