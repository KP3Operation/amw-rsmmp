<script setup>
import { useAuthStore } from "@shared/+store/auth.store.js";
import DefaultAvatar from "@resources/static/images/avatar-default.png";
import Header from "@shared/Components/Header/Header.vue";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import { storeToRefs } from "pinia";
import {onMounted, reactive} from "vue";
import * as bootstrap from "bootstrap";
import apiRequest from "@shared/utils/axios.js";

const authStore = useAuthStore();
const { userData, userDoctorData } = storeToRefs(authStore);
const layoutStore = useLayoutStore();
const modalState = reactive({
    logoutConfirmationModal: null
});

const logout = () => {
    apiRequest.get('/api/v1/logout').then(() => {
        authStore.$reset();
        window.location.href = "/auth/login";
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    });
}

onMounted(() => {
    modalState.logoutConfirmationModal = new bootstrap.Modal("#logout-modal", {});
});
</script>

<template>
    <Header :title="$t('profile.title')" :with-back-url="false"></Header>
    <section class="profile-doctor pt-6">
        <img :src="DefaultAvatar" :alt="userData.userFullName" width="49" height="49">

        <p class="fw-bold mt-4">{{ userData.userFullName }}</p>
        <p class="mt-2 fs-6">{{ userDoctorData.doctorId }}</p>
    </section>
    <div class="px-4 mt-4">
        <div class="d-flex justify-content-between align-items-center pb-3 border-bottom border-gray-400">
            <div class="w-50 d-flex col-gap-8 align-items-center">
                <i class="bi bi-phone-fill icon-blue-500 fs-3"></i>

                <span class="fs-6 text-gray-700">{{ $t('profile.phone_number') }}</span>
            </div>

            <p class="w-50 text-end fw-semibold">{{ userData.phoneNumber }}</p>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4 pb-3 border-bottom border-gray-400">
            <div class="w-50 d-flex col-gap-8">
                <i class="d-flex bi bi-person-vcard-fill icon-blue-500 fs-3"></i>

                <span class="fs-6 text-gray-700">{{ $t('profile.smf') }}</span>
            </div>

            <p class="w-50 text-end fw-semibold">{{ userDoctorData.smfName }}</p>
        </div>

        <a href="javascript:void(0);" @click="modalState.logoutConfirmationModal.show()" class="d-block btn btn-outline-red-500 rounded-pill mt-4 text-decoration-none">{{
            $t('profile.logout') }}</a>
    </div>

    <div class="modal" id="logout-modal" aria-labelledby="Logout Modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div class="d-flex align-items-center col-gap-8">
                        <i class="bi bi-info-circle-fill icon-blue-500 fs-3"></i>
                        <h5 class="modal-title">{{ $t('profile.logout_modal.title') }}</h5>
                    </div>
                    <button type="button" class="btn-close" aria-label="Close"
                            @click="modalState.logoutConfirmationModal.hide()">
                        <i class="bi bi-x fs-2 icon-black"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ $t('profile.logout_modal.message') }}</p>
                </div>
                <div class="modal-footer flex-nowrap">
                    <button type="button" class="w-50 btn btn-link" @click="modalState.logoutConfirmationModal.hide()">
                        {{ $t('profile.logout_modal.cancel') }}
                    </button>
                    <button type="button" @click="logout" class="w-50 btn-masuk btn btn-blue">
                        {{ $t('profile.logout_modal.yes') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
