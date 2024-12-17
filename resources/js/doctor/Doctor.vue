<script setup>
import { useLayoutStore } from "@shared/+store/layout.store.js";
import { storeToRefs } from "pinia";
import {onMounted, ref} from "vue";

const layoutStore = useLayoutStore();
const { showSuccessAlert,
    successAlertMessage,
    showErrorAlert,
    errorAlertMessage,showDoctorFee } = storeToRefs(layoutStore);

onMounted(() => {
    
});
</script>

<template>
    <nav>
        <router-link to="/home" :class="(layoutStore.doctorActiveMenu === 'home') ? 'active' : ''">
            <i class="bi bi-house-door-fill"></i>
            <p>{{ $t('menus.home') }}</p>
        </router-link>
        <router-link to="/fee" :class="(layoutStore.doctorActiveMenu === 'fee') ? 'active' : ''" v-if="layoutStore.showDoctorFee">
            <svg width="24" height="20" viewBox="0 0 24 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M3.25 2.5C1.87109 2.5 0.75 3.62109 0.75 5V15C0.75 16.3789 1.87109 17.5 3.25 17.5H20.75C22.1289 17.5 23.25 16.3789 23.25 15V5C23.25 3.62109 22.1289 2.5 20.75 2.5H3.25ZM5.75 15H3.25V12.5C4.62891 12.5 5.75 13.6211 5.75 15ZM3.25 7.5V5H5.75C5.75 6.37891 4.62891 7.5 3.25 7.5ZM18.25 15C18.25 13.6211 19.3711 12.5 20.75 12.5V15H18.25ZM20.75 7.5C19.3711 7.5 18.25 6.37891 18.25 5H20.75V7.5ZM7.625 10C7.625 9.42547 7.73816 8.85656 7.95803 8.32576C8.17789 7.79496 8.50015 7.31266 8.90641 6.90641C9.31266 6.50015 9.79496 6.17789 10.3258 5.95803C10.8566 5.73816 11.4255 5.625 12 5.625C12.5745 5.625 13.1434 5.73816 13.6742 5.95803C14.205 6.17789 14.6873 6.50015 15.0936 6.90641C15.4998 7.31266 15.8221 7.79496 16.042 8.32576C16.2618 8.85656 16.375 9.42547 16.375 10C16.375 10.5745 16.2618 11.1434 16.042 11.6742C15.8221 12.205 15.4998 12.6873 15.0936 13.0936C14.6873 13.4998 14.205 13.8221 13.6742 14.042C13.1434 14.2618 12.5745 14.375 12 14.375C11.4255 14.375 10.8566 14.2618 10.3258 14.042C9.79496 13.8221 9.31266 13.4998 8.90641 13.0936C8.50015 12.6873 8.17789 12.205 7.95803 11.6742C7.73816 11.1434 7.625 10.5745 7.625 10ZM10.5937 8.125C10.5937 8.50391 10.8633 8.81641 11.2187 8.89063V10.7812H11.0625C10.6328 10.7812 10.2812 11.1328 10.2812 11.5625C10.2812 11.9922 10.6328 12.3438 11.0625 12.3438H12.9375C13.3672 12.3438 13.7187 11.9922 13.7187 11.5625C13.7187 11.1328 13.3672 10.7812 12.9375 10.7812H12.7812V8.125C12.7812 7.69531 12.4297 7.34375 12 7.34375H11.375C10.9453 7.34375 10.5937 7.69531 10.5937 8.125Z"
                    fill="#6C757D" />
            </svg>
            <p>{{ $t('menus.fee') }}</p>
        </router-link>
        <router-link to="/appointment" :class="(layoutStore.doctorActiveMenu === 'appointment') ? 'active' : ''">
            <i class="bi bi-calendar-event-fill"></i>
            <p>{{ $t('menus.appointment') }}</p>
        </router-link>
        <router-link to="/profile" :class="(layoutStore.doctorActiveMenu === 'profile') ? 'active' : ''">
            <i class="bi bi-person-circle"></i>
            <p>{{ $t('menus.profile') }}</p>
        </router-link>
    </nav>

    <router-view></router-view>

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
</template>
