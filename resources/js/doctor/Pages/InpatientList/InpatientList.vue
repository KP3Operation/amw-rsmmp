<script setup>
import Header from "@shared/Components/Header/Header.vue";
import {onMounted, reactive, ref} from "vue";
import {useLayoutStore} from "@shared/+store/layout.store.js";
import Form from "vform";
import InpatientListCard from "@doctor/Components/InpatientListCard/InpatientListCard.vue";
import {useInpatientStore} from "@doctor/+store/inpatient.store.js";
import {storeToRefs} from "pinia";

const inpatientStore = useInpatientStore();
const { patients, patientCount } = storeToRefs(inpatientStore);
const filterForm = reactive(
    new Form({
        room_name: null
    })
);
const layoutStore = useLayoutStore();
const filterInpatientList = () => {
    layoutStore.isLoading = false;
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

const setSelectedPatient = (patient) => {
    inpatientStore.setSelectedPatient(patient);
}

onMounted(() => {
    filterInpatientList();
});

</script>

<template>
    <div>
        <Header title="List Pasien Rawat Inap" :with-back-url="true" page-name="InpatientListPage"></Header>
        <section
            class="filter-inpatient filter-sticky-2 d-flex align-items-center justify-content-between col-gap-20 p-4 mt-6 bg-white position-sticky">
            <p class="w-50">{{ patientCount }} Data Pasien</p>
            <div id="multiselect" class="w-50 dropdown filter-sticky d-flex col-gap-20 align-items-center p-0">
                <button
                    class="d-flex align-items-center w-100 px-3 py-2 border border-gray-400 rounded-3 bg-white justify-content-between text-black"
                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span>Semua Ruangan</span>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <ul class="dropdown-menu px-4 py-3 shadow rounded-0 border-0 mt-2">
                    <li>
                        <input class="form-check-input on" type="checkbox" name="filter" value="Semua" id="semua"
                               checked>
                        <label class="form-check-label" for="semua">
                            Semua Ruangan
                        </label>
                    </li>
                    <li class="mt-3">
                        <input class="form-check-input" type="checkbox" name="filter" value="Anggrek" id="anggrek"
                               checked>
                        <label class="form-check-label" for="anggrek">
                            Anggrek
                        </label>
                    </li>
                    <li class="mt-3">
                        <input class="form-check-input" type="checkbox" name="filter" value="Melati" id="melati"
                               checked>
                        <label class="form-check-label" for="melati">
                            Melati
                        </label>
                    </li>
                    <li class="mt-3">
                        <input v-model="filterForm.room_name" class="form-check-input" type="checkbox" name="filter" value="ICU" id="icu" checked>
                        <label class="form-check-label" for="icu">
                            ICU
                        </label>
                    </li>
                </ul>
            </div>
        </section>
        <div class="d-flex flex-column rows-gap-16 mt-6 px-4">
            <div v-for="(patient, index) in patients">
                <InpatientListCard
                    :id="index.toString()"
                    :registrationNo="patient.RegistrationNo"
                    :medicalNo="patient.MedicalNo"
                    :patientName="patient.PatientName"
                    :roomName="patient.RoomName"
                    @click="setSelectedPatient(patient)"/>
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
    </div>
</template>
