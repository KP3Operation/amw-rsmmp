<script>
import { onMounted, ref } from "vue";
import apiRequest from "@shared/utils/axios.js";
import { useLayoutStore } from "@shared/+store/layout.store.js";

export default {
    setup(){
        const patientGuarantors = ref([])
        const layoutStore = useLayoutStore();

        const getPatientGuarantors = () => {
            layoutStore.isLoading = true;
            apiRequest.get(`/api/v1/doctor/guarantor/summary`).then((response) => {
                const data = response.data;
                patientGuarantors.value = data.summary;
            }).catch((error) => {
                layoutStore.toggleErrorAlert(`${error.response.data.message}`);
            }).finally(() => {
                layoutStore.isLoading = false;
            })
        }

        onMounted(() => {
            getPatientGuarantors();
        });

        return { patientGuarantors }
    }

}
</script>
<template>
    <section class="mt-3">
        <h2 class="fs-3 fw-bold text-black">{{ $t('home.guarantor_summary') }}</h2>
        <div class="pt-3 mb-3">
            <template v-if="patientGuarantors.length > 0">
                <div v-for="(dt,index) in patientGuarantors" class="guarantor-summary-item">
                    <p class="title">{{ dt.name }}</p>
                    <p class="info">{{ dt.value }} orang</p>
                </div>
            </template>
            <template v-else>
                <div style="text-align: center;">
                    <p style="font-size: 14px; font-style: italic;">belum ada data untuk ditampilkan</p>
                </div>
            </template>
        </div>
    </section>
</template>

<style scoped>
.guarantor-summary-item {
    background-color:#f0f0ff; 
    display:flex; 
    justify-content: space-between; 
    margin-top:4px; 
    padding:0.5em; 
    border-radius:10px; 
    border:1px solid #f0f0ff; 
    padding:1em;
}

.guarantor-summary-item .title {
    font-weight: 700;
    font-size: 14px;
    text-wrap: wrap;
    margin-left: 5px;
}
.guarantor-summary-item .info {
    font-size: 14px;
    text-align: center;
    text-wrap: nowrap;
}

</style>