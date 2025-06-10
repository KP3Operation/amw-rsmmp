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
const fileUrl = ref("");
const radResultDetails = ref([]);
const route = useRoute();
const medicalHistoryStore = useMedicalHistoryStore();
const { selectedRadResult } = storeToRefs(medicalHistoryStore);

const fetchLabResultFile = () => {
    layoutStore.isLoading = true;
    apiRequest
        .get(
            `/api/v1/patient/medical/history/radresult/file/${transactionNo.value}`
        )
        .then((response) => {
            console.log(response);
            fileUrl.value = response.data.data;
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
    fetchLabResultFile();
});
</script>
<template>
    <Header
        :title="$t('history.lab_result_view.title')"
        :with-back-url="true"
        page-name="HistoryPage"
    ></Header>
    <div class="px-4 pt-8 mt-2">
        <!-- <div class="d-flex flex-column rows-gap-16 mt-2">
            <div class="pb-3">
                <p class="mt-1" style="padding:0;margin:0;"><span style="font-weight:bold;">No.Transaksi : </span>{{ selectedLabResult.transactionNo }}</p>
                <p class="mt-1" style="padding:0;margin:0;">
                    <span style="font-weight:bold;">Tanggal : </span>
                    {{
                        convertDateTimeToDateTime(
                            selectedLabResult.executionDate
                        )
                    }}
                </p>
            </div>
        </div> -->
    </div>    
    <div class="container-fluid" style="padding:0;">
        <iframe :src="fileUrl" frameborder="0" style="width:100%;height: 90vh;"></iframe>
    </div>
</template>