<script setup>
import { toRefs } from "vue";

const props = defineProps({
    id: String,
    registrationNo: String,
    medicalNo: String,
    patientName: String,
    roomName: String
});

let { id, registrationNo, medicalNo, patientName, roomName } = toRefs(props);

</script>

<template>
    <div :id="'item-' + id" class="item new-data-bg d-flex flex-column rows-gap-16 border rounded px-4 py-3">
        <div class="d-flex align-items-center justify-content-between col-gap-20">
            <div class="w-50">
                <p class="fs-6 text-gray-700">{{ $t('inpatient_list_card.rregistration_no') }}</p>
                <p class="mt-2">{{ registrationNo }}</p>
            </div>
            <div class="w-50 text-end">
                <p class="fs-6 text-gray-700">{{ $t('inpatient_list_card.medical_no') }}</p>
                <p class="mt-2">{{ medicalNo }}</p>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between col-gap-20">
            <div class="w-50">
                <p class="fs-6 text-gray-700">{{ $t('inpatient_list_card.patient_name') }}</p>
                <p class="mt-2">{{ patientName }}</p>
            </div>
            <div class="w-50 text-end">
                <p class="fs-6 text-gray-700">{{ $t('inpatient_list_card.room') }}</p>
                <p class="mt-2">{{ roomName }}</p>
            </div>
        </div>
        <router-link to="/inpatient/detail?" class="d-block btn btn-blue-500-rounded-sm">{{ $t('inpatient_list_card.detail') }}</router-link>
    </div>
</template>
