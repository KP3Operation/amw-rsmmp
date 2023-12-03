<script>
import { useAppointmentStore } from "@doctor/+store/appointment.store.js";
import HomeHeader from "@doctor/Components/HomeHeader/HomeHeader.vue";
import OverviewConsultationScheduleEmpty from "@doctor/Components/OverviewConsultationSchedule/OverviewConsultationScheduleEmpty.vue";
import OverviewSummaryFee from "@doctor/Components/OverviewSummaryFee/OverviewSummaryFee.vue";
import { useAuthStore } from "@shared/+store/auth.store.js";
import apiRequest from "@shared/utils/axios.js";
import {mapState} from "pinia";
import {convertDateTimeToDate} from "@shared/utils/helpers.js";

export default {
    components: { HomeHeader, OverviewConsultationScheduleEmpty, OverviewSummaryFee },
    setup() {},
    data() {
        return {
            showSummaryFeeFilter: false,
            overviewAppointments: [],
        };
    },
    computed: {
        ...mapState(useAppointmentStore, {
            selectedDate: 'selectedDate',
            doctorAppointments: 'doctorAppointments'
        }),
        ...mapState(useAuthStore, {
            userData: 'userData'
        })
    },
    mounted() {
        this.getAppointments();
    },
    methods: {
        convertDateTimeToDate,
        getAppointments() {
            apiRequest
                .get("/api/v1/doctor/appointments/group", {
                    params: { date: this.selectedDate },
                })
                .then((response) => {
                    const data = response.data;
                    this.overviewAppointments = data.appointments;
                })
                .catch((error) => {})
                .finally(() => {});
        },
    },
};
</script>

<template>
    <HomeHeader />
    <br /><br /><br />
    <div class="px-4 mt-5">
        <section class="d-flex col-gap-20 bg-blue-100 rounded-3 px-4 py-3">
            <img src="@resources/static/images/doctor-2.png" alt="Ilustrasi" width="80" height="57" />

            <div>
                <h1 class="fs-4 fw-bold">
                    {{ $t("home.welcome") }} <br />
                    <span
                        v-text="$getUserFirstName(userData.userFullName)"
                    ></span>
                </h1>

                <p class="mt-2 fs-6 text-gray-700">{{ $t("home.greeting") }}</p>
            </div>
        </section>

        <section class="list-menu-homepage mt-5">
            <router-link to="/appointment" class="item bg-blue-100">
                <div class="icon icon-doctor bg-blue-500">
                    <i class="bi bi-calendar-event fs-3"></i>
                </div>

                <p class="fw-bold text-black mt-3">
                    {{ $t("home.appointment") }}
                </p>
                <p class="fs-6 text-gray-700 mt-2">
                    {{ $t("home.appointment_desc") }}
                </p>
            </router-link>

            <router-link to="/inpatient/list" class="item bg-green-100">
                <div class="icon icon-doctor bg-green-500">
                    <img src="@resources/static/icons/doctor-white.svg" alt="Icon" width="20" height="20" />
                </div>

                <p class="fw-bold text-black mt-3">
                    {{ $t("home.inpatient_list") }}
                </p>
                <p class="fs-6 text-gray-700 mt-2">
                    {{ $t("home.inpatient_list_desc") }}
                </p>
            </router-link>

            <router-link to="/fee" class="item summary-fee">
                <div class="icon icon-doctor">
                    <img src="@resources/static/icons/money-white.svg" alt="Icon" width="20" height="20" />
                </div>

                <p class="fw-bold text-black mt-3">
                    {{ $t("home.summary_fee") }}
                </p>
                <p class="fs-6 text-gray-700 mt-2">
                    {{ $t("home.summary_fee_desc") }}
                </p>
            </router-link>

            <router-link to="/profile" class="item profile">
                <div class="icon icon-doctor">
                    <i class="bi bi-person-circle icon-white fs-3"></i>
                </div>
                <p class="fw-bold text-black mt-3">{{ $t("home.profile") }}</p>
                <p class="fs-6 text-gray-700 mt-2">
                    {{ $t("home.profile_desc") }}
                </p>
            </router-link>
        </section>
        <section class="homepage-banner mt-6">
            <img src="@resources/static/images/banner.png" alt="Banner" height="177" />
        </section>

        <section class="mt-5">
            <h2 class="fs-3 fw-bold text-black">
                {{ $t("home.overview_consult_schedule") }}
            </h2>
            <OverviewConsultationScheduleEmpty
                v-if="overviewAppointments.length < 1"/>

            <div
                v-if="overviewAppointments.length >= 1"
                id="overview-jadwal-konsultasi"
                class="carousel slide mt-3"
                data-bs-touch="true"
                data-bs-ride="carousel">
                <div class="carousel-inner d-flex flex-nowrap col-gap-20">
                    <div
                        class="carousel-item"
                        :class="index === 0 ? 'active' : ''"
                        :key="index"
                        v-for="(appointment, index) in overviewAppointments">
                        <div class="d-flex col-gap-20">
                            <div class="overview-slider-doctor">
                                <div class="date">
                                    <div>
                                        <i class="bi bi-calendar-event-fill"></i>
                                        <p> {{ convertDateTimeToDate(appointment.date) }}
                                        </p>
                                    </div>
                                </div>
                                <div class="patient">
                                    <i class="bi bi-heart-pulse-fill fs-1 icon-blue-200"></i>
                                    <p>
                                        <span>{{ appointment.count }}</span>
                                        <br />
                                        {{ $t('home.patient') }}
                                    </p>
                                </div>
                                <p class="location">
                                    {{ appointment.serviceUnitName }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="carousel-indicators position-static mb-0">
                    <button
                        v-for="(appointment, index) in overviewAppointments"
                        type="button"
                        data-bs-target="#overview-jadwal-konsultasi"
                        :data-bs-slide-to="index"
                        :class="index === 0 ? 'active' : ''"
                        :aria-label="'Slide ' + index"
                    ></button>
                </div>
            </div>
        </section>
        <OverviewSummaryFee />
    </div>
</template>
