<script setup>
import Header from "@shared/Components/Header/Header.vue";
import SubmitButton from "@shared/Components/SubmitButton/SubmitButton.vue";
import { onMounted, reactive, ref } from "vue";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import Form from "vform";
import * as bootstrap from "bootstrap";
import router from "@patient/router.js";
import { useRoute } from "vue-router";
import axios from "axios";
import { convertDateToFormField } from "@shared/utils/helpers.js";

const callingCode = import.meta.env.VITE_APP_CALLING_CODE;
const layoutStore = useLayoutStore();
const newFamilyId = ref(0);
const isEditMode = ref(false);
const route = useRoute();

const modalState = reactive({
    familyDataConfirmation: null,
});

const form = reactive(
    new Form({
        id: null,
        ssn: null,
        phone_number: null,
        name: null,
        gender: null,
        birth_date: null,
        email: null
    })
);

const confirmation = () => {
    modalState.familyDataConfirmation.show();
}

const storeFamilyConfirmFalse = () => {
    modalState.familyDataConfirmation.hide();
    router.push({ path: '/family' });
}


const storeFamilyConfirmTrue = () => {
    modalState.familyDataConfirmation.hide();
    router.push({ path: `/family/confirm/${newFamilyId.value}` });
}

const storeFamily = () => {
    layoutStore.isLoading = true;
    form.post('/api/v1/patient/family').then((response) => {
        const data = response.data.data;
        newFamilyId.value = data.family.id;
        modalState.familyDataConfirmation.show();
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = false;
    });
}

const updateFamily = () => {
    layoutStore.isLoading = true;
    form.put(`/api/v1/patient/family/${route.params.id}`).then((response) => {
        const data = response.data.data;
        layoutStore.toggleInfoAlert(`Berhasil Mengubah Data Member ${data.family.name}`);
        router.push({ path: '/family' });
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = false;
    });
}

const fetchFamily = () => {
    layoutStore.isLoading = true;
    axios.get(`/api/v1/patient/family/${route.params.id}`).then((response) => {
        const data = response.data;
        form.id = data.family.id;
        form.name = data.family.name;
        form.ssn = data.family.ssn;
        form.phone_number = data.family.phone_number.replace(callingCode, "");
        form.gender = (data.family.gender === "F") ? "Perempuan" : "Laki-Laki";
        form.birth_date = convertDateToFormField(data.family.birth_date);
        form.email = data.family.email;
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = false;
    });
}

onMounted(() => {
    layoutStore.$patch({
        isFullView: true
    });
    modalState.familyDataConfirmation = new bootstrap.Modal("#modal-konfirmasi");
    if (route.params.id !== undefined) {
        fetchFamily();
        isEditMode.value = true;
    }
});
</script>

<template>
    <Header :title="isEditMode ? $t('family.edit.title') : $t('family.create.title')" :with-back-url="true"
        pageName="FamilyPage" :with-action-btn="false">
    </Header>

    <div class="px-4 pt-7 pb-4">
        <p class="fs-5 text-red-500">* {{ $t('family.required_field') }}</p>

        <form @submit.prevent="isEditMode ? updateFamily() : storeFamily()" @keydown="form.onKeydown($event)"
            class="d-flex flex-column rows-gap-16 mt-3">
            <div>
                <label for="nama">{{ $t('family.patient_name') }} <span class="text-red-500 fw-semibold">*</span>
                </label>
                <input type="text" name="nama" id="nama" placeholder="John Doe" class="form-control mt-2"
                    v-model="form.name">
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('name')"
                    v-html="form.errors.get('name')" />
            </div>

            <div>
                <label for="nik">{{ $t('family.ssn') }} <span class="text-red-500 fw-semibold">*</span></label>
                <input type="number" name="nik" id="nik" placeholder="3123987564123" class="form-control mt-2"
                    v-model="form.ssn" :readonly="isEditMode">
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('ssn')"
                    v-html="form.errors.get('ssn')" />
            </div>

            <div>
                <label for="dob">{{ $t('family.birth_date') }} <span class="text-red-500 fw-semibold">*</span></label>
                <input type="date" name="dob" id="dob" class="form-control mt-2" v-model="form.birth_date">
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('birth_date')"
                    v-html="form.errors.get('birth_date')" />
            </div>

            <div>
                <label for="nohp">{{ $t('family.phone_number') }} <span class="text-red-500 fw-semibold">*</span></label>
                <div class="input-group flex-nowrap mt-2">
                    <span class="input-group-text">{{ callingCode }}</span>
                    <input type="tel" name="no-hp" id="no-hp" placeholder="8123940183020" class="form-control"
                        v-model="form.phone_number">
                </div>
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('phone_number')"
                    v-html="form.errors.get('phone_number')" />
            </div>

            <div>
                <label for="gender">{{ $t('family.gender') }} <span class="text-red-500 fw-semibold">*</span></label>
                <div class="d-flex col-gap-20 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" :id="$t('family.male')"
                            :value="$t('family.male')" v-model="form.gender">
                        <label class="form-check-label" :for="$t('family.male')">
                            {{ $t('family.male') }}
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" :id="$t('family.female')"
                            :value="$t('family.female')" v-model="form.gender">
                        <label class="form-check-label" :for="$t('family.female')">
                            {{ $t('family.female') }}
                        </label>
                    </div>
                </div>
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('gender')"
                    v-html="form.errors.get('gender')" />
            </div>

            <div>
                <label for="email">{{ $t('family.email') }}</label>
                <input type="email" name="email" id="email" class="form-control mt-2" placeholder="email@example.com"
                    v-model="form.email">
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('email')"
                    v-html="form.errors.get('email')" />
            </div>

            <SubmitButton className="btn-blue-500-rounded"
                :text="isEditMode ? $t('family.edit.save') : $t('family.save')" />
            <router-link v-show="!layoutStore.isLoading" to="/family"
                class="text-center text-blue-500 text-decoration-none fw-bold">{{
                    $t('family.cancel') }}</router-link>
        </form>
    </div>

    <div class="modal" id="modal-konfirmasi" aria-labelledby="Confirmation Modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div class="d-flex align-items-center col-gap-8">
                        <i class="bi bi-info-circle-fill icon-blue-500 fs-3"></i>
                        <h5 class="modal-title">{{ $t('family.create.confirmation_modal.title') }}</h5>
                    </div>
                    <button type="button" class="btn-close" @click="modalState.familyDataConfirmation.hide()"
                        aria-label="Close">
                        <i class="bi bi-x fs-2 icon-black"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ $t('family.create.confirmation_modal.message') }}</p>
                </div>
                <div class="modal-footer flex-nowrap">
                    <button @click="storeFamilyConfirmFalse" type="button"
                        class="w-50 text-center text-gray-800 fw-bold text-decoration-none">{{
                            $t('family.create.confirmation_modal.no') }}</button>
                    <button @click="storeFamilyConfirmTrue" type="button" class="w-50 btn-next btn btn-blue">{{
                        $t('family.create.confirmation_modal.yes') }}</button>
                </div>
            </div>
        </div>
    </div>
</template>
