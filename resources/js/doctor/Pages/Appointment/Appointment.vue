<script setup>
import Header from "@shared/Components/Header/Header.vue";
import {convertDateTimeToDate} from "@shared/utils/helpers.js";
import {onMounted, watch} from "vue";
import {useAppointmentStore} from "@doctor/+store/appointment.store.js";
import {storeToRefs} from "pinia";
import {useLayoutStore} from "@shared/+store/layout.store.js";
import apiRequest from "@shared/utils/axios.js";

const layoutStore = useLayoutStore();
const { isLoading } = storeToRefs(layoutStore);
const appointmentStore = useAppointmentStore();
const {selectedDate, doctorAppointments} = storeToRefs(appointmentStore);

const fetchAppointments = () => {
    layoutStore.updateLoadingState(true);
    apiRequest.get('/api/v1/doctor/appointments', {
        params: {
            date: selectedDate.value
        }
    }).then((response) => {
        const data = response.data;
        appointmentStore.updateAppointments(data.appointments);
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.updateLoadingState(false);
    });
}

watch(selectedDate, (newValue, oldValue) => {
    appointmentStore.updateSelectedDate(selectedDate.value);
    fetchAppointments();
});

onMounted(() => {
    fetchAppointments();
});
</script>

<template>
    <Header :title="$t('appointment.title')" :with-back-url="true"></Header>
    <br>
    <div class="filter-sticky-2 position-sticky bg-white p-4 mt-6 bg-white">
        <input type="date" name="date" id="date" class="d-block form-control" v-model="selectedDate">
        <div class="d-flex justify-content-between col-gap-20 mt-3">
            <p class="w-50">{{ doctorAppointments.length }} {{ $t('appointment.data') }}</p>
            <p class="w-50 text-end">{{ convertDateTimeToDate(selectedDate) }}</p>
        </div>
    </div>
    <div v-if="!isLoading" class="d-flex flex-column rows-gap-16 px-4 mt-6" v-for="(appointment, index) in doctorAppointments">
        <div :id="'item-'+index" class="item border d-flex flex-column rows-gap-16 rounded px-4 py-3 bg-blue-100 border-blue-200">
            <div class="d-flex align-items-center justify-content-between col-gap-20">
                <div class="d-flex col-gap-8 align-items-center w-50">
                    <i class="bi bi-calendar-event fs-2 icon-blue-500"></i>
                    <p class="fs-5">{{ convertDateTimeToDate(appointment.appointmentDate_yMdHms) }}</p>
                </div>
                <div class="d-flex col-gap-8 align-items-center justify-content-end w-50">
                    <i class="bi bi-clock fs-2 icon-blue-500"></i>
                    <p class="fs-5 text-end">{{ appointment.appointmentTime }}</p>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between col-gap-20">
                <div class="w-50">
                    <p class="fs-6 text-gray-700">{{ $t('appointment.booking_code') }}</p>
                    <!-- <p class="fs-5 mt-2 fw-semibold">{{ appointment.appointmentQue }}</p> -->
                    <p class="fs-5 mt-2 fw-semibold">{{ appointment.appointmentNo }}</p>
                </div>
                <div class="w-50 text-end">
                    <p class="fs-6 text-gray-700">{{ $t('appointment.medical_no') }}</p>
                    <p class="fs-5 mt-2 fw-semibold">{{ appointment.medicalNo }}</p>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between col-gap-20">
                <div class="w-50">
                    <p class="fs-6 text-gray-700">{{ $t('appointment.patient_name') }}</p>
                    <p class="fs-5 mt-2 fw-semibold">{{ appointment.patientName }}</p>
                </div>
                <div class="w-50 text-end">
                    <p class="fs-6 text-gray-700">{{ $t('appointment.guarantor_name') }}</p>
                    <p class="fs-5 mt-2 fw-semibold">{{ appointment.guarantorName }}</p>
                </div>
            </div>
            <!-- <div class="d-flex align-items-center justify-content-between col-gap-20">
                <div class="w-100">
                    <p class="px-2 py-1 bg-gray-100 text-blue-500 fw-bold text-sm rounded fs-5"
                        v-if="appointment.appointmentStatus === 'Open'">
                        status : {{ $t('appointment.status.booking') }}
                    </p>
                    <p class="px-2 py-1 bg-green-100 text-blue-500 fw-bold text-sm rounded fs-5"
                        v-else>
                        status : {{ $t('appointment.status.confirm') }}
                    </p>
                </div>
            </div> -->
            <div class="d-flex col-gap-20 rounded py-1">
                <div class="w-50">
                    <p class="fs-6 text-gray-700">{{ $t('appointment.queue_status') }}</p>
                    <p class="fs-5 fw-semibold" v-if="appointment.appointmentStatus === 'Open'">{{ $t('appointment.status.booking') }}</p>
                    <p class="fs-5 fw-semibold" v-else>{{ $t('appointment.status.confirm') }}</p>
                </div>

                <div class="w-50 text-end" v-if="appointment.FormattedNo !== ''">
                    <p class="fs-6 text-gray-700">{{ $t('appointment.queue_number') }}</p>
                    <p class="fs-5 fw-semibold">{{ appointment.FormattedNo }}</p>
                </div>
            </div>

            <router-link :to="{name: 'AppointmentDetailPage', query: {appointmentNo: appointment.appointmentNo}}" class="d-block btn btn-blue-500-rounded-sm">{{ $t('appointment.actions.detail') }}</router-link>
        </div>
    </div>
    <div class="text-center mt-3" v-if="isLoading">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
</template>
