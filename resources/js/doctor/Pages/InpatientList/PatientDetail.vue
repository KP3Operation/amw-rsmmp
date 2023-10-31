<script setup>
import { useInpatientStore } from "@doctor/+store/inpatient.store.js";
import Header from "@shared/Components/Header/Header.vue";
import axios from "axios";
import { storeToRefs } from "pinia";
import { onMounted, reactive, watch } from "vue";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import { convertDateTimeToDate } from "@shared/utils/helpers.js"
import apiRequest from "@shared/utils/axios.js";
// import router from "@doctor/router.js";

const inpatientStore = useInpatientStore();
const { selectedPatient, selectedRegistrationNo } = storeToRefs(inpatientStore);
const layoutStore = useLayoutStore();
const { isLoading } = storeToRefs(layoutStore);
const cppts = reactive([]);

const fetchPatientRegistrationCPPTs = () => {
    layoutStore.isLoading = true;
    apiRequest.get(`/api/v1/doctor/inpatient/cppt/registrations?registration_no=${selectedRegistrationNo.value}`).then((response) => {
        cppts.values = response.data.cppts;
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = false;
    })
}

watch(selectedRegistrationNo, (newSelectedRegistrationNo, oldSelectedRegistrationNo) => {
    if (newSelectedRegistrationNo !== oldSelectedRegistrationNo) {
        fetchPatientRegistrationCPPTs();
    }
});

onMounted(() => {
    layoutStore.isLoading = true;
    if (selectedRegistrationNo.value !== "") {
        fetchPatientRegistrationCPPTs();
    }
    // NOTE: We need to check if thereis a selected patient in store(?)
    // if (Object.keys(selectedPatient.value).length === 0 && selectedRegistrationNo.value === "") {
    //     router.push({ name: "InpatientListPage" });
    // }
});
</script>
<template>
    <Header title="Detail Pasien Rawat Inap" :with-back-url="true" page-name="InpatientListPage"></Header>
    <div class="px-4 pt-8 mt-4">
        <div class="text-center mt-3" v-if="isLoading">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="d-flex flex-column rows-gap-16 mt-2" v-if="!isLoading">
            <div class="d-flex justify-content-between pb-3 border-bottom border-gray-400">
                <div class="w-50">
                    <p class="fs-6 text-gray-700">No. Rekam Medis</p>
                    <p class="mt-2">{{ selectedPatient.medicalNo }}</p>
                </div>

                <div class="w-50 text-end">
                    <p class="fs-6 text-gray-700">Nama Ruang</p>
                    <p class="mt-2">{{ selectedPatient.roomName }}</p>
                </div>
            </div>

            <div class="pb-3 border-bottom border-gray-400">
                <div>
                    <p class="fs-6 text-gray-700">Nama Pasien</p>
                    <p class="mt-2">{{ selectedPatient.patientName }}</p>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <div class="w-50">
                        <p class="fs-6 text-gray-700">Jenis Kelamin</p>
                        <p class="mt-2" v-if="selectedPatient.sex === 'M'">Laki-Laki</p>
                        <p class="mt-2" v-if="selectedPatient.sex === 'F'">Perempuan</p>
                    </div>

                    <div class="w-50 text-end">
                        <p class="fs-6 text-gray-700">Tanggal Lahir</p>
                        <p class="mt-2">{{ selectedPatient.dateOfBirth }}</p>
                    </div>
                </div>
            </div>

            <div class="pb-3">
                <p class="fs-6 text-gray-700">Riwayat Medis</p>

                <div class="accordion d-flex flex-column rows-gap-16 mt-3" id="accordion"
                    v-for="(cppt, index) in cppts.values">
                    <div class="accordion-item rawat-inap">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed col-gap-20" type="button" data-bs-toggle="collapse"
                                :data-bs-target="'#riwayat-medis-' + index" aria-expanded="false"
                                aria-controls="riwayat-medis-1">
                                <div class="cppt-wrapper">
                                    <div class="d-flex justify-content-between">
                                        <div class="w-100 d-flex flex-column rows-gap-16 fs-6">
                                            <div>
                                                <p class="fw-bold">Tanggal</p>
                                                <p class="fw-normal">{{ convertDateTimeToDate(cppt.dateTimeInfo_yMdHms) }}
                                                </p>
                                            </div>

                                            <div>
                                                <p class="fw-bold">No. Registrasi</p>
                                                <p class="fw-normal">{{ cppt.registrationNo }}</p>
                                            </div>

                                            <div>
                                                <p class="fw-bold">Dibuat Oleh</p>
                                                <p class="fw-normal">{{ cppt.createdByUserID }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-25 text-end">
                                    <p class="fs-6 text-gray-700">Tipe CPPT</p>
                                    <p class="fs-3 fw-bold">{{ cppt.sRMedicalNotesInputType }}</p>
                                </div>
                                </div>
                            </button>
                        </h2>

                        <div :id="'riwayat-medis-' + index" class="accordion-collapse collapse"
                            :data-bs-parent="'#riwayat-medis-' + index">
                            <div class="accordion-body">
                                <div class="accordion-divider"></div>

                                <ul class="mt-3 pl-1" v-if="cppt.sRMedicalNotesInputType === 'Notes' && !cppt.info1.includes('TTV')">
                                    <li>implementasi: {{ cppt.info1 }}</li>
                                    <li>Respond/Result: {{ cppt.info2 }}</li>
                                </ul>

                                <ul class="mt-3 pl-1" v-if="cppt.sRMedicalNotesInputType === 'SOAP'">
                                    <li>S: {{ cppt.info1 }}</li>
                                    <li>O: {{ cppt.info2 }}</li>
                                    <li>A: {{ cppt.info3 }}</li>
                                    <li>P: {{ cppt.info4 }}</li>
                                </ul>

                                <ul class="mt-3 pl-1" v-if="cppt.sRMedicalNotesInputType === 'SBAR'">
                                    <li>S: {{ cppt.info1 }}</li>
                                    <li>B: {{ cppt.info2 }}</li>
                                    <li>A: {{ cppt.info3 }}</li>
                                    <li>R: {{ cppt.info4 }}</li>
                                </ul>

                                <ul class="mt-3 pl-1" v-if="cppt.sRMedicalNotesInputType === 'ADIME'">
                                    <li>A: {{ cppt.info1 }}</li>
                                    <li>D: {{ cppt.info2 }}</li>
                                    <li>I: {{ cppt.info3 }}</li>
                                    <li>M: {{ cppt.info4 }}</li>
                                    <li>E: {{ cppt.info5 }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.cppt-wrapper {
    width: 100%;
    display: flex;
    justify-content: space-between;
}
</style>
