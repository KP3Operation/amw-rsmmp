<script setup>
import DefaultAvatar from "@resources/static/images/avatar-default.png";
import SyncPhoto from "@resources/static/images/sinkronisasi.png";
import { useAuthStore } from "@shared/+store/auth.store.js";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import axios from "axios";
import * as bootstrap from 'bootstrap';
import { onMounted, reactive, ref } from 'vue';
import { calculateAge, updateMyProfileStore } from "@shared/utils/helpers.js";
import Header from "@shared/Components/Header/Header.vue";
import { storeToRefs } from "pinia";

const modalState = reactive({
    syncDataModal: null
});
const authStore = useAuthStore();
const { patientId, ssn, userFullName, phoneNumber, gender, userEmail, birthDate  } = storeToRefs(authStore);
const layoutStore = useLayoutStore();
const patientAge = ref(0);

const showSyncDataModal = () => {
    modalState.syncDataModal.show();
}

const syncData = () => {
    modalState.syncDataModal.hide();
    layoutStore.isFullView = true;
    axios.get("/api/v1/me/sync").then(() => {
        layoutStore.toggleSuccessAlert(t('profile.sync.success'));
    }).catch((error) => {
        layoutStore.toggleErrorAlert(t('profile.sync.failed'));
    }).finally(() => {
        layoutStore.isFullView = false;
        updateMyProfileStore();
    })
}

const logout = () => {
    axios.get('/api/v1/logout').then(() => {
        window.location.href = "/auth/login";
    }).catch((error) => {
        layoutStore.toggleErrorAlert(t('profile.logout_failed'));
    });
}

onMounted(() => {
    modalState.syncDataModal = new bootstrap.Modal("#modal-sinkronisasi", {});
    patientAge.value = calculateAge(birthDate.value);
});

</script>

<template>
    <div>
        <div v-if="!layoutStore.isFullView">
            <Header :title="$t('profile.title')"></Header>
            <div class="px-4 pt-8">
                <section class="profile-patient">
                    <img :src="DefaultAvatar" :alt="userFullName" width="49" height="49">

                    <div>
                        <p class="name">{{ userFullName }}</p>
                        <p>{{ gender }} {{ patientAge }} {{ $t('profile.year') }}</p>
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

                        <p class="text-end">{{ patientId }}</p>
                    </div>

                    <div class="d-flex align-items-center justify-content-between pb-3 border-bottom border-gray-400">
                        <p class="fs-5 text-gray-600">{{ $t('profile.ssn') }}</p>

                        <p class="text-end">{{ ssn == 0 ? '' : ssn }}</p>
                    </div>

                    <div class="d-flex align-items-center justify-content-between pb-3 border-bottom border-gray-400">
                        <p class="fs-5 text-gray-600">{{ $t('profile.phone_number') }}</p>

                        <p class="text-end">{{ phoneNumber }}</p>
                    </div>

                    <div class="d-flex align-items-center justify-content-between pb-3 border-bottom border-gray-400">
                        <p class="fs-5 text-gray-600">{{ $t('profile.birth_date') }}</p>

                        <p class="text-end">{{ birthDate }}</p>
                    </div>

                    <div class="d-flex align-items-center justify-content-between pb-3 border-bottom border-gray-400">
                        <p class="fs-5 text-gray-600">{{ $t('profile.gender') }}</p>

                        <p class="text-end">{{ gender }}</p>
                    </div>

                    <div class="d-flex align-items-center justify-content-between">
                        <p class="fs-5 text-gray-600">{{ $t('profile.email') }}</p>

                        <p class="text-end">{{ userEmail }}</p>
                    </div>
                </div>

                <div class="d-flex flex-column mt-4">
                    <button class="btn btn-green-700-rounded" type="button" @click="showSyncDataModal">{{
                        $t('profile.sync_data') }}</button>

                    <a href="#" @click="logout"
                        class="d-block text-red-500 fw-semibold text-center text-decoration-none mt-4">{{
                            $t('profile.logout')
                        }}</a>
                </div>
            </div>

            <div class="modal" id="modal-sinkronisasi" aria-labelledby="Sinkronisasi Modal" aria-hidden="true"
                tabindex="-1">
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

        <div class="bg-white d-flex align-items-center mt-4 h-100" v-if="layoutStore.isFullView">
            <div class="text-center">
                <img :src="SyncPhoto" alt="Ilustrasi Sinkronisasi" width="324" height="212" class="d-inline-block">

                <h1 class="mt-2 fs-4 fw-bold">{{ $t('profile.sync.title') }}</h1>
                <p class="mt-2 text-gray-700">{{ $t('profile.sync.subtitle') }}</p>
            </div>
        </div>
    </div>
</template>
