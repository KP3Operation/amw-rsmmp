<script setup>
import Header from "@shared/Components/Header/Header.vue";
import { onMounted, reactive, ref } from "vue";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import axios from "axios";
import { useFamilyStore } from "@patient/+store/family.store.js";
import { storeToRefs } from "pinia";
import FamilyMemberCard from "@patient/Components/FamilyMemberCard/FamilyMemberCard.vue";
import * as bootstrap from "bootstrap";
import NotFoundImage from "@resources/static/images/not-found.png";

const layoutStore = useLayoutStore();
const familyStore = useFamilyStore();
const { families } = storeToRefs(familyStore);
const selectedFamilyMemberName = ref('');
const selectedFamilyMemberId = ref(0);
const modalState = reactive({
    deleteFamilyConfirmation: null
});

const fetchFamily = () => {
    axios.get(`/api/v1/patient/family`).then((response) => {
        const data = response.data.data;
        familyStore.$patch({
            families: data.families
        });
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    });
}

const handleDeleteFamilyMember = (event) => {
    selectedFamilyMemberId.value = event.familyId.value;
    selectedFamilyMemberName.value = event.familyName.value;
    modalState.deleteFamilyConfirmation.show();
}

const handleDeleteFamily = () => {
    layoutStore.isLoading = true;
    axios.delete(`/api/v1/patient/family/${selectedFamilyMemberId.value}`).then((response) => {
        layoutStore.toggleInfoAlert(`Berhasil Hapus Data ${selectedFamilyMemberName.value}`);
        selectedFamilyMemberId.value = 0;
        selectedFamilyMemberName.value = '';
        fetchFamily();
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = false;
    });
}

onMounted(() => {
    modalState.deleteFamilyConfirmation = new bootstrap.Modal("#modal-remove");
    layoutStore.$patch({
        isFullView: false
    });
    fetchFamily();
});
</script>

<template>
    <Header :title="$t('family.title')" :with-back-url="true" :with-action-btn="true">
        <router-link to="/family/create" class="add">
            <i class="bi bi-plus-circle-fill"></i>
        </router-link>
    </Header>

    <div class="px-4 pt-8">

        <div class="d-flex flex-column rows-gap-16 mt-4" v-for="family in families" v-if="families.length > 0">
            <FamilyMemberCard :name="family.name" :birth-date="family.birth_date" :ssn="family.ssn"
                :phone-number="family.phone_number" :family-id="family.id"
                @delete-family-member="handleDeleteFamilyMember" />
        </div>
        <div class="d-flex flex-column rows-gap-16 mt-4" v-else>
            <div class="text-center">
                <img :src="NotFoundImage" alt="Ilustrasi Family Member" width="206" height="195">
                <h2 class="fs-2 fw-bold mt-4">Tambah Orang Yang Anda Sayangi</h2>
                <p class="text-gray-800 mt-2">Tambah orang yang anda sayangi untuk menjadi family member</p>
                <router-link to="/family/create"
                    class="d-flex col-gap-8 btn btn-blue-500-rounded text-decoration-none justify-content-center align-items-center mt-4">
                    <i class="bi bi-plus fs-3"></i>
                    Tambah Data
                </router-link>
            </div>
        </div>
    </div>
    <div class="modal" id="modal-remove" aria-labelledby="Remove Modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div class="d-flex align-items-center col-gap-8">
                        <i class="bi bi-info-circle-fill icon-blue-500 fs-3"></i>
                        <h5 class="modal-title">{{ $t('family.delete_confirmation_modal.title') }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        @click="modalState.deleteFamilyConfirmation.hide()" aria-label="Close">
                        <i class="bi bi-x fs-2 icon-black"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ $t('family.delete_confirmation_modal.message', { name: selectedFamilyMemberName }) }} </p>
                </div>
                <div class="modal-footer flex-nowrap">
                    <button @click="modalState.deleteFamilyConfirmation.hide()" type="button" class="w-50 btn btn-link"
                        data-bs-dismiss="modal">Batal</button>

                    <button @click="handleDeleteFamily" type="button" class="w-50 btn-hapus-data btn btn-red-500-rounded"
                        data-bs-dismiss="modal">Hapus Data</button>
                </div>
            </div>
        </div>
    </div>
</template>
