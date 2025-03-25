<script setup>
import Header from "@shared/Components/Header/Header.vue";
import {useLayoutStore} from "@shared/+store/layout.store.js";
import {storeToRefs} from "pinia";
import {onMounted, reactive, ref} from "vue";
import {useRoute} from "vue-router";
import {convertDateTimeToDate} from "@shared/utils/helpers.js";
import apiRequest from "@shared/utils/axios.js";

const layoutStore = useLayoutStore();
const {isLoading} = storeToRefs(layoutStore);
const registrationNo = ref("");
const encounterDetail = ref({});
const encountersDetail = ref([]);
const route = useRoute();

const fetchEncounterHistoryDetail = (registrationNo) => {
    layoutStore.isLoading = true;
    apiRequest.get(`/api/v1/patient/medical/history/encounters/details?registration_no=${registrationNo}`).then((response) => {
        const data = response.data;
        encounterDetail.value = data.details[0];
        encountersDetail.value = data.details;
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = false;
    });
}

onMounted(() => {
    registrationNo.value = route.query['registrationNo'];
    fetchEncounterHistoryDetail(registrationNo.value);
});
</script>

<template>
    <Header :title="$t('history.encounter_detail.title')" :with-back-url="true" page-name="HistoryPage"></Header>
    <div class="px-4 pt-8">
        <div class="bg-white mt-4">
            <div class="header-detail-encounter">
                <div class="unit">
                    <!-- <p class="note">{{ encounterDetail.serviceUnitName }}</p> -->
                    <p class="name">{{ encounterDetail.paramedicName }}</p>
                </div>

                <div class="queue">
                    <p class="note">{{ $t('history.encounter_detail.queue_no') }}</p>
                    <p class="name">{{ encounterDetail.registrationQue }}</p>
                </div>
            </div>

            <div class="d-flex flex-column rows-gap-16 mt-3 px-4">
                <div class="d-flex justify-content-between pb-3 border-bottom border-gray-400">
                    <div class="w-50">
                        <p class="fs-6 text-gray-700">{{ $t('history.encounter_detail.registration_no') }}</p>
                        <p class="mt-2">{{ encounterDetail.registrationNo }}</p>
                    </div>

                    <div class="w-50 text-end">
                        <p class="fs-6 text-gray-700">{{ $t('history.encounter_detail.consultation_date') }}</p>
                        <!-- <p class="mt-2">{{ encounterDetail.guarantorName }}</p> -->
                        <p class="mt-2">
                            {{ convertDateTimeToDate(encounterDetail.registrationDate_yMdHms) }} {{ encounterDetail.registrationTime }}
                        </p>
                         
                    </div>
                </div>
                <div class="pb-3 border-bottom border-gray-400">
                    <p class="fs-6 text-gray-700">{{ $t('history.encounter_detail.guarantor_name') }}</p>
                    <p class="mt-2">{{ encounterDetail.guarantorName }}</p>
                </div>
                <div class="pb-3 border-bottom border-gray-400">
                    <p class="fs-6 text-gray-700">{{ $t('history.encounter_detail.service_unit') }}</p>
                    <p class="mt-2">{{ encounterDetail.serviceUnitName }}</p>
                </div>
                <!-- <div class="pb-3 border-bottom border-gray-400">
                    <p class="fs-6 text-gray-700">{{ $t('history.encounter_detail.visit_type') }}</p>
                    <p class="mt-2">{{ encounterDetail.visitTypeName }}</p>
                </div> -->

                <div class="pb-3">
                    <p class="fs-6 text-gray-700">{{ $t('history.encounter_detail.diagnosis') }}</p>

                    <div v-if="!isLoading" class="accordion d-flex flex-column rows-gap-16 mt-3" id="accordion" v-for="(encounter, index) in encountersDetail">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="'#diagnosis-'+index" aria-expanded="false" aria-controls="diagnosis-1">
                                    {{ encounter.diagnoseID }}
                                </button>
                            </h2>
                            <div :id="'diagnosis-'+index" class="accordion-collapse collapse show" :data-bs-parent="'#diagnosis-'+index">
                                <div class="accordion-body">
                                    <div class="accordion-divider"></div>
                                    <p class="fs-5 mt-3">{{ encounter.diagnoseID }} : {{ encounter.diagnosisText }}</p>
                                    <p class="mt-3 fs-5 text-gray-700">{{ $t('history.encounter_detail.chiefComplain') }}</p>
                                    <p class="mt-2 fs-5">
                                        {{ encounter.chiefComplaint }}
                                    </p>
                                    <p class="mt-3 fs-5 text-gray-700">{{ $t('history.encounter_detail.currentHistory') }}</p>
                                    <p class="mt-2 fs-5">
                                        {{ encounter.hpi }}
                                    </p>
                                    <p class="mt-3 fs-5 text-gray-700">{{ $t('history.encounter_detail.medikamentosaHistory') }}</p>
                                    <p class="mt-2 fs-5">
                                        {{ encounter.medikamentosa }}
                                    </p>
                                    <p class="mt-3 fs-5 text-gray-700">{{ $t('history.encounter_detail.generalCondition') }}</p>
                                    <p class="mt-2 fs-5">
                                        {{ encounter.generalCondition }}
                                    </p>
                                    <p class="mt-3 fs-5 text-gray-700">{{ $t('history.encounter_detail.conscious') }}</p>
                                    <p class="mt-2 fs-5">
                                        {{ encounter.conscious }}
                                    </p>
                                    <p class="mt-3 fs-5 text-gray-700">{{ $t('history.encounter_detail.otherExamination') }}</p>
                                    <p class="mt-2 fs-5">
                                        {{ encounter.otherExam }}
                                    </p>
                                    <p class="mt-3 fs-5 text-gray-700">{{ $t('history.encounter_detail.notes') }}</p>
                                    <p class="mt-2 fs-5">
                                        {{ encounter.notes }}
                                    </p>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3" v-if="isLoading">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
