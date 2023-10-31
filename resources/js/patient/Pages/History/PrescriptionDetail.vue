<script setup>
import Header from "@shared/Components/Header/Header.vue";
import {useRoute} from "vue-router";
import {onMounted, ref} from "vue";
import axios from "axios";
import {useLayoutStore} from "@shared/+store/layout.store.js";
import {storeToRefs} from "pinia";
import {useMedicalHistoryStore} from "@patient/+store/medical-history.store.js";
import {convertDateTimeToDate} from "@shared/utils/helpers.js";
import apiRequest from "@shared/utils/axios.js";

/**
 * @type {Ref<UnwrapRef<string>>}
 */
const prescriptionNo = ref("");
const route = useRoute();
const layoutStore = useLayoutStore();
const { isLoading } = storeToRefs(layoutStore);
const medicalHistoryStore = useMedicalHistoryStore();
const { selectedPrescription } = storeToRefs(medicalHistoryStore);

/**
 * @typedef prescriprtion
 * @property {string} PrescriptionNo
 * @property {string} prescriptionDate_yMdHms
 * @property {string} itemID
 * @property {string} itemName
 * @property {string} prescriptionQty
 * @property {string} consumeMethod
 *
 * @type {Ref<UnwrapRef<prescriprtion[]>>}
 */
const prescriptions = ref([]);
const fetchPrescriptionHistoryDetail = (prescriptionNo) => {
    layoutStore.isLoading = true;
    apiRequest.get(`/api/v1/patient/medical/history/prescriptions/detail?prescription_no=${prescriptionNo}`).then((response) => {
        prescriptions.value = response.data.data;
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = true;
    });
}

onMounted(() => {
    prescriptionNo.value = route.query['prescriptionNo'];
    fetchPrescriptionHistoryDetail(prescriptionNo.value);
});
</script>
<template>
    <Header title="Detail Resep" :with-back-url="true" page-name="HistoryPage"></Header>
    <div class="pt-8 mt-4">
        <div class="d-flex flex-column rows-gap-16 mt-2">
            <div class="d-flex justify-content-between col-gap-20 px-4 pb-3 border-bottom border-gray-400">
                <div class="w-50 ">
                    <p class="fs-6 text-gray-700">Nomor Resep</p>
                    <p class="mt-2">{{ selectedPrescription.prescriptionNo }}</p>
                </div>

                <div class="w-50 text-end">
                    <p class="fs-6 text-gray-700">Tanggal Resep</p>
                    <p class="mt-2">{{ convertDateTimeToDate(selectedPrescription.prescriptionDate) }}</p>
                </div>
            </div>

            <div class="pb-3 px-4 border-bottom border-gray-400">
                <p class="fs-6 text-gray-700">Nama Dokter Pemberi Resep</p>
                <p class="mt-2">{{ selectedPrescription.paramedicName }}</p>
            </div>

            <!-- START LIST RESEP OBAT -->
            <div class="d-flex flex-column rows-gap-16 px-4" v-for="(prescription, index) in prescriptions">
                <div class="d-flex col-gap-8 pb-3 border-bottom border-gray-400">
                    <i class="bi bi-capsule icon-blue-500 fs-3"></i>
                    <div class="flex-fill">
                        <div class="d-flex col-gap-20 justify-content-between">
                            <div class="w-75">
                                <p class="fs-6 text-gray-700">Nama Obat</p>
                                <p class="mt-2">{{ prescription.itemName }}</p>
                            </div>

                            <div class="w-25 text-end">
                                <p class="fs-6 text-gray-700">Jumlah</p>
                                <p class="mt-2">{{ prescription.prescriptionQty }}</p>
                            </div>
                        </div>

                        <div class="mt-2">
                            <p class="fs-6 text-gray-700">Petunjuk</p>
                            <p class="mt-2">{{ prescription.consumeMethod }}</p>
                        </div>
                    </div>
                </div>


<!--                <div class="d-flex col-gap-8">-->
<!--                    <i class="bi bi-capsule icon-blue-500 fs-3"></i>-->

<!--                    <div class="flex-fill">-->
<!--                        <div class="d-flex col-gap-20 justify-content-between">-->
<!--                            <div class="w-75">-->
<!--                                <p class="fs-6 text-gray-700">Nama Obat</p>-->
<!--                                <p class="mt-2">Paracetamol</p>-->
<!--                            </div>-->

<!--                            <div class="w-25 text-end">-->
<!--                                <p class="fs-6 text-gray-700">Jumlah</p>-->
<!--                                <p class="mt-2">5 Tablet</p>-->
<!--                            </div>-->
<!--                        </div>-->

<!--                        <div class="mt-2">-->
<!--                            <p class="fs-6 text-gray-700">Dosis Obat</p>-->
<!--                            <p class="mt-2">500 mg</p>-->
<!--                        </div>-->

<!--                        <div class="mt-2">-->
<!--                            <p class="fs-6 text-gray-700">Petunjuk</p>-->
<!--                            <p class="mt-2">Minum dengan air hangat jika perlu, tidak lebih dari 4 tablet dalam sehari. Jangan digunakan lebih dari 3 hari berturut-turut.</p>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
        </div>
        <!-- END LIST RESEP OBAT-->
    </div>
    <!-- END CONTAINER -->
</template>
