<script setup>
import Header from "@shared/Components/Header/Header.vue";
import {useDoctorScheduleStore} from "@patient/+store/doctor-schedule.store.js";
import {storeToRefs} from "pinia";
import axios from "axios";
import {useLayoutStore} from "@shared/+store/layout.store.js";
import {onMounted, watch} from "vue";
import {useServiceUnitStore} from "@patient/+store/service-unit.store.js";

const layoutStore = useLayoutStore();
const {isLoading} = storeToRefs(layoutStore);
const doctorScheduleStore = useDoctorScheduleStore();
const { schedules, selectedDate, selectedServiceUnitId} = storeToRefs(doctorScheduleStore);
const serviceUnitStore = useServiceUnitStore();
const {serviceUnits} = storeToRefs(serviceUnitStore);

const fetchDoctorSchedules = () => {
    layoutStore.updateLoadingState(true);
    axios.get(`/api/v1/patient/doctor/schedules`, {
        params: {
            date: `${selectedDate.value}`,
            service_unit_id: `${selectedServiceUnitId.value}`
        }
    }).then((response) => {
        const data = response.data;
        doctorScheduleStore.updateSchedules(data.schedules);
        serviceUnitStore.updateServiceUnits(data.service_units);
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
            layoutStore.updateLoadingState(false);
    });
}

const selectParamedicId = (id) => {
   doctorScheduleStore.updateSelectedParamedicId(id);
}

watch(selectedDate, (newValue, oldValue) => {
  doctorScheduleStore.updateSelectedDate(newValue);
  fetchDoctorSchedules();
});

watch(selectedServiceUnitId, (newValue, oldValue) => {
  doctorScheduleStore.updateSelectedServiceUnitId(newValue);
  fetchDoctorSchedules();
});

onMounted(() => {
    fetchDoctorSchedules();
});
</script>

<template>
    <Header title="Jadwal Dokter" :with-back-url="true"></Header>
    <div class="px-4 pt-8 mt-4">
        <section class="d-flex col-gap-20 justify-content-between">
            <div>
                <label for="unit" class="fs-5 text-gray-700">Unit</label>
                <select v-model="selectedServiceUnitId" name="unit" id="unit" class="form-select mt-2">
                    <option value="">Semua Unit</option>
                    <option v-for="unit in serviceUnits" :value="unit.serviceUnitID">{{ unit.serviceUnitName }}</option>
                </select>
            </div>
            <div>
                <label for="tanggal" class="fs-5 text-gray-700">Tanggal</label>
                <input v-model="selectedDate" type="date" name="tanggal" id="tanggal" class="form-control mt-2">
            </div>
        </section>
        <section class="d-flex flex-column rows-gap-16 mt-4" v-if="!isLoading">
            <div class="bg-blue-100 rounded px-4 py-3" v-for="schedule in schedules">
                <p class="fw-semibold">{{ schedule.paramedicName }}</p>
                <p class="mt-2 fs-5 text-gray-700">{{ schedule.serviceUnitName }}</p>
                <router-link @click="selectParamedicId(schedule.paramedicID)" :to="{name: 'DoctorScheduleDetailPage', query: {paramedicId: schedule.paramedicID}}" class="d-block btn btn-blue-500-rounded-sm mt-3">Detail</router-link>
            </div>
        </section>
        <div class="text-center mt-3" v-if="isLoading">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</template>
