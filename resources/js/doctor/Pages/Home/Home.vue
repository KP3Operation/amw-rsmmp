<script setup>
import HomeHeader from "@doctor/Components/HomeHeader/HomeHeader.vue";
import Doctor2 from "@resources/static/images/doctor-2.png";
import DoctorWhite from "@resources/static/icons/doctor-white.svg";
import MoneyWhite from "@resources/static/icons/money-white.svg";
import Banner from "@resources/static/images/banner.png";
import {onMounted, ref, watch} from "vue";
import { useAuthStore } from "@shared/+store/auth.store.js";
import { getUserFirstName } from "@shared/utils/helpers.js";
import OverviewSummaryFee from "@doctor/Components/OverviewSummaryFee/OverviewSummaryFee.vue";
import OverviewConsultationScheduleEmpty from "@doctor/Components/OverviewConsultationSchedule/OverviewConsultationScheduleEmpty.vue";
import OverviewInpatientEmpty from "@doctor/Components/OverviewInpatient/OverviewInpatientEmpty.vue";
import {useAppointmentStore} from "@doctor/+store/appointment.store.js";
import {storeToRefs} from "pinia";
import axios from "axios";

const appointmentStore = useAppointmentStore();
const {selectedDate, doctorAppointments} = storeToRefs(appointmentStore);
const authStore = useAuthStore();
const showSummaryFeeFilter = ref(false);

const fetchAppointments = () => {
  axios.get('/api/v1/doctor/appointments', {
    params: {date: selectedDate.value}
  }).then((response) => {
    const data = response.data;
    appointmentStore.updateAppointments(data.appointments);
  }).catch((error) => {}).finally(() => {});
}

onMounted(() => {
  // fetchAppointments();
});
</script>

<template>
    <HomeHeader />
    <br><br><br>
    <div class="px-4 mt-5">
        <section class="d-flex col-gap-20 bg-blue-100 rounded-3 px-4 py-3">
            <img :src="Doctor2" alt="Ilustrasi" width="80" height="57">

            <div>
                <h1 class="fs-4 fw-bold">{{ $t('home.welcome') }} <br /> <span
                        v-text="getUserFirstName(authStore.userFullName)"></span></h1>

                <p class="mt-2 fs-6 text-gray-700">{{ $t('home.greeting') }}</p>
            </div>
        </section>

        <section class="list-menu-homepage mt-5">
            <router-link to="/appointment" class="item bg-blue-100">
                <div class="icon icon-doctor bg-blue-500">
                    <i class="bi bi-calendar-event fs-3"></i>
                </div>

                <p class="fw-bold text-black mt-3">{{ $t('home.appointment') }}</p>
                <p class="fs-6 text-gray-700 mt-2">{{ $t('home.appointment_desc') }}</p>
            </router-link>

            <router-link to="/inpatient/list" class="item bg-green-100">
                <div class="icon icon-doctor bg-green-500">
                    <img :src="DoctorWhite" alt="Icon" width="20" height="20">
                </div>

                <p class="fw-bold text-black mt-3">{{ $t('home.inpatient_list') }}</p>
                <p class="fs-6 text-gray-700 mt-2">{{ $t('home.inpatient_list_desc') }}</p>
            </router-link>

            <router-link to="/fee" class="item summary-fee">
                <div class="icon icon-doctor">
                    <img :src="MoneyWhite" alt="Icon" width="20" height="20">
                </div>

                <p class="fw-bold text-black mt-3">{{ $t('home.summary_fee') }}</p>
                <p class="fs-6 text-gray-700 mt-2">{{ $t('home.summary_fee_desc') }}</p>
            </router-link>

            <router-link to="/profile" class="item profile">
                <div class="icon icon-doctor">
                    <i class="bi bi-person-circle icon-white fs-3"></i>
                </div>
                <p class="fw-bold text-black mt-3">{{ $t('home.profile') }}</p>
                <p class="fs-6 text-gray-700 mt-2">{{ $t('home.profile_desc') }}</p>
            </router-link>
        </section>
        <section class="homepage-banner mt-6">
            <img :src="Banner" alt="Banner" height="177">
        </section>

        <section class="mt-5">
            <h2 class="fs-3 fw-bold text-black">{{ $t('home.overview_consult_schedule') }}</h2>
            <OverviewConsultationScheduleEmpty v-if="doctorAppointments.length < 1" />

<!--            <div v-if="doctorAppointments.length >= 1" id="overview-jadwal-konsultasi" class="carousel slide mt-3" data-bs-touch="true" data-bs-ride="carousel">-->
<!--              <div class="carousel-inner d-flex flex-nowrap col-gap-20">-->
<!--                <div class="carousel-item">-->
<!--                  <div class="d-flex col-gap-20">-->
<!--                    <div class="overview-slider-doctor">-->
<!--                      <div class="date">-->
<!--                        <div>-->
<!--                          <i class="bi bi-calendar-event-fill"></i>-->

<!--                          <p>12 Jun 2023</p>-->
<!--                        </div>-->

<!--                      </div>-->

<!--                      <div class="patient">-->
<!--                        <i class="bi bi-heart-pulse-fill fs-1 icon-blue-200"></i>-->

<!--                        <p>-->
<!--                          <span>3230</span>-->
<!--                          <br>-->
<!--                          Pasien-->
<!--                        </p>-->
<!--                      </div>-->

<!--                      <p class="location">Unit Kecantikan dan Estetika</p>-->
<!--                    </div>-->

<!--                    <div class="overview-slider-doctor">-->
<!--                      <div class="date">-->
<!--                        <div>-->
<!--                          <i class="bi bi-calendar-event-fill"></i>-->

<!--                          <p>14 Jun 2023</p>-->
<!--                        </div>-->

<!--                      </div>-->

<!--                      <div class="patient">-->
<!--                        <i class="bi bi-heart-pulse-fill fs-1 icon-blue-200"></i>-->

<!--                        <p>-->
<!--                          <span>230</span>-->
<!--                          <br>-->
<!--                          Pasien-->
<!--                        </p>-->
<!--                      </div>-->

<!--                      <p class="location">Unit Kejiwaan dan Konseling</p>-->
<!--                    </div>-->
<!--                  </div>-->
<!--                </div>-->

<!--              </div>-->

<!--              <div class="carousel-indicators position-static mb-0">-->
<!--                <button type="button" data-bs-target="#overview-jadwal-konsultasi" data-bs-slide-to="0" class="" aria-label="Slide 1"></button>-->

<!--                <button type="button" data-bs-target="#overview-jadwal-konsultasi" data-bs-slide-to="1" aria-label="Slide 2" class="active" aria-current="true"></button>-->
<!--              </div>-->
<!--            </div>-->

        </section>
<!--        <section class="mt-5">-->
<!--            <h2 class="fs-3 fw-bold text-black">{{ $t('home.overview_inpatient_list') }}</h2>-->
<!--            <OverviewInpatientEmpty />-->
<!--        </section>-->
        <OverviewSummaryFee />
    </div>
</template>
