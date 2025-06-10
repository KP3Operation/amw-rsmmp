<script setup>
import { useMedicalHistoryStore } from "@patient/+store/medical-history.store.js";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import Header from "@shared/Components/Header/Header.vue";
import apiRequest from "@shared/utils/axios.js";
import { convertDateTimeToDateTime } from "@shared/utils/helpers.js";
import { storeToRefs } from "pinia";
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";

const layoutStore = useLayoutStore();
const { isLoading } = storeToRefs(layoutStore);
const transactionNo = ref("");
const radResultDetails = ref([]);
const radResultPatient = ref(null);
const route = useRoute();
const medicalHistoryStore = useMedicalHistoryStore();
const { selectedRadResult } = storeToRefs(medicalHistoryStore);

const fetchRadResultDetail = () => {
    layoutStore.isLoading = true;
    apiRequest
        .get(
            `/api/v1/patient/medical/history/radresult/detail?transaction_no=${transactionNo.value}`
        )
        .then((response) => {
            let resValue = response.data;
            radResultPatient.value = resValue.patient;
            radResultDetails.value = resValue.data[0];
        })
        .catch((error) => {
            layoutStore.toggleErrorAlert(`${error.response.data.message}`);
        })
        .finally(() => {
            layoutStore.isLoading = false;
        });
};

onMounted(() => {
    transactionNo.value = route.query["transactionNo"];
    fetchRadResultDetail();
});
</script>

<template>
    <Header
        :title="$t('history.rad_result_detail.title')"
        :with-back-url="true"
        page-name="HistoryPage"
    ></Header>
    <div class="px-4 pt-8 mt-4">
        <div class="d-flex flex-column rows-gap-16 mt-2">
            <div class="pb-3 border-bottom border-gray-400">
                <p class="fs-6 text-gray-700">{{ $t('history.rad_result_detail.patient_name') }}</p>
                <p class="mt-2">{{ radResultPatient?.name }}</p>
            </div>

            <div class="pb-3 border-bottom border-gray-400">
                <p class="fs-6 text-gray-700">{{ $t('history.rad_result_detail.transaction_no') }}</p>
                <p class="mt-2">{{ radResultDetails.transactionNo }}</p>
            </div>

            <div class="pb-3 border-bottom border-gray-400">
                <p class="fs-6 text-gray-700">{{ $t('history.rad_result_detail.result_date') }}</p>
                <p class="mt-2">
                    {{ convertDateTimeToDateTime(radResultDetails.testResultDate) }}
                </p>
            </div>

            

            <div class="pb-3">
                <p class="fs-6 text-gray-700">{{ $t('history.rad_result_detail.rad_result') }}</p>
                <div class="mt-2 pb-3 rounded-3 p-3 bg-blue-100 border border-blue-200">
                    <div class="pb-3 border-bottom border-gray-400">
                        <p class="fs-6 text-gray-700">{{ $t('history.rad_result_detail.item_name') }}</p>
                        <p class="mt-2">{{ radResultDetails.itemID }} - {{ radResultDetails.itemName }}</p>
                    </div>
                    <p class="mt-2" v-html="radResultDetails.testResult" style="font-size: 14px;"></p>
                </div>
                <div class="text-center mt-3" v-if="isLoading">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
