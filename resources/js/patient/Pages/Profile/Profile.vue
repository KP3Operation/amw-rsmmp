<script setup>
import DefaultAvatar from "@resources/static/images/avatar-default.png";
import SyncPhoto from "@resources/static/images/sinkronisasi.png";
import { useAuthStore } from "@shared/+store/auth.store.js";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import * as bootstrap from 'bootstrap';
import {onMounted, reactive, ref, watch} from 'vue';
import { calculateAge, updateMyProfileStore } from "@shared/utils/helpers.js";
import Header from "@shared/Components/Header/Header.vue";
import { storeToRefs } from "pinia";
import apiRequest from "@shared/utils/axios.js";

const modalState = reactive({
    syncDataModal: null,
    logoutConfirmationModal: null,
    deleteAccountConfirmationModal: null
});
const authStore = useAuthStore();
const { userData, userPatientData } = storeToRefs(authStore);
const layoutStore = useLayoutStore();
const patientAge = ref(0);

const showSyncDataModal = () => {
    modalState.syncDataModal.show();
}

const syncData = () => {
    modalState.syncDataModal.hide();
    layoutStore.isFullView = true;
    apiRequest.get("/api/v1/me/sync").then(() => {
        layoutStore.toggleSuccessAlert(t('profile.sync.success'));
    }).catch((error) => {
        layoutStore.toggleErrorAlert(t('profile.sync.failed'));
    }).finally(() => {
        layoutStore.isFullView = false;
        updateMyProfileStore();
    })
}

const logout = () => {
    apiRequest.get('/api/v1/logout').then(() => {
        window.location.href = "/auth/login";
    }).catch((error) => {
        layoutStore.toggleErrorAlert(t('profile.logout_failed'));
    });
}
const deleteAccount = () => {
    apiRequest.get('/api/v1/deleteAccount').then(() => {
        authStore.$reset();
        window.location.href = "/auth/login";
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    });
}

watch(userPatientData.value, (newValue, oldValue) => {
    patientAge.value = calculateAge(newValue['birthDate']);
});

onMounted(() => {
    modalState.syncDataModal = new bootstrap.Modal("#modal-sinkronisasi", {});
    modalState.logoutConfirmationModal = new bootstrap.Modal("#logout-modal", {});
    modalState.deleteAccountConfirmationModal = new bootstrap.Modal("#deleteAccount-modal", {});
    patientAge.value = calculateAge(userPatientData.value.birthDate);
});

</script>

<template>
    <div v-if="!layoutStore.isFullView">
        <Header :title="$t('profile.title')" :with-action-btn="true">
            <router-link to="/profile/delete" class="text-decoration-none icon-red-500">
                <i class="bi bi-person-x fs-2" title="Delete Account"></i>
            </router-link>
        </Header>
        <div class="px-4 pt-8">
            <section class="profile-patient">
                <img :src="DefaultAvatar" :alt="userData.userFullName" width="49" height="49">

                <div>
                    <p class="name">{{ userData.userFullName }}</p>
                    <p>{{ userPatientData.gender }} {{ patientAge }} {{ $t('profile.year') }}</p>
                </div>
            </section>

            <div class="mt-4 d-flex flex-column rows-gap-16">
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="fs-3 fw-bold">{{ $t('profile.subtitle') }}</h2>

                    <router-link to="/profile/edit" class="text-decoration-none icon-blue-500">
                        <i class="bi bi-pencil-fill fs-3"></i>
                    </router-link>
                </div>

                <div class="d-flex align-items-center justify-content-between pb-3 border-bottom border-gray-400">
                    <p class="fs-5 text-gray-600">{{ $t('profile.patient_id') }}</p>

                    <p class="text-end">{{ userPatientData.patientId }}</p>
                </div>

                <div class="d-flex align-items-center justify-content-between pb-3 border-bottom border-gray-400">
                    <p class="fs-5 text-gray-600">{{ $t('profile.ssn') }}</p>

                    <p class="text-end">{{ userPatientData.ssn == 0 ? '' : userPatientData.ssn }}</p>
                </div>

                <div class="d-flex align-items-center justify-content-between pb-3 border-bottom border-gray-400">
                    <p class="fs-5 text-gray-600">{{ $t('profile.phone_number') }}</p>

                    <p class="text-end">{{ userData.phoneNumber }}</p>
                </div>

                <div class="d-flex align-items-center justify-content-between pb-3 border-bottom border-gray-400">
                    <p class="fs-5 text-gray-600">{{ $t('profile.birth_date') }}</p>

                    <p class="text-end">{{ userPatientData.birthDate }}</p>
                </div>

                <div class="d-flex align-items-center justify-content-between pb-3 border-bottom border-gray-400">
                    <p class="fs-5 text-gray-600">{{ $t('profile.gender') }}</p>

                    <p class="text-end">{{ userPatientData.gender }}</p>
                </div>

                <div class="d-flex align-items-center justify-content-between">
                    <p class="fs-5 text-gray-600">{{ $t('profile.email') }}</p>

                    <p class="text-end">{{ userData.userEmail }}</p>
                </div>
            </div>

            <div class="d-flex flex-column mt-4">
                <button class="btn btn-green-700-rounded" type="button" @click="showSyncDataModal">{{
                    $t('profile.sync_data') }}</button>

                <a href="javascript:void(0);" @click="modalState.logoutConfirmationModal.show()"
                    class="d-block text-red-500 fw-semibold text-center text-decoration-none mt-4">{{
                        $t('profile.logout')
                    }}</a>
            </div>
        </div>

        <div class="modal" id="modal-sinkronisasi" aria-labelledby="Sinkronisasi Modal" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between">
                        <div class="d-flex align-items-center col-gap-8">
                            <i class="bi bi-info-circle-fill icon-blue-500 fs-3"></i>

                            <h5 class="modal-title">{{ $t('profile.sync_modal.title') }}</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x fs-2 icon-black"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{ $t('profile.sync_modal.message') }}</p>
                    </div>
                    <div class="modal-footer d-flex flex-nowrap">
                        <button type="button" class="w-50 btn btn-link m-0" data-bs-dismiss="modal">{{
                            $t('profile.sync_modal.cancel') }}</button>
                        <button type="button" @click="syncData" class="w-50 btn-sinkronisasi btn btn-blue m-0">Ya
                            {{ $t('profile.sync_modal.sync') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-none alert alert-dismissible d-flex col-gap-16 shadow fs-5 fw-semibold mt-6" role="alert">
            <p></p>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="bi bi-x icon-red-600 fs-2 fw-bold"></i>
            </button>
        </div>
    </div>

    <div class="bg-white d-flex align-items-center mt-8 h-100" v-if="layoutStore.isFullView">
        <div class="text-center">
            <img :src="SyncPhoto" alt="Ilustrasi Sinkronisasi" width="324" height="212" class="d-inline-block">

            <h1 class="mt-2 fs-4 fw-bold">{{ $t('profile.sync.title') }}</h1>
            <p class="mt-2 text-gray-700">{{ $t('profile.sync.subtitle') }}</p>
        </div>
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
                    <p>{{ $t('profile.logout_modal.description') }}</p>
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
    
    <div class="modal" id="deleteAccount-modal" aria-labelledby="Delete Account Modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div class="d-flex align-items-center col-gap-8">
                        <i class="bi bi-info-circle-fill icon-blue-500 fs-3"></i>
                        <h5 class="modal-title">{{ $t('profile.deleteAccount_modal.title') }}</h5>
                    </div>
                    <button type="button" class="btn-close" aria-label="Close"
                            @click="modalState.deleteAccountConfirmationModal.hide()">
                        <i class="bi bi-x fs-2 icon-black"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ $t('profile.deleteAccount_modal.message') }}</p>
                </div>
                <div class="modal-footer flex-nowrap justify-content-between">
                    <button type="button" class="w-70 btn btn-blue" @click="modalState.deleteAccountConfirmationModal.hide()">
                        {{ $t('profile.deleteAccount_modal.cancel') }}
                    </button>
                    <button type="button" @click="deleteAccount" class="w-30 btn-masuk btn btn-outline-red-500 rounded-pill">
                        {{ $t('profile.deleteAccount_modal.yes') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
