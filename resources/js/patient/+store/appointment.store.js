import { defineStore } from "pinia";
import { ref } from "vue";
import {getCurrentDate} from "@shared/utils/helpers.js";

export const useAppointmentStore = defineStore(
    "appointment-store",
    () => {
        let openAppointments = ref([]);
        let doneAppointments = ref([]);
        let cancelAppointments = ref([]);
        let closeAppointments = ref([]);
        let selectedMedicalNo = ref("");
        let selectedDate = ref(getCurrentDate());
        let selectedParamedicId = ref("");
        let selectedServiceUnitId = ref("");
        let selectedStartDate = ref("");
        let selectedEndDate = ref("");


        function updateOpenAppointments (data) {
            openAppointments.value = data;
        }
        function updateDoneAppointments (data) {
            doneAppointments.value = data;
        }

        function updateCancelAppointments (data) {
            cancelAppointments.value = data;
        }

        /**
         * @param {array} cancels
         * @param {array} dones
         */
        function updateCloseAppointments (cancels, dones) {
            closeAppointments.value = cancels.concat(dones);
        }

        function updateSelectedMedicalNo (medicalNo) {
            selectedMedicalNo.value = medicalNo;
        }

        function updateSelectedDate (date) {
            selectedDate.value = date;
        }

        function updateSelectedParamedicId (id) {
            selectedParamedicId.value = id;
        }

        function updateSelectedServiceUnitId (id) {
            selectedServiceUnitId.value = id;
        }

        function updateSelectedStartDate (date) {
            selectedStartDate.value = date;
        }

        function updateSelectedEndDate (date) {
            selectedEndDate.value = date;
        }

        function $reset() {
            openAppointments.value = [];
            doneAppointments.value = [];
            cancelAppointments.value = [];
            selectedMedicalNo.value = "";
            selectedDate.value = getCurrentDate();
            selectedParamedicId.value = "";
            selectedServiceUnitId.value = "";
            selectedStartDate.value = "";
            selectedEndDate.value = "";
            selectedEndDate.value = "";
        }


        return {
            $reset,
            updateDoneAppointments,
            updateCancelAppointments,
            updateOpenAppointments,
            updateSelectedMedicalNo,
            updateSelectedDate,
            updateCloseAppointments,
            updateSelectedParamedicId,
            updateSelectedServiceUnitId,
            updateSelectedStartDate,
            updateSelectedEndDate,
            doneAppointments,
            cancelAppointments,
            openAppointments,
            selectedMedicalNo,
            selectedDate,
            closeAppointments,
            selectedParamedicId,
            selectedServiceUnitId,
            selectedStartDate,
            selectedEndDate
        };
    }
);
