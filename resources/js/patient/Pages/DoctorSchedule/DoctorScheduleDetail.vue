<script setup>
import { useAppointmentStore } from "@patient/+store/appointment.store.js";
import { useDoctorScheduleStore } from "@patient/+store/doctor-schedule.store.js";
import router from "@patient/router.js";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import Header from "@shared/Components/Header/Header.vue";
import apiRequest from "@shared/utils/axios.js";
import { storeToRefs } from "pinia";
import { onMounted, ref } from "vue";
import { convertDateTimeToDate } from "@shared/utils/helpers.js";


const layoutStore = useLayoutStore();
const { isLoading } = storeToRefs(layoutStore);
const doctorScheduleStore = useDoctorScheduleStore();
const { schedules, selectedParamedicId, selectedDate, selectedServiceUnitId } =
    storeToRefs(doctorScheduleStore);
const appointmentStore = useAppointmentStore();
let selectedSchedule = ref({});
let isShowButton = ref(false);

const fetchDoctorScheduleDetail = () => {
    layoutStore.updateLoadingState(true);
    apiRequest
        .get(`/api/v1/patient/doctor/schedules`, {
            params: {
                date: `${selectedDate.value}`,
                service_unit_id: `${selectedServiceUnitId.value}`,
                paramedic_id: `${selectedParamedicId.value}`,
            },
        })
        .then((response) => {
            const data = response.data;
            doctorScheduleStore.updateSchedules(data.schedules);
            selectedSchedule.value = schedules.value[0];
            isShowButton.value = true;
        })
        .catch((error) => {
            layoutStore.toggleErrorAlert(`${error.response.data.message}`);
        })
        .finally(() => {
            layoutStore.updateLoadingState(false);
        });
};

const navigateToCreateAppointment = () => {
    appointmentStore.updateSelectedParamedicId(
        selectedSchedule.value.paramedicID
    );
    appointmentStore.updateSelectedServiceUnitId(
        selectedSchedule.value.serviceUnitID
    );
    router.push({ name: "CreateAppointmentPage" });
};

onMounted(() => {
    fetchDoctorScheduleDetail();
});
</script>

<template>
    <Header
        :title="$t('doctor_schedule.schedule_detail.title')"
        :with-back-url="true"
        page-name="DoctorSchedulePage"
    ></Header>
    <div class="pt-8 mt-4 px-4">
        <h2 class="fs-3 fw-bold text-center">
            {{ selectedSchedule.paramedicName }}
        </h2>
        <p class="text-center text-gray-700 mt-2">
            {{ selectedSchedule.serviceUnitName }}
        </p>
        <p class="text-center text-gray-700 mt-2">
            {{ new Date(selectedSchedule.ScheduleDate_yMdHms).toLocaleDateString('id-ID', { weekday: 'long' }) }},
            {{ convertDateTimeToDate(selectedSchedule.ScheduleDate_yMdHms) }}
        </p>
        <section class="mt-5">
            <div class="d-flex col-gap-8 align-items-center">
                <i class="bi bi-clock-fill icon-blue-500 fs-3"></i>
                <p class="fs-5 text-gray-700">{{ $t('doctor_schedule.schedule_detail.schedule') }}</p>
            </div>
            <div class="d-flex flex-column rows-gap-8 mt-2">
                <div class="d-flex justify-content-between pb-2 border-bottom border-gray-400">
                    <p>{{ selectedSchedule.startTime1 }}</p> sampai dengan
                    <p class="text-end fs-4">{{ selectedSchedule.endTime1 }}</p>
                </div>
                <div
                    v-if="selectedSchedule.startTime2 && selectedSchedule.endTime2"
                    class="d-flex justify-content-between pb-2 border-bottom border-gray-400"
                >
                    <p>{{ selectedSchedule.startTime2 }}</p>
                    sampai dengan
                    <p class="text-end fs-4">{{ selectedSchedule.endTime2 }}</p>
                </div>
                <div class="d-flex justify-content-between pb-2 border-bottom border-gray-400">
                     <p>{{ selectedSchedule.startTime3 }}</p> sampai dengan
                    <p class="text-end fs-4">{{ selectedSchedule.endTime3 }}</p>
                </div>
                <!-- <div class="d-flex justify-content-between pb-2 border-bottom border-gray-400">
                    <p>{{ $t('doctor_schedule.schedule_detail.schedule2') }}</p>
                    <p class="text-end">
                        {{ selectedSchedule.startTime2 }} -
                        {{ selectedSchedule.endTime2 }}
                    </p>
                </div> -->
            </div>
            <a
                href="#"
                v-if="isShowButton"
                @click="navigateToCreateAppointment"
                class="d-block btn btn-blue-500-rounded mt-4"
                >{{ $t('doctor_schedule.schedule_detail.create_appointment') }}</a
            >
            <!-- <p class="d-flex justify-content-between pt-3">Notes : </p> -->
        </section>
    </div>
</template>
