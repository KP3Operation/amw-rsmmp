import { defineStore } from "pinia";
import { ref } from "vue";
import {getCurrentDate} from "@shared/utils/helpers.js";

export const useAppointmentStore = defineStore(
    "appointment-store",
    () => {
        let doctorAppointments = ref([]);
        let selectedDate = ref(getCurrentDate());

        /**
         * @param {array} appointments
         */
        function updateAppointments (appointments) {
           doctorAppointments.value = appointments;
        }

        /**
         * @param {string} date
         */
        function updateSelectedDate (date) {
           selectedDate.value = date;
        }

        function $reset() {
            doctorAppointments.value = [];
        }

        return {
            $reset,
            updateAppointments,
            updateSelectedDate,
            doctorAppointments,
            selectedDate
        };
    }
);
