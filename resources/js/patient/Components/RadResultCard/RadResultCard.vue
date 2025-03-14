<script setup>
import {convertDateTimeToDate} from "@shared/utils/helpers.js";
import {toRefs} from "vue";
import {useMedicalHistoryStore} from "@patient/+store/medical-history.store.js";

// const isLabResultFile = ref(false)

const props = defineProps({
    paramedicName: String,
    transactionNo: String,
    registrationNo : String,
    resultDate: String,
    itemName: String,
    itemID: String,
    testResult:String,
    testSummary:String,
    testSuggest:String,

    isRadResultBridging : {
        type : Boolean, 
        default : false
    }
});
const { paramedicName,transactionNo,registrationNo, resultDate, itemName,isRadResultBridging } = toRefs(props);
const medicalHistoryStore = useMedicalHistoryStore();

const setSelectedRadResult = (radResult) => {
   medicalHistoryStore.updateSelectedRadResult(radResult);
}

</script>

<template>
    <div class="d-flex flex-column rows-gap-16 rounded-3 p-3 bg-blue-100 border border-blue-200">
        <div class="d-flex justify-between">
            <div class="w-50">
                <p class="fs-6 text-gray-700">No. Pemeriksaan</p>
                <p class="mt-2 fs-5 fw-semibold">{{ transactionNo }}</p>
            </div>
            <div class="w-50 text-end">
                <p class="fs-6 text-gray-700">No. Registrasi</p>
                <p class="mt-2 fs-5 fw-semibold">{{ registrationNo }}</p>
            </div>
        </div>
        <div class="w-100">
            <p class="fs-6 text-gray-700">Dokter Pembaca</p>
            <p class="mt-2 fs-5 fw-semibold">{{ paramedicName }}</p>
        </div>
        <div class="w-100">
            <p class="fs-6 text-gray-700">Tanggal Hasil</p>
            <p class="mt-2 fs-5 fw-semibold">{{ resultDate }}</p>
        </div>
            
        <div class="row gx-5">
            <div class="col">
                <router-link @click="setSelectedRadResult({
                    itemID : itemID,
                    itemName : itemName,
                    readBy : paramedicName,
                    registrationNo : registrationNo,
                    transactionNo : transactionNo,
                    testResult : testResult,
                    testSummary : testSummary,
                    testResultDate : resultDate,
                    testSuggest : testSuggest,})"
                     :to="{name: 'RadResultDetailPage', query: {transactionNo: transactionNo}}" class="btn w-100 btn-blue-500-rounded-sm mt-1">Detail</router-link>
            </div>
        </div>
    </div>
</template>
