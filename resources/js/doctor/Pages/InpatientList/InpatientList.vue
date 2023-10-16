<script setup>
import Header from "@shared/Components/Header/Header.vue";
import {onMounted, reactive, ref, watch} from "vue";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import Form from "vform";
import InpatientListCard from "@doctor/Components/InpatientListCard/InpatientListCard.vue";
import { useInpatientStore } from "@doctor/+store/inpatient.store.js";
import { storeToRefs } from "pinia";
import NotFoundImage from "@resources/static/images/not-found.png";

const inpatientStore = useInpatientStore();
const { patients, patientCount, selectedRegistrationNo } = storeToRefs(inpatientStore);
const filterForm = reactive(
    new Form({
        room_name: ""
    })
);
const layoutStore = useLayoutStore();
const { isLoading } = storeToRefs(layoutStore);
const selectedRoomName = ref("");

const filterInpatientList = () => {
    layoutStore.isLoading = true;
    filterForm.get('/api/v1/doctor/inpatient').then((response) => {
        const data = response.data;
        patients.value = data.patients;
        patientCount.value = patients.value.length;
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = false;
    })
}

watch(selectedRoomName, (newValue, oldValue) => {
    filterForm.fill({
        room_name: newValue
    });
    filterInpatientList();
});

const setSelectedPatient = (patient) => {
    selectedRegistrationNo.value = patient.registrationNo;
    inpatientStore.setSelectedPatient(patient);
}

onMounted(() => {
    inpatientStore.$reset();
    filterInpatientList();
});
</script>

<template>
    <Header title="List Pasien Rawat Inap" :with-back-url="true"></Header>
    <section
        class="filter-inpatient filter-sticky-2 d-flex align-items-center justify-content-between col-gap-20 p-4 mt-6 bg-white position-sticky">
        <p class="w-50"><span v-if="patientCount !== 0">{{ patientCount }} Data Pasien</span></p>
        <div id="multiselect" class="w-50 dropdown filter-sticky d-flex col-gap-20 align-items-center p-0">
            <select class="form-select" aria-label="Tipe" v-model="selectedRoomName">
                <option value="" selected>Semua Ruangan</option>
                <option value="anggrek">Anggrek</option>
                <option value="melati">Melati</option>
                <option value="icu">ICU</option>
            </select>
        </div>
    </section>
    <div class="text-center mt-5" v-if="isLoading">
        <br><br>
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="d-flex flex-column rows-gap-16 mt-6 px-4" v-if="!isLoading">
        <div v-for="(patient, index) in patients">
            <InpatientListCard :id="index.toString()" :registrationNo="patient.registrationNo"
                :medicalNo="patient.medicalNo" :patientName="patient.patientName" :roomName="patient.roomName"
                @click="setSelectedPatient(patient)" />
        </div>
        <div class="text-center mt-3" v-if="patientCount === 0">
            <img :src="NotFoundImage" alt="Ilustrasi Tidak Ditemukan" width="280" height="210">

            <p class="mt-4 fs-3 fw-bold">Anda Tidak Memiliki <br />
                Pasien Rawat Inap</p>
        </div>
    </div>
    <!-- TODO: Need to use pagination -->
    <!--        <div aria-label="Page navigation">-->
    <!--            <ul class="pagination justify-content-center mt-3">-->
    <!--                <li class="page-item disabled">-->
    <!--                    <a class="page-link">-->
    <!--                        Prev-->
    <!--                    </a>-->
    <!--                </li>-->
    <!--                <li class="page-item active"><a class="page-link" href="#">1</a></li>-->
    <!--                <li class="page-item"><a class="page-link" href="#">2</a></li>-->
    <!--                <li class="page-item"><a class="page-link" href="#">3</a></li>-->
    <!--                <li class="page-item">-->
    <!--                    <a class="page-link" href="#">Next</a>-->
    <!--                </li>-->
    <!--            </ul>-->
    <!--        </div>-->
</template>
