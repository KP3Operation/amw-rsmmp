<script setup>
import {convertDateTimeToDate} from "@shared/utils/helpers.js";
import {toRefs} from "vue";
import {useMedicalHistoryStore} from "@patient/+store/medical-history.store.js";

// const isLabResultFile = ref(false)

const props = defineProps({
    paramedicName: String,
    sequenceNo: String,
    date: String,
    transactionNo: String,
    registrationNo : String,
    gender: String,
    age: String,
    isLabResultBridging : {
        type : Boolean, 
        default : false
    }
});
const { paramedicName, sequenceNo, date, transactionNo,registrationNo, gender, age } = toRefs(props);
const medicalHistoryStore = useMedicalHistoryStore();

const setSelectedLabResult = (labResult) => {
   medicalHistoryStore.updateSelectedLabResult(labResult);
}

// const initialize = function(){
//     if(import.meta.env.VITE_IS_LAB_RESULT_BRIDGING === 'true' || import.meta.env.VITE_IS_LAB_RESULT_BRIDGING === 'TRUE'){
//         isLabResultFile.value = true;
//     }
//     else { 
//         isLabResultFile.value = false; 
//     }
// }

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
                <!-- <p class="mt-2 fs-5 fw-semibold">{{ convertDateTimeToDate(date) }}</p> -->
                <p class="mt-2 fs-5 fw-semibold">{{ registrationNo }}</p>
            </div>
        </div>
        <div class="row gx-5">
            <div class="col" v-if="isLabResultBridging">
                <router-link @click="setSelectedLabResult({sequenceNo: sequenceNo,
                        executionDate: date, age: age, gender: gender, transactionNo: transactionNo})"
                    :to="{name: 'LabResultViewPage', query: {transactionNo: transactionNo}}" class="btn w-100 btn-blue-500-rounded-sm mt-1">View</router-link>
            </div>
            <div class="col" v-else>
                <router-link @click="setSelectedLabResult({sequenceNo: sequenceNo,
                    executionDate: date, age: age, gender: gender, transactionNo: transactionNo})"
                        :to="{name: 'LabResultDetailPage', query: {transactionNo: transactionNo}}" class="btn w-100 btn-blue-500-rounded-sm mt-1">Detail</router-link>
            </div>
        </div>
    </div>
</template>
