<script setup>
import { useInpatientStore } from "@doctor/+store/inpatient.store.js";
import Header from "@shared/Components/Header/Header.vue";
import { storeToRefs } from "pinia";
import { computed, onMounted, ref, watch } from "vue";
import { useRoute } from "vue-router";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import { convertDateTimeToDate } from "@shared/utils/helpers.js"
import { convertDateTimeToDateTime } from "@shared/utils/helpers.js"

import apiRequest from "@shared/utils/axios.js";

const inpatientStore = useInpatientStore();
const { selectedPatient, selectedRegistrationNo } = storeToRefs(inpatientStore);
const layoutStore = useLayoutStore();
const { isLoading } = storeToRefs(layoutStore);
const route = useRoute();
const cppts = ref([]);
const selectedCpptType = ref("");
const currentPage = ref(1);
const itemsPerPage = 5;

const truncateCpptType = (cpptType) => {
    const maxLength = 36;
    return cpptType.length > maxLength
        ? `${cpptType.slice(0, maxLength)}...`
        : cpptType;
};

const cpptTypes = computed(() => {
    return [...new Set(
        cppts.value
            .map((cppt) => cppt.sRMedicalNotesInputType)
            .filter(Boolean)
    )].sort((a, b) => a.localeCompare(b));
});

const filteredCppts = computed(() => {
    const filteredData = selectedCpptType.value
        ? cppts.value.filter((cppt) => cppt.sRMedicalNotesInputType === selectedCpptType.value)
        : cppts.value;

    return [...filteredData].sort((a, b) => {
        const dateA = Date.parse((a.dateTimeInfo_yMdHms || "").replace(" ", "T")) || 0;
        const dateB = Date.parse((b.dateTimeInfo_yMdHms || "").replace(" ", "T")) || 0;
        return dateB - dateA;
    });
});

const totalPages = computed(() => Math.max(1, Math.ceil(filteredCppts.value.length / itemsPerPage)));

const paginatedCppts = computed(() => {
    const startIndex = (currentPage.value - 1) * itemsPerPage;
    return filteredCppts.value.slice(startIndex, startIndex + itemsPerPage);
});

watch(selectedCpptType, () => {
    currentPage.value = 1;
});

watch(totalPages, (newTotalPages) => {
    if (currentPage.value > newTotalPages) {
        currentPage.value = newTotalPages;
    }
});

const fetchPatientRegistrationCPPTs = () => {
    const registrationNo = selectedRegistrationNo.value || route.query.registration_no;

    if (!registrationNo) {
        layoutStore.isLoading = false;
        return;
    }

    layoutStore.isLoading = true;
    apiRequest.get('/api/v1/doctor/inpatient/cppt/registrations', {
        params: { registration_no: registrationNo }
    }).then((response) => {
        cppts.value = Array.isArray(response.data.cppts) ? response.data.cppts : [];
        currentPage.value = 1;
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
    fetchPatientRegistrationCPPTs();
    // NOTE: We need to check if thereis a selected patient in store(?)
    // if (Object.keys(selectedPatient.value).length === 0 && selectedRegistrationNo.value === "") {
    //     router.push({ name: "InpatientListPage" });
    // }
});
</script>
<template>
    <Header :title="$t('inpatient.details.title')" :with-back-url="true" page-name="InpatientListPage"></Header>
    <div class="patient-detail-content px-4 pt-8 mt-4">
        <div class="text-center mt-3" v-if="isLoading">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="d-flex flex-column rows-gap-16 mt-2" v-if="!isLoading">
            <div class="patient-information-card border rounded px-4 py-3 bg-blue-100 border-blue-200">
                <div class="patient-information-grid">
                    <div class="patient-information-item">
                        <p class="fs-6 text-gray-700">No. Registrasi</p>
                        <p class="fs-5 mt-2 fw-semibold">{{ selectedPatient.registrationNo || route.query.registration_no || '-' }}</p>
                    </div>
                    <div class="patient-information-item text-end">
                        <p class="fs-6 text-gray-700">No. RM</p>
                        <p class="fs-5 mt-2 fw-semibold">{{ selectedPatient.medicalNo || '-' }}</p>
                    </div>
                    <div class="patient-information-item">
                        <p class="fs-6 text-gray-700">Nama Pasien</p>
                        <p class="fs-5 mt-2 fw-semibold">{{ selectedPatient.patientName || '-' }}</p>
                    </div>
                    <div class="patient-information-item text-end">
                        <p class="fs-6 text-gray-700">Tgl Lahir</p>
                        <p class="fs-5 mt-2 fw-semibold">{{ selectedPatient.dateOfBirth ? convertDateTimeToDate(selectedPatient.dateOfBirth) : '-' }}</p>
                    </div>
                    <div class="patient-information-item">
                        <p class="fs-6 text-gray-700">Gender</p>
                        <p class="fs-5 mt-2 fw-semibold" v-if="selectedPatient.sex === 'M'">{{ $t('inpatient.details.male') }}</p>
                        <p class="fs-5 mt-2 fw-semibold" v-else-if="selectedPatient.sex === 'F'">{{ $t('inpatient.details.female') }}</p>
                        <p class="fs-5 mt-2 fw-semibold" v-else>-</p>
                    </div>
                    <div class="patient-information-item text-end">
                        <p class="fs-6 text-gray-700">Nama Ruang</p>
                        <p class="fs-5 mt-2 fw-semibold">{{ selectedPatient.roomName || '-' }}</p>
                    </div>
                    <div class="patient-information-item">
                        <p class="fs-6 text-gray-700">Nama Guarantor</p>
                        <p class="fs-5 mt-2 fw-semibold">{{ selectedPatient.guarantorName || '-' }}</p>
                    </div>
                    <div class="patient-information-item text-end">
                        <p class="fs-6 text-gray-700">Kelas</p>
                        <p class="fs-5 mt-2 fw-semibold">{{ selectedPatient.className || '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="pb-3">
                <p class="fs-5 text-gray-700">Riwayat Medis</p>

                <div class="mt-3">
                    <select
                        id="cppt-type-filter"
                        v-model="selectedCpptType"
                        class="form-select cppt-type-select"
                    >
                        <option value="">Filter tipe CPPT</option>
                        <option v-for="cpptType in cpptTypes" :key="cpptType" :value="cpptType">
                            {{ truncateCpptType(cpptType) }}
                        </option>
                    </select>
                </div>

                <div v-if="paginatedCppts.length" id="accordion" class="accordion d-flex flex-column rows-gap-16">
                    <div v-for="(cppt, index) in paginatedCppts" :key="`${cppt.registrationNo}-${cppt.dateTimeInfo_yMdHms}-${index}`">
                    <div class="accordion-item rawat-inap mt-3">
                        <div v-if="cppt.isDeleted" class="w-100 pt-2 pb-2 text-center" style="background-color: red;border-radius: 5px;">
                            <p style="font-size:14px; font-weight:800;color:white;">VOID</p>
                        </div>
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed col-gap-20" type="button" data-bs-toggle="collapse"
                                :data-bs-target="'#riwayat-medis-' + index" aria-expanded="false"
                                aria-controls="riwayat-medis-1">
                                <div class="cppt-wrapper">
                                    <div class="d-flex justify-content-between">
                                        <div class="w-100 d-flex flex-column rows-gap-16 fs-6">
                                            <div>
                                                <p class="fw-bold">{{ $t('inpatient.details.date') }}</p>
                                                <p class="fw-normal">{{ convertDateTimeToDateTime(cppt.dateTimeInfo_yMdHms) }}
                                                </p>
                                            </div>

                                            <div>
                                                <p class="fw-bold">{{ $t('inpatient.details.registration_no') }}</p>
                                                <p class="fw-normal">{{ cppt.registrationNo }}</p>
                                            </div>

                                            <div>
                                                <p class="fw-bold">{{ $t('inpatient.details.created_by') }}</p>
                                                <p class="fw-normal">{{ cppt.createdByUserName }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-25 text-end">
                                    <p class="fs-6 text-gray-700">{{ $t('inpatient.details.cppt_type') }}</p>
                                    <p class="fs-3 fw-bold">{{ cppt.sRMedicalNotesInputType }}</p>
                                </div>
                                </div>
                            </button>
                        </h2>

                        <div :id="'riwayat-medis-' + index" class="accordion-collapse collapse"
                            data-bs-parent="#accordion">
                            <div class="accordion-body">
                                <div class="accordion-divider"></div>

                                <!-- <ul class="mt-3 pl-1" v-if="cppt.sRMedicalNotesInputType === 'Notes' && !cppt.info1.includes('TTV')">
                                    <li>{{ $t('inpatient.details.implementation') }} {{ cppt.info1 }}</li>
                                    <li>{{ $t('inpatient.details.response_result') }} {{ cppt.info2 }}</li>
                                </ul> -->
                                <ul class="mt-3 pl-1" v-if="cppt.sRMedicalNotesInputType === 'Notes'">
                                    <li><b>{{ $t('inpatient.details.implementation') }}</b> {{ cppt.info1 }}</li>
                                    <li>
                                        <b>{{ $t('inpatient.details.response_result') }}</b> 
                                        <br/>{{ cppt.info2 }}
                                        <br/>{{ cppt.info6 }}
                                    </li>
                                </ul>

                                <ul class="mt-3 pl-1" v-if="cppt.sRMedicalNotesInputType === 'SOAP'">
                                    <li><b>S</b>: {{ cppt.info1 }}</li>
                                    <li><b>O</b>: {{ cppt.info2 }}</li>
                                    <li><b>A</b>: {{ cppt.info3 }}</li>
                                    <li><b>P</b>: {{ cppt.info4 }}</li>
                                    <li><b>I</b>: {{ cppt.ppaInstruction }}</li>
                                    <li><b>E</b>: {{ cppt.info5 }}</li>
                                </ul>

                                <!-- <ul class="mt-3 pl-1" v-if="cppt.sRMedicalNotesInputType === 'SBAR'">
                                    <li><b>S</b>: {{ cppt.info1 }}</li>
                                    <li><b>B</b>: {{ cppt.info2 }}</li>
                                    <li><b>A</b>: {{ cppt.info3 }}</li>
                                    <li><b>R</b>: {{ cppt.info4 }}</li>
                                    <li><b>I</b>: {{ cppt.ppaInstruction }}</li>
                                    <li><b>TBAK</b>: {{ cppt.info5 }}</li>
                                </ul> -->

                                <ul class="mt-3 pl-1" v-if="cppt.sRMedicalNotesInputType === 'ADIME'">
                                    <li><b>A</b>: {{ cppt.info1 }}</li>
                                    <li><b>D</b>: {{ cppt.info2 }}</li>
                                    <li><b>I</b>: {{ cppt.info3 }}</li>
                                    <li><b>M</b>: {{ cppt.info4 }}</li>
                                    <li><b>E</b>: {{ cppt.info5 }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

                <p v-else class="text-center text-gray-700 mt-4">
                    Data riwayat medis tidak ditemukan.
                </p>

                <div v-if="filteredCppts.length > itemsPerPage" class="cppt-pagination d-flex align-items-center justify-content-center col-gap-20 mt-4" role="navigation" aria-label="Pagination riwayat medis">
                    <button
                        type="button"
                        class="btn btn-outline-primary cppt-pagination-button"
                        :disabled="currentPage === 1"
                        @click="currentPage--"
                        aria-label="Halaman sebelumnya"
                    ><i class="bi bi-chevron-left" aria-hidden="true"></i></button>
                    <span class="cppt-page-info">Hal. {{ currentPage }}/{{ totalPages }}</span>
                    <button
                        type="button"
                        class="btn btn-outline-primary cppt-pagination-button"
                        :disabled="currentPage === totalPages"
                        @click="currentPage++"
                        aria-label="Halaman berikutnya"
                    ><i class="bi bi-chevron-right" aria-hidden="true"></i></button>
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

.patient-detail-content {
    padding-bottom: 96px;
}

.patient-information-card {
    display: flex;
    flex-direction: column;
}

.patient-information-grid {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
    gap: 16px 20px;
}

.patient-information-item {
    min-width: 0;
}

.patient-information-item p:last-child {
    overflow-wrap: anywhere;
}

.cppt-type-select {
    width: 100%;
    max-width: 100%;
    text-overflow: ellipsis;
}

.cppt-type-select option {
    max-width: 100%;
}

.cppt-pagination {
    width: 100%;
    padding: 8px 0;
}

.cppt-pagination-button {
    width: 40px;
    height: 40px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.cppt-page-info {
    min-width: 72px;
    text-align: center;
}
</style>
