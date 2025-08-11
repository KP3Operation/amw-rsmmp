<script setup>
import DefaultAvatar from "@resources/static/images/avatar-default.png";
import accountDeletePhoto from "@resources/static/images/account-delete.png";
import { useAuthStore } from "@shared/+store/auth.store.js";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import * as bootstrap from 'bootstrap';
import {onMounted, reactive, ref, watch} from 'vue';
import { calculateAge, updateMyProfileStore } from "@shared/utils/helpers.js";
import Header from "@shared/Components/Header/Header.vue";
import { storeToRefs } from "pinia";
import apiRequest from "@shared/utils/axios.js";

const modalState = reactive({
    deleteAccountConfirmationModal: null
});
const authStore = useAuthStore();
const { userData, userPatientData } = storeToRefs(authStore);
const layoutStore = useLayoutStore();

const deleteAccount = () => {
    apiRequest.get('/api/v1/deleteAccount').then(() => {
        authStore.$reset();
        window.location.href = "/auth/login";
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    });
}

onMounted(() => {
    modalState.deleteAccountConfirmationModal = new bootstrap.Modal("#deleteAccount-modal", {});
});

</script>

<template>

    <div class="bg-white d-flex align-items-center h-100" v-if="layoutStore.isFullView">
        <Header :title="$t('profile.account_delete.title')" :with-action-btn="true">
            <button id="btn-akan-datang" @click="modalState.familyMemberFilterModal.show();"
                class="btn d-flex col-gap-8 align-items-center fw-bold text-blue-500 p-0">
            </button>
        </Header>
    </div>
    <div class="bg-white d-flex align-items-center h-100 m-4" v-if="layoutStore.isFullView">
        <div class="text-center mt-8 row">
            <div class="col">
                <img :src="accountDeletePhoto" alt="Account Delete Ilustrasi" width="324" height="212" class="d-inline-block">
                <h1 class="mt-2  fs-3 fw-bold">{{ $t('profile.account_delete.subtitle') }}</h1>
                <p class="mt-2 text-danger fw-bold">{{ $t('profile.account_delete.description') }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white align-items-center h-100 m-4" v-if="layoutStore.isFullView">
        <div class="mt-4 row">
            <div class="col">
                <router-link to="/profile" class="d-block btn btn-blue rounded-pill">
                    {{ $t('profile.account_delete.cancel') }}
                </router-link>
            </div>
        </div>
        <div class="mt-4 row">
            <div class="col">
                <a href="javascript:void(0);" @click="modalState.deleteAccountConfirmationModal.show()" 
                    class="d-block btn btn-outline-danger rounded-pill">{{$t('profile.account_delete.delete')
                    }}</a>
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
                    <button type="button" class="d-block btn btn-outline-blue rounded-pill" @click="modalState.deleteAccountConfirmationModal.hide()">
                        {{ $t('profile.deleteAccount_modal.cancel') }}
                    </button>
                    <button type="button" @click="deleteAccount" class="d-block btn btn-outline-danger rounded-pill">
                        {{ $t('profile.deleteAccount_modal.yes') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
