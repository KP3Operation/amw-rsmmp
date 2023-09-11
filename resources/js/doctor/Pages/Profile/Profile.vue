<script setup>
import { useAuthStore } from "@shared/+store/auth.store.js";
import DoctorPhoto from "@resources/static/images/doctor-photo.jpg";
import axios from "axios";

const authStore = useAuthStore();

const logout = () => {
    axios.get('/api/v1/logout').then(() => {
        authStore.$reset();
        window.location.href = "/auth/login";
    }).catch((error) => {
        // error while logout
    });
}
</script>

<template>
    <section class="profile-doctor pt-6">
        <img :src="DoctorPhoto" :alt="authStore.userFullName" width="49" height="49">

        <p class="fw-bold mt-4">{{ authStore.userFullName }}</p>
        <p class="mt-2 fs-6">DKT12345</p>
    </section>
    <div class="px-4 mt-4">
        <div class="d-flex justify-content-between align-items-center pb-3 border-bottom border-gray-400">
            <div class="w-50 d-flex col-gap-8 align-items-center">
                <i class="bi bi-phone-fill icon-blue-500 fs-3"></i>

                <span class="fs-6 text-gray-700">{{ $t('profile.phone_number') }}</span>
            </div>

            <p class="w-50 text-end fw-semibold">{{ authStore.phoneNumber }}</p>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 pb-3 border-bottom border-gray-400">
            <div class="w-50 d-flex col-gap-8">
                <i class="d-flex bi bi-person-vcard-fill icon-blue-500 fs-3"></i>

                <span class="fs-6 text-gray-700">{{ $t('profile.smf') }}</span>
            </div>

            <p class="w-50 text-end fw-semibold">Staff Medis Kardiologi</p>
        </div>

        <a href="#" @click="logout" class="d-block btn btn-outline-red-500 rounded-pill mt-4 text-decoration-none">{{
            $t('profile.logout') }}</a>
    </div>
</template>
