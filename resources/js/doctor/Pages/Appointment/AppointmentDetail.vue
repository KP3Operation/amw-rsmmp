<script setup>
import Header from "@shared/Components/Header/Header.vue";
import axios from "axios";
import {convertDateTimeToDate, getCurrentDate} from "@shared/utils/helpers.js";
import {onMounted, ref, watch} from "vue";
import {useAppointmentStore} from "@doctor/+store/appointment.store.js";
import {storeToRefs} from "pinia";
import {useLayoutStore} from "@shared/+store/layout.store.js";
import AppointmentPage from "@doctor/Pages/Appointment/Appointment.vue";
import DoctorBlue from "@resources/static/icons/doctor-blue.svg";
import {useRoute} from "vue-router";

const layoutStore = useLayoutStore();
const { isLoading } = storeToRefs(layoutStore);
const appointmentStore = useAppointmentStore();
const {selectedDate, doctorAppointments} = storeToRefs(appointmentStore);
const route = useRoute();
const appointmentNo = ref("");
const appointmentDetail = ref({});

const fetchAppointmentDetail = () => {
    layoutStore.updateLoadingState(true);
    axios.get('/api/v1/doctor/appointments/detail', {
        params: {
            appointment_no: appointmentNo.value
        }
    }).then((response) => {
        const data = response.data;
        appointmentDetail.value = data.appointment;
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.updateLoadingState(false);
    });
}

onMounted(() => {
    appointmentNo.value = route.query['appointmentNo'];
    fetchAppointmentDetail();
});
</script>

<template>
    <Header title="Detail Appointment" :with-back-url="true" page-name="AppointmentPage"></Header>
    <div class="px-4 pt-8 mt-4">
        <div class="mt-2">
            <div class="rounded-top px-4 py-3 bg-blue-500 text-white">
                <div class="d-flex col-gap-8 align-items-center fw-bold">
                    <i class="bi bi-person-circle fs-2"></i>
                    <span>Pasien</span>
                </div>
                <div class="d-flex justify-content-between col-gap-20 mt-3">
                    <div class="w-50">
                        <p class="fs-6">Nama</p>
                        <p>{{ appointmentDetail.firstName }} {{ appointmentDetail.middleName }} {{ appointmentDetail.lastName }}</p>
                    </div>
                    <div class="w-50 text-end">
                        <p class="fs-6">No. Rekam Medis</p>
                        <p>{{ appointmentDetail.medicalNo }}</p>
                    </div>
                </div>
                <div class="d-flex justify-content-between col-gap-20 mt-2">
                    <div class="w-50">
                        <p class="fs-6">Jenis Kelamin</p>
                        <p>{{ (appointmentDetail.sex === 'M') ? 'Laki-Laki' : 'Perempuan' }}</p>
                    </div>
                    <div class="w-50 text-end">
                        <p class="fs-6">Penjamin</p>
                        <p>{{ appointmentDetail.guarantorName }}</p>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="fs-6">Alamat</p>
                    <p>
                        {{ appointmentDetail.streetName }}
                        {{ appointmentDetail.district }}
                        {{ appointmentDetail.city }}
                        {{ appointmentDetail.zipCode }}
                    </p>
                </div>
            </div>
            <div class="bg-white px-4 py-3">
                <div class="d-flex justify-content-between col-gap-20 pt-3 border-top border-gray-400">
                    <div>
                        <div class="d-flex align-items-center col-gap-8">
                            <i class="bi bi-person-vcard-fill icon-blue-500"></i>
                            <span class="fs-6 text-gray-700">No Registrasi</span>
                        </div>
                        <p class="mt-2">{{ appointmentDetail.appointmentNo }}</p>
                    </div>
                    <div class="text-end">
                        <div class="d-flex align-items-center justify-content-end col-gap-8">
                            <i class="bi bi-clock-fill icon-blue-500"></i>

                            <span class="fs-6 text-gray-700">Tanggal</span>
                        </div>
                        <p class="mt-2">{{ convertDateTimeToDate(appointmentDetail.appointmentDate_yMdHms) }}. {{ appointmentDetail.appointmentTime }}</p>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-top border-gray-400">
                    <div class="d-flex col-gap-8 align-items-center">
                        <img :src="DoctorBlue" alt="Icon" width="16" height="16">
                        <p class="fs-6 text-gray-700">Nama Dokter</p>
                    </div>
                    <p class="mt-2">{{ appointmentDetail.paramedicName }}</p>
                </div>
                <div class="mt-3 pt-3 border-top border-gray-400">
                    <div class="d-flex col-gap-8 align-items-center">
                        <i class="bi bi-building-fill icon-blue-500"></i>

                        <p class="fs-6 text-gray-700">Unit</p>
                    </div>
                    <p class="mt-2">{{ appointmentDetail.serviceUnitName }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-3" v-if="isLoading">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</template>
