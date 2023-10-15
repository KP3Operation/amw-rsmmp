import { defineStore } from "pinia";
import { ref } from "vue";
import {convertDateToFormField, getCurrentDate} from "@shared/utils/helpers.js";

export const useDoctorScheduleStore = defineStore(
    "doctor-schedule-store",
    () => {
        let schedules = ref([]);
        let selectedParamedicId = ref("");
        let selectedDate = ref(convertDateToFormField(new Date()));
        let selectedServiceUnitId = ref("");

        /**
         * @param {array<string, string>} doctorSchedules
         */
        function updateSchedules(doctorSchedules) {
            schedules.value = doctorSchedules;
        }

        /**
         * @param {string} date
         */
        function updateSelectedDate (date) {
            selectedDate.value = date;
        }

        /**
         * @param {string} id
         */
        function updateSelectedParamedicId (id) {
           selectedParamedicId.value = id;
        }

        /**
         * @param {string} unitId
         */
        function updateSelectedServiceUnitId (unitId) {
           selectedServiceUnitId.value = unitId;
        }

        function $reset() {
            schedules.value = [];
            selectedParamedicId.value = "";
            selectedDate.value = convertDateToFormField(new Date());
            selectedServiceUnitId.value = "";
        }

        return {
            $reset,
            updateSelectedParamedicId,
            updateSchedules,
            updateSelectedDate,
            updateSelectedServiceUnitId,
            schedules,
            selectedParamedicId,
            selectedDate,
            selectedServiceUnitId
        };
    }
);
