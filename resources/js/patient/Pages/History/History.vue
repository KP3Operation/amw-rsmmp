<script setup>
import MicroscopeWhite from "@resources/static/icons/microscope-white.svg";
import Header from "@shared/Components/Header/Header.vue";
import axios from "axios";
import { onMounted, reactive, ref, watch } from "vue";
import { usePatientVitalSignStore } from "@patient/+store/patient-vital-sign.store";
import { useLayoutStore } from "@shared/+store/layout.store";
import VitalSignCard from "@patient/Components/VitalSignCard/VitalSignCard.vue";
import PatientCard from "@patient/Components/PatientCard/PatientCard.vue";
import * as bootsrap from "bootstrap";
import { useFamilyStore } from "@patient/+store/family.store.js";
import { storeToRefs } from "pinia";

const patientVitalSignStore = usePatientVitalSignStore();
const layoutStore = useLayoutStore();
const currentActiveTab = ref('unit-vital');
const modalState = reactive({
    familyMemberFilterModal: null
});
const selectedVitalSignType = ref("");
const prescriptionHistories = reactive([]);
const labResults = reactive([]);
const appointments = reactive([]);
const familyStore = useFamilyStore();
const { families } = storeToRefs(familyStore);

const fetchVitalSignHistories = () => {
    layoutStore.isLoading = true;
    patientVitalSignStore.$reset();
    axios.get(`/api/v1/patient/medical/history/vitalsign?type=${selectedVitalSignType.value}`).then((response) => {
        const data = response.data.data;
        data.histories.forEach((history) => {
            patientVitalSignStore.$patch((state) => {
                state.histories.push(history);
            });
        });
        patientVitalSignStore.$patch({
            patient: data.patient,
            patientData: data.patient.patient_data
        });
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = false;
    });
}


const fetchPrescriptionHistories = () => {
    layoutStore.isLoading = true;
    patientVitalSignStore.$reset();
    axios.get(`/api/v1/patient/medical/history/prescription`).then((response) => {
        const data = response.data.data;
        prescriptionHistories.values = data.histories;
        console.log(prescriptionHistories);
        patientVitalSignStore.$patch({
            patient: data.patient,
            patientData: data.patient.patient_data
        });
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = false;
    });
}

const fetchLabResults = () => {
    layoutStore.isLoading = true;
    patientVitalSignStore.$reset();
    axios.get(`/api/v1/patient/medical/history/labresult`).then((response) => {
        const data = response.data.data;
        labResults.values = data.histories;
        console.log(labResults);
        patientVitalSignStore.$patch({
            patient: data.patient,
            patientData: data.patient.patient_data
        });
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = false;
    });
}

const fetchAppointment = () => {
    console.log('fetch appointment');
}


watch(selectedVitalSignType, (newValue, oldValue) => {
    selectedVitalSignType.value = newValue;
    fetchVitalSignHistories();
});

watch(currentActiveTab, (newValue, oldValue) => {
    if (newValue === 'unit-vital') {
        fetchVitalSignHistories();
    }

    if (newValue === 'resep-obat') {
        fetchPrescriptionHistories();
    }

    if (newValue === 'hasil-lab') {
        fetchLabResults();
    }

    if (newValue === 'pertemuan') {
        fetchAppointment();
    }
});

const fetchFamily = () => {
    axios.get(`/api/v1/patient/family`).then((response) => {
        const data = response.data.data;
        familyStore.$patch({
            families: data.families
        });
        console.log(families.value);
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    });
}

const resetFamilyFilter = () => {
    window.location.reload();
}

const filterByFamily = () => {
    console.log('filter');
}

onMounted(() => {
    fetchVitalSignHistories();
    fetchFamily();
    modalState.familyMemberFilterModal = new bootsrap.Modal("#modal-filter");
});

</script>

<template>
    <Header :title="$t('history.title')" :with-back-url="true" :with-action-btn="true">
        <button id="btn-akan-datang" @click="modalState.familyMemberFilterModal.show();"
            class="btn d-flex col-gap-8 align-items-center fw-bold text-blue-500 p-0">
            <i class="bi bi-filter-left fs-2"></i>
        </button>
    </Header>

    <div class="px-4 pt-7">

        <PatientCard :name="patientVitalSignStore.patient.name" :gender="patientVitalSignStore.patientData.gender"
            :medicalNo="patientVitalSignStore.patientData.medical_no"
            :birthDate="patientVitalSignStore.patientData.birth_date" />

        <div class="tab-riwayat-medis nav nav-pills d-flex flex-nowrap col-gap-20 mt-4">
            <button class="nav-link unit-vital active" data-bs-toggle="pill" data-bs-target="#unit-vital" role="tab"
                aria-controls="unit-vital" aria-selected="true" @click="currentActiveTab = 'unit-vital'">
                <div class="icon">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>

                <p>{{ $t('history.vital_sign') }}</p>
            </button>

            <button class="nav-link resep-obat" data-bs-toggle="pill" data-bs-target="#resep-obat" role="tab"
                aria-controls="resep-obat" aria-selected="true" @click="currentActiveTab = 'resep-obat'">
                <div class="icon">
                    <i class="bi bi-capsule"></i>
                </div>

                <p>{{ $t('history.medical_prescription') }}</p>
            </button>

            <button class="nav-link hasil-lab" data-bs-toggle="pill" data-bs-target="#hasil-lab" role="tab"
                aria-controls="hasil-lab" aria-selected="true" @click="currentActiveTab = 'hasil-lab'">
                <div class="icon">
                    <img :src="MicroscopeWhite" alt="Icon" width="16" height="16">
                </div>

                <p>{{ $t('history.lab_result') }}</p>
            </button>

            <button class="nav-link pertemuan" data-bs-toggle="pill" data-bs-target="#pertemuan" role="tab"
                aria-controls="pertemuan" aria-selected="true" @click="currentActiveTab = 'pertemuan'">
                <div class="icon">
                    <i class="bi bi-clipboard-heart-fill"></i>
                </div>

                <p>{{ $t('history.meeting') }}</p>
            </button>
        </div>


        <div class="tab-content mt-4" id="tab-content">

            <section class="tab-pane fade show active" id="unit-vital" role="tabpanel" aria-labelledby="unit-vital"
                tabindex="0">
                <div id="multiselect" class="dropdown filter-sticky d-flex col-gap-20 align-items-center">
                    <p>Tipe</p>
                    <select class="form-select" aria-label="Tipe" v-model="selectedVitalSignType">
                        <option value="" selected>Pilih Tipe</option>
                        <option value="BP1">Tekanan Darah Sistolik</option>
                        <option value="BP2">Tekanan Darah Diastolik</option>
                        <option value="TEMP">Suhu Tubuh</option>
                        <option value="RESP">Laju Pernapasan</option>
                    </select>
                </div>
                <div class="d-flex flex-column rows-gap-16 mt-4" v-for="history in patientVitalSignStore.histories">

                    <VitalSignCard :dateCreated="history.recordDate_yMdHms" :timeCreated="history.recordTime"
                        :registrationNo="history.registrationNo" :vitalSignUnit="history.vitalSignUnit"
                        :vitalSignName="history.vitalSignName" />

                </div>
            </section>

            <section class="tab-pane fade" id="resep-obat" role="tabpanel" aria-labelledby="unit-vital" tabindex="0">
            </section>

            <section class="tab-pane fade" id="hasil-lab" role="tabpanel" aria-labelledby="unit-vital" tabindex="0">
            </section>

            <section class="tab-pane fade" id="pertemuan" role="tabpanel" aria-labelledby="unit-vital" tabindex="0">
            </section>

            <div class="text-center mt-3" v-if="layoutStore.isLoading">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-filter" aria-labelledby="Modal Filter" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div class="d-flex align-items-center col-gap-8">
                        <i class="bi bi-info-circle-fill icon-blue-500 fs-3"></i>
                        <h5 class="modal-title">{{ $t('history.modal_filter.title') }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x fs-2 icon-black"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" class="d-flex flex-column rows-gap-16">
                        <div>
                            <label for="member" class="d-block text-start">{{ $t('history.modal_filter.member') }}</label>
                            <select name="member" id="member" class="form-select mt-2">
                                <option v-for="(member, index) in families" :value="member.id">{{ member.name }}</option>
                            </select>
                        </div>

                        <div class="d-flex col-gap-20">

                            <button type="reset" @click="resetFamilyFilter" class="w-50 btn btn-red-500-rounded"
                                data-bs-dismiss="modal">{{
                                    $t('history.modal_filter.reset') }}</button>

                            <button type="button" @click="filterByFamily" class="w-50 btn btn-blue-500-rounded"
                                data-bs-dismiss="modal">{{
                                    $t('history.modal_filter.apply') }}</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
