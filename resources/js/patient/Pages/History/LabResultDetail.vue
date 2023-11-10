<script setup>
import Header from "@shared/Components/Header/Header.vue";
import {useMedicalHistoryStore} from "@patient/+store/medical-history.store.js";
import {storeToRefs} from "pinia";
import {onMounted, ref} from "vue";
import {useRoute} from "vue-router";
import axios from "axios";
import {useLayoutStore} from "@shared/+store/layout.store.js";
import {convertDateTimeToDateTime} from "@shared/utils/helpers.js";
import apiRequest from "@shared/utils/axios.js";

const layoutStore = useLayoutStore();
const {isLoading} = storeToRefs(layoutStore);
const transactionNo = ref("");
const labResultDetails = ref([]);
const route = useRoute();
const medicalHistoryStore = useMedicalHistoryStore();
const {selectedLabResult} = storeToRefs(medicalHistoryStore);

const fetchLabResultDetail = () => {
    layoutStore.isLoading = true;
    apiRequest.get(`/api/v1/patient/medical/history/labresult/detail?transaction_no=${transactionNo.value}`).then((response) => {
        labResultDetails.value = response.data.data;
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = false;
    });
}

onMounted(() => {
    transactionNo.value = route.query['transactionNo'];
    fetchLabResultDetail();
});
</script>

<template>
    <Header title="Detail Hasil Lab" :with-back-url="true" page-name="HistoryPage"></Header>
    <div class="px-4 pt-8 mt-4">
        <div class="d-flex flex-column rows-gap-16 mt-2">
            <div class="pb-3 border-bottom border-gray-400">
                <p class="fs-6 text-gray-700">No. Pemeriksaan</p>
                <p class="mt-2">{{ selectedLabResult.transactionNo }}</p>
            </div>

            <div class="pb-3 border-bottom border-gray-400">
                <p class="fs-6 text-gray-700">Waktu Pemeriksaan</p>
                <p class="mt-2">{{ convertDateTimeToDateTime(selectedLabResult.executionDate) }}</p>
            </div>

            <div class="pb-3 border-bottom border-gray-400">
                <p class="fs-6 text-gray-700">Umur Pasien</p>
                <p class="mt-2">{{ selectedLabResult.age }} Tahun</p>
            </div>

            <div class="pb-3 border-bottom border-gray-400">
                <p class="fs-6 text-gray-700">Jenis Kelamin</p>
                <p class="mt-2">{{ selectedLabResult.gender === 'M' ? 'Laki-Laki' : 'Perempuan' }}</p>
            </div>

            <div class="pb-3">
                <p class="fs-6 text-gray-700">Hasil Pemeriksaan</p>

                <div v-if="!isLoading" class="accordion d-flex flex-column rows-gap-16 mt-3" id="accordion" v-for="(result, index) in labResultDetails">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="'#hasil-tes-' + index" aria-expanded="false" aria-controls="hasil-tes-1">
                                {{ result.testName }}
                            </button>
                        </h2>

                        <div :id="'hasil-tes-'+index" class="accordion-collapse collapse" :data-bs-parent="'#hasil-tes-'+index">
                            <div class="accordion-body">
                                <div class="accordion-divider"></div>
                                <div class="py-3">
                                  <p v-if="result.resultValue === ''">
                                    <i>Hasil pemeriksaan belum tersedia</i>
                                  </p>
                                  <p v-if="result.resultValue !== ''">
                                      <span class="me-3">Hasil</span>: {{ result.resultValue }}<br>
                                      <span class="me-3">Nilai Normal</span>:{{ result.normalValueMin }} - {{ result.normalValueMax }}
                                  </p>
                                </div>
                                <div class="accordion-divider my-3"></div>

                                <p class="fs-5 text-gray-700">Catatan</p>

                                <p class="mt-2 fs-5" v-if="result.notes !== ''">{{ result.notes }}</p>
                                <p class="mt-2 fs-5" v-if="result.notes === ''">
                                  <i>Catatan Klinis Dokter Pemeriksa</i>
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
</template>
