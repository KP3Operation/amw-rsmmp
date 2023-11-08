<script setup>
import LogoWhite from "@resources/static/images/logo-white.svg";
import DoctorPatient from "@resources/static/images/doctor-2-pasien.png";
import DoctorWhite from "@resources/static/icons/doctor-white.svg";
import UserFillWhite from "@resources/static/icons/users-fill-white.svg";
import { useAuthStore } from "@shared/+store/auth.store.js";
import { convertDateTimeToDate, getUserFirstName } from "@shared/utils/helpers.js";
import ConsulCard from "@patient/Components/ConsulCard/ConsulCard.vue";
import { useAppointmentStore } from "@patient/+store/appointment.store.js";
import { storeToRefs } from "pinia";
import axios from "axios";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import { onMounted, ref } from "vue";
import apiRequest from "@shared/utils/axios.js";

const authStore = useAuthStore();
const { userData, userPatientData } = storeToRefs(authStore);
const layoutStore = useLayoutStore();
const { isLoading } = storeToRefs(layoutStore);
const appointmentStore = useAppointmentStore();
const { openAppointments, selectedMedicalNo } = storeToRefs(appointmentStore);

const fetchAppointments = () => {
    apiRequest.get(`/api/v1/patient/appointments`, {
        params: {
            medical_no: selectedMedicalNo.value
        }
    }).then((response) => {
        const data = response.data;
        appointmentStore.updateOpenAppointments(data.appointments.opens);
    }).catch((error) => {
        throw error;
    }).finally(() => {
        layoutStore.updateLoadingState(false);
    });
}

onMounted(() => {
    appointmentStore.$reset();
    appointmentStore.updateSelectedMedicalNo(userPatientData.value.medicalNo);
    fetchAppointments();
});
</script>

<template>
    <div class="header header-pasien">
        <router-link to="/home">
            <img :src="LogoWhite" alt="Logo Aviat" width="33" height="33">
        </router-link>
        <p>{{ $t('header.greeting') }}, <span v-text="getUserFirstName(userData.userFullName)"></span></p>
    </div>
    <div class="mt-4 px-4 pt-8">
        <section class="rounded-3 d-flex col-gap-20 bg-blue-500 p-4 text-white">
            <img :src="DoctorPatient" alt="Ilustrasi" width="113" height="82">
            <div>
                <p class="fw-bold">{{ $t('home.welcome') }}</p>
                <p class="mt-2 fs-6">{{ $t('home.greeting') }}</p>
            </div>
        </section>
        <section class="mt-4">
            <h2 class="fs-3 fw-bold text-black">{{ $t('home.what_your_need') }}</h2>
            <div class="list-menu-homepage pasien mt-3">
                <router-link to="/history" class="item riwayat-medis bg-blue-100">
                    <div class="icon icon-pasien bg-blue-500">
                        <i class="bi bi-clipboard-heart-fill"></i>
                    </div>

                    <p class="fw-bold mt-3 text-black">{{ $t('home.history') }}</p>
                </router-link>
                <router-link :to="{ name: 'DoctorSchedulePage' }" class="item doctor-schedule bg-green-100">
                    <div class="icon icon-pasien bg-green-500">
                        <img :src="DoctorWhite" alt="Icon" width="25" height="25">
                    </div>

                    <p class="fw-bold mt-3 text-black">{{ $t('home.doctor_schedule') }}</p>
                </router-link>
                <router-link to="/family" class="item family-member">
                    <div class="icon icon-pasien">
                        <img :src="UserFillWhite" alt="Icon" width="25" height="25">
                    </div>
                    <p class="fw-bold mt-3 text-black">{{ $t('home.family_member') }}</p>
                </router-link>
                <router-link to="/appointment" class="item appointment">
                    <div class="icon icon-pasien">
                        <i class="bi bi-calendar2-date-fill"></i>
                    </div>
                    <p class="fw-bold mt-3 text-black">{{ $t('home.appointment') }}</p>
                </router-link>
            </div>
        </section>
        <section class="mt-6">
            <h2 class="fs-3 fw-bold text-black">{{ $t('home.next_consult_schedule') }}</h2>
            <div v-if="openAppointments.length > 0" id="jadwal-konsultasi" class="carousel slide mt-2"
                data-bs-interval="5000" data-bs-touch="true" data-bs-ride="carousel">
                <div class="carousel-inner d-flex flex-nowrap col-gap-20">
                    <ConsulCard
                        v-for="(appointment, index) in openAppointments"
                        :doctor="appointment.paramedicName" :unit="appointment.serviceUnitName"
                        :date="convertDateTimeToDate(appointment.appointmentDate_yMdHms)"
                        :time="appointment.appointmentTime" :id="index" />
                </div>

                <div class="carousel-indicators position-static mt-2 mb-0">
                    <button v-for="(appointment, index) in openAppointments" type="button"
                        data-bs-target="#jadwal-konsultasi" :data-bs-slide-to="index + 1" :class="index === 0?'active': ''"
                        :aria-label="'Slide ' + index" form="#"></button>
                </div>
            </div>
            <div class="mt-2 px-4 py-3 bg-blue-100 rounded-3 text-center"
                v-if="openAppointments.length < 1 && !isLoading && selectedMedicalNo !== ''">
                <p class="fw-bold">Anda Tidak Memiliki Jadwal Konsultasi</p>
                <p class="mt-2 text-gray-700 fs-5">Buat Jadwal Konsultasi Baru Dengan Mentap Tombol “Buat Jadwal”</p>
                <router-link :to="{ name: 'CreateAppointmentPage' }"
                    class="d-block btn btn-blue-500-rounded-sm mt-4 fw-semibold">Buat Jadwal</router-link>
            </div>
            <div class="mt-2 px-4 py-3 bg-blue-100 rounded-3 text-center"
                v-if="!isLoading && selectedMedicalNo === '' && openAppointments.length < 1">
                <p class="fw-bold">Mulai Sinkronisasi Data Anda</p>
                <p class="mt-2 text-gray-700 fs-5">Mulai Sinkronisasi Data Anda untuk Mulai Jadwal Konsultasi dengan Tap
                    tombol Sinkronisasi dibawah.</p>

                <router-link :to="{ name: 'ProfilePage' }"
                    class="d-block btn btn-blue-500-rounded-sm mt-4 fw-semibold">Sinkronisasi</router-link>
            </div>
        </section>
    </div>
</template>
