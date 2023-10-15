<script setup>
import { toRefs } from 'vue';
import { convertDateTimeToDate } from "@shared/utils/helpers";
import {useMedicalHistoryStore} from "@patient/+store/medical-history.store.js";
import router from "@patient/router.js";

const medicalHistoryStore = useMedicalHistoryStore();
const props = defineProps({
    prescriptionNo: String,
    prescriptionDate_yMdHms: String,
    paramedicName: String
});
const { prescriptionNo, prescriptionDate_yMdHms, paramedicName } = toRefs(props);

const setSelectedPrescription = (prescription) => {
    medicalHistoryStore.updateSelectedPrescription(prescription);
}

</script>
<template>
    <div class="d-flex flex-column rows-gap-16 rounded-3 p-3 bg-blue-100 border border-blue-200">
        <div class="d-flex justify-between">
            <div class="w-50">
                <p class="fs-6 text-gray-700">No. Resep</p>
                <p class="mt-2 fs-5 fw-semibold">{{ prescriptionNo }}</p>
            </div>

            <div class="w-50 text-end">
                <p class="fs-6 text-gray-700">Tanggal Resep</p>
                <p class="mt-2 fs-5 fw-semibold">{{ convertDateTimeToDate(prescriptionDate_yMdHms) }}</p>
            </div>
        </div>

        <div>
            <p class="fs-6 text-gray-700">Nama Dokter</p>
            <p class="mt-2 fs-5 fw-semibold">
            </p><p class="mt-2 fs-5 fw-semibold">{{ paramedicName }}</p>
            <p></p>
        </div>

        <router-link
            @click="setSelectedPrescription({prescriptionNo: prescriptionNo,
                    prescriptionDate: prescriptionDate_yMdHms, paramedicName: paramedicName})"
            :to="{name: 'PrescriptionDetailPage', query: {prescriptionNo: prescriptionNo}}" class="btn btn-blue-500-rounded-sm mt-2">Detail</router-link>
    </div>
</template>
