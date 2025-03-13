<script setup>
import { useFamilyStore } from "@patient/+store/family.store.js";
import { useMedicalHistoryStore } from "@patient/+store/medical-history.store.js";
import EncounterCard from "@patient/Components/EncounterCard/EncounterCard.vue";
import LabResultCard from "@patient/Components/LabResultCard/LabResultCard.vue";
import PatientCard from "@patient/Components/PatientCard/PatientCard.vue";
import PrescriptionCard from "@patient/Components/PrescriptionCard/PrescriptionCard.vue";
import VitalSignCard from "@patient/Components/VitalSignCard/VitalSignCard.vue";
import MicroscopeWhite from "@resources/static/icons/microscope-white.svg";
import Doctor2Image from "@resources/static/images/doctor-2.png";
import DoctorImage from "@resources/static/images/doctor.png";
import LabImage from "@resources/static/images/lab.png";
import NotFoundImage from "@resources/static/images/not-found.png";
import { useLayoutStore } from "@shared/+store/layout.store";
import Header from "@shared/Components/Header/Header.vue";
import apiRequest from "@shared/utils/axios.js";
import * as bootsrap from "bootstrap";
import { storeToRefs } from "pinia";
import { onMounted, reactive, ref, watch } from "vue";

const prevVitalSignData = ref([]);
const prevPrescriptionData = ref([]);
const prevLabResultData = ref([]);
const prevEncounterData = ref(1);
const isLabResultFile = ref(false);

const loadMoreIndexPertemuan = ref(1);

const layoutStore = useLayoutStore();
const modalState = reactive({
    familyMemberFilterModal: null
});
const selectedVitalSignType = ref("BP1");
const familyStore = useFamilyStore();
const { families } = storeToRefs(familyStore);
const medicalHistoriesStore = useMedicalHistoryStore();
const { vitalSignHistories, prescriptionHistories, encounterHistories,
    labResultHistories, selectedPatient, selectedTab, selectedFamilyMemberId } = storeToRefs(medicalHistoriesStore);

const fetchVitalSignHistories = () => {
    medicalHistoriesStore.updateVitalSignHistories([]);
    layoutStore.isLoading = true;
    apiRequest.get(`/api/v1/patient/medical/history/vitalsign`, {
        params: {
            type: selectedVitalSignType.value,
            family_member_id: selectedFamilyMemberId.value === 0 ? '' : selectedFamilyMemberId.value,
            prev_data: prevVitalSignData.value
        }
    }).then((response) => {
        const data = response.data;
        medicalHistoriesStore.updateVitalSignHistories(data.histories);

        data.histories.map((history) => {
           prevVitalSignData.value.push(history.registrationNo);
        });
        medicalHistoriesStore.updateSelectedPatient({
            name: data.patient.name,
            gender: data.patient.gender,
            medicalNo: data.patient.medical_no,
            birthDate: data.patient.birth_date
        });
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = false;
    });
}

const fetchPrescriptionHistories = () => {
    medicalHistoriesStore.updatePrescriptionHitories([]);
    layoutStore.isLoading = true;
    apiRequest.get(`/api/v1/patient/medical/history/prescription`, {
        params: {
            family_member_id: selectedFamilyMemberId.value === 0 ? '' : selectedFamilyMemberId.value,
            prev_data: prevPrescriptionData.value
        }
    }).then((response) => {
        const data = response.data;
        medicalHistoriesStore.updatePrescriptionHitories(data.histories);
        data.histories.map((history) => {
            prevPrescriptionData.value.push(history.PrescriptionNo);
        });
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = false;
    });
}

const fetchLabResults = () => {
    medicalHistoriesStore.updateLabResultHistories([]);
    layoutStore.isLoading = true;
    apiRequest.get(`/api/v1/patient/medical/history/labresult`, {
        params: {
            family_member_id: selectedFamilyMemberId.value === 0 ? '' : selectedFamilyMemberId.value,
            prev_data: prevLabResultData.value
        }
    }).then((response) => {
        const data = response.data;
        medicalHistoriesStore.updateLabResultHistories(data.histories);
        data.histories.map((history) => {
            prevLabResultData.value.push(history.registrationNo);
        });
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = false;
    });
}

const fetchEncounterHistories = () => {
    medicalHistoriesStore.updateEncounterHistories([]);
    layoutStore.isLoading = true;
    apiRequest.get(`/api/v1/patient/medical/history/encounters`, {
        params: {
            family_member_id: selectedFamilyMemberId.value === 0 ? '' : selectedFamilyMemberId.value,
            prev_data: prevEncounterData.value
        }
    }).then((response) => {
        const data = response.data;
        medicalHistoriesStore.updateEncounterHistories(data.histories);
        data.histories.map((history) => {
            prevEncounterData.value.push(history.registrationNo);
        });
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = false;
    });
}

const onTabChange = (tab) => {
    prevVitalSignData.value = [];
    prevPrescriptionData.value = [];
    prevLabResultData.value = [];
    prevEncounterData.value = [];
    medicalHistoriesStore.updateSelectedTab(tab);
}

watch(selectedVitalSignType, (newValue, oldValue) => {
    if(newValue !== oldValue) {
        prevVitalSignData.value = [];
    }
    selectedVitalSignType.value = newValue;
    fetchVitalSignHistories();
});

watch(selectedTab, (newValue, oldValue) => {
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
        fetchEncounterHistories();
    }
});

const fetchFamily = () => {
    apiRequest.get(`/api/v1/patient/family`).then((response) => {
        const data = response.data;
        familyStore.$patch({
            families: data.families
        });
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = false;
    })
}

const resetFamilyFilter = () => {
    window.location.reload();
}

const filterByFamily = () => {
    const selectedFamilyMember = families.value.find((family) => family.id === selectedFamilyMemberId.value);
    medicalHistoriesStore.updateSelectedPatient({
        name: selectedFamilyMember.name,
        gender: selectedFamilyMember.gender,
        medicalNo: selectedFamilyMember.medical_no,
        birthDate: selectedFamilyMember.birth_date
    });

    medicalHistoriesStore.updateSelectedFamilyMemberId(selectedFamilyMember.id);

    if (selectedTab.value === 'unit-vital') {
        fetchVitalSignHistories();
    }

    if (selectedTab.value === 'resep-obat') {
        fetchPrescriptionHistories();
    }

    if (selectedTab.value === 'hasil-lab') {
        fetchLabResults();
    }

    if (selectedTab.value === 'pertemuan') {
        fetchEncounterHistories();
    }

}

const loadMore = () => {
    if (selectedTab.value === 'unit-vital') {
        fetchVitalSignHistories();
    }

    if (selectedTab.value === 'resep-obat') {
        fetchPrescriptionHistories();
    }

    if (selectedTab.value === 'hasil-lab') {
        fetchLabResults();
    }

    if (selectedTab.value === 'pertemuan') {
        fetchEncounterHistories();
    }
}


const initialize = function(){
    if(import.meta.env.VITE_IS_LAB_RESULT_BRIDGING === 'true' || import.meta.env.VITE_IS_LAB_RESULT_BRIDGING === 'TRUE'){
        isLabResultFile.value = true;
    }
    else { 
        isLabResultFile.value = false; 
    }
}

onMounted(() => {
    if (selectedTab.value === '') {
        medicalHistoriesStore.updateSelectedTab('unit-vital');
    }
    initialize();
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
        <PatientCard :name="selectedPatient.name" :gender="selectedPatient.gender"
            :medicalNo="selectedPatient.medicalNo"
            :birthDate="selectedPatient.birthDate" />
        <div class="tab-riwayat-medis nav nav-pills d-flex flex-nowrap col-gap-20 mt-4">
            <button class="nav-link unit-vital" :class="selectedTab === 'unit-vital' ? 'active' : ''" data-bs-toggle="pill" data-bs-target="#unit-vital" role="tab"
                aria-controls="unit-vital" aria-selected="true" @click="onTabChange('unit-vital')">
                <div class="icon">
                    <i class="bi bi-heart-pulse-fill"></i>
                </div>
                <p>{{ $t('history.vital_sign') }}</p>
            </button>
            <button class="nav-link resep-obat" :class="selectedTab === 'resep-obat' ? 'active' : ''" data-bs-toggle="pill" data-bs-target="#resep-obat" role="tab"
                aria-controls="resep-obat" aria-selected="true" @click="onTabChange('resep-obat')">
                <div class="icon">
                    <i class="bi bi-capsule"></i>
                </div>
                <p>{{ $t('history.medical_prescription') }}</p>
            </button>
            <button class="nav-link hasil-lab" :class="selectedTab === 'hasil-lab' ? 'active' : ''" data-bs-toggle="pill" data-bs-target="#hasil-lab" role="tab"
                aria-controls="hasil-lab" aria-selected="true" @click="onTabChange('hasil-lab')">
                <div class="icon">
                    <img :src="MicroscopeWhite" alt="Icon" width="16" height="16">
                </div>
                <p>{{ $t('history.lab_result') }}</p>
            </button>
            <button class="nav-link pertemuan" :class="selectedTab === 'pertemuan' ? 'active' : ''" data-bs-toggle="pill" data-bs-target="#pertemuan" role="tab"
                aria-controls="pertemuan" aria-selected="true" @click="onTabChange('pertemuan')" form="#">
                <div class="icon">
                    <i class="bi bi-clipboard-heart-fill"></i>
                </div>
                <p>{{ $t('history.meeting') }}</p>
            </button>
        </div>
        <div class="tab-content mt-4" id="tab-content">
            <section class="tab-pane fade"
                     :class="selectedTab === 'unit-vital' ? 'show active' : ''" id="unit-vital" role="tabpanel" aria-labelledby="unit-vital"
                tabindex="0">
                <div id="multiselect" class="dropdown filter-sticky d-flex col-gap-20 align-items-center">
                    <p>Tipe</p>
                    <select class="form-select" aria-label="Tipe" v-model="selectedVitalSignType">
                        <!-- <option value="" selected>{{ $t('history.choose_vital_unit') }}</option> -->
                        <option value="BP1" selected>{{ $t('history.vital_unit.bp1') }}</option>
                        <option value="BP2">{{ $t('history.vital_unit.bp2') }}</option>
                        <option value="TEMP">{{ $t('history.vital_unit.temp') }}</option>
                        <option value="RESP">{{ $t('history.vital_unit.resp') }}</option>
                    </select>
                </div>
                <div v-if="vitalSignHistories.length > 0" class="d-flex flex-column rows-gap-16 mt-4" v-for="history in vitalSignHistories">
                    <VitalSignCard :dateCreated="history.recordDate_yMdHms" :timeCreated="history.recordTime"
                        :registrationNo="history.registrationNo" :vitalSignUnit="history.vitalSignUnit"
                        :vitalSignName="history.vitalSignName"
                        :questionAnswerNum="history.questionAnswerNum"/>
                </div>
                <div class="text-center" v-if="vitalSignHistories.length < 1 && !layoutStore.isLoading">
                    <img :src="NotFoundImage" alt="Ilustrasi Tidak Ada Data"
                         width="238" height="198" class="d-inline-block">
                    <p class="mt-3 fs-3 fw-bold" v-html="$t('history.no_vital_unit_result')"></p>
                </div>

                <div class="d-flex flex-column rows-gap-16 mt-6 px-4"
                     v-if="!layoutStore.isLoading && vitalSignHistories.length >= 10" @click="loadMore">
                    <button type="button" class="btn btn-default">{{ $t('history.load_more') }}</button>
                </div>

            </section>
            <section class="tab-pane fade" :class="selectedTab === 'resep-obat' ? 'show active' : ''" id="resep-obat" role="tabpanel" aria-labelledby="resep-obat" tabindex="0">
                <div class="d-flex flex-column rows-gap-16 mt-4" v-for="history in prescriptionHistories">
                    <PrescriptionCard
                        :prescription-no="history.PrescriptionNo"
                        :prescription-date_y-md-hms="history.prescriptionDate_yMdHms"
                        :paramedic-name="history.paramedicName" />
                </div>
                <div class="text-center mt-3" v-if="prescriptionHistories.length < 1 && !layoutStore.isLoading">
                    <img :src="DoctorImage" alt="Ilustrasi Tidak Ada Data"
                         width="238" height="198" class="d-inline-block">
                    <p class="mt-4 fs-3 fw-bold">{{ $t('history.no_prescription') }}</p>
                </div>
                <div class="d-flex flex-column rows-gap-16 mt-6 px-4" v-if="!layoutStore.isLoading && vitalSignHistories.length >= 10" @click="loadMore">
                    <button type="button" class="btn btn-default">{{ $t('history.load_more') }}</button>
                </div>
            </section>
            <section class="tab-pane fade" :class="selectedTab === 'hasil-lab' ? 'show active' : ''" id="hasil-lab" role="tabpanel" aria-labelledby="hasil-lab" tabindex="0">
                <div class="d-flex flex-column rows-gap-16 mt-4" v-for="result in labResultHistories">
                    <LabResultCard
                        paramedic-name="-"
                        :sequence-no="result.sequenceNo"
                        :date="result.executionDate_yMdHms"
                        :transaction-no="result.transactionNo"
                        :registration-no="result.registrationNo"
                        :age="result.age"
                        :sex="result.sex"
                        :isLabResultBridging="isLabResultFile"/>
                </div>
                <div class="text-center mt-3" v-if="labResultHistories.length < 1 && !layoutStore.isLoading">
                    <img :src="LabImage" alt="Ilustrasi Tidak Ada Data"
                         width="238" height="198" class="d-inline-block">
                    <p class="mt-4 fs-3 fw-bold">{{ $t('history.no_lab_result') }}</p>
                </div>
                <div class="d-flex flex-column rows-gap-16 mt-4 text-center">
                    <p>Apabila membutuhkan informasi lebih lanjut hubungi call center RS :</p>
                    <p class="fw-bold">{{ $t('history.callcenter') }}</p>
                </div>
                
                <div class="d-flex flex-column rows-gap-16 mt-6 px-4" v-if="!layoutStore.isLoading && vitalSignHistories.length >= 10" @click="loadMore">
                    <button type="button" class="btn btn-default">{{ $t('history.load_more') }}</button>
                </div>
            </section>
            <section class="tab-pane fade" :class="selectedTab === 'pertemuan' ? 'show active' : ''" id="pertemuan" role="tabpanel" aria-labelledby="pertemuan" tabindex="0">
                <div class="d-flex flex-column rows-gap-16 mt-4" v-for="result in encounterHistories">
                    <EncounterCard
                        :registration-no="result.registrationNo"
                        :date="result.registrationDate_yMdHms"
                        :paramedic-name="result.paramedicName"/>
                </div>
                <div class="text-center mt-3" v-if="encounterHistories.length < 1 && !layoutStore.isLoading">
                    <img :src="Doctor2Image" alt="Ilustrasi Tidak Ada Data"
                         width="238" height="198" class="d-inline-block">
                    <p class="mt-4 fs-3 fw-bold">{{ $t('history.no_encounter') }}</p>
                </div>
                <div class="d-flex flex-column rows-gap-16 mt-6 px-4" v-if="!layoutStore.isLoading && vitalSignHistories.length >= 10" @click="loadMore">
                    <button type="button" class="btn btn-default">{{ $t('history.load_more') }}</button>
                </div>
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
                            <select v-model="selectedFamilyMemberId" name="member" id="member" class="form-select mt-2">
                                <option v-for="(member, index) in families" :value="member.id">{{ member.name }}</option>
                            </select>
                        </div>
                        <div class="d-flex col-gap-20">
                            <button type="reset" @click="resetFamilyFilter" class="w-50 btn btn-red-500-rounded"
                                data-bs-dismiss="modal">{{
                                    $t('history.modal_filter.reset') }}</button>
                            <button type="button" @click="filterByFamily" class="w-50 btn btn-blue-500-rounded" :class="selectedFamilyMemberId === 0 ? 'disabled' : ''"
                                data-bs-dismiss="modal">{{
                                    $t('history.modal_filter.apply') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
