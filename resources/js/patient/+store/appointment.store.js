import { getCurrentDate } from "@shared/utils/helpers.js";
import { defineStore } from "pinia";
import { reactive, ref } from "vue";

export const useAppointmentStore = defineStore("appointment-store", () => {
    let openAppointments = ref([]);
    let doneAppointments = ref([]);
    let cancelAppointments = ref([]);
    let closeAppointments = ref([]);
    let selectedMedicalNo = ref("");
    let selectedDate = ref(getCurrentDate());
    let selectedParamedicId = ref("");
    let selectedServiceUnitId = ref("");
    let selectedGuarantorId = ref("");
    let selectedStartDate = ref("");
    let selectedEndDate = ref("");
    let selectedPatient = reactive({
        name: "-",
        gender: "-",
        medicalNo: "-",
        birthDate: "-",
    });

    function updateOpenAppointments(data) {
        openAppointments.value = data;
    }
    function updateDoneAppointments(data) {
        doneAppointments.value = data;
    }

    function updateCancelAppointments(data) {
        cancelAppointments.value = data;
    }

    /**
     * @param {array} cancels
     * @param {array} dones
     */
    function updateCloseAppointments(cancels, dones) {
        closeAppointments.value = cancels.concat(dones);
    }

    function updateSelectedMedicalNo(medicalNo) {
        selectedMedicalNo.value = medicalNo;
    }

    function updateSelectedDate(date) {
        selectedDate.value = date;
    }

    function updateSelectedParamedicId(id) {
        selectedParamedicId.value = id;
    }

    function updateSelectedServiceUnitId(id) {
        selectedServiceUnitId.value = id;
    }
    function updateSelectedGuarantorId(id) {
        selectedGuarantorId.value = id;
    }

    function updateSelectedStartDate(date) {
        selectedStartDate.value = date;
    }

    function updateSelectedEndDate(date) {
        selectedEndDate.value = date;
    }

    /**
     * @param {Object} patient
     * @param {string} patient.name
     * @param {string} patient.gender
     * @param {string} patient.medicalNo
     * @param {string} patient.birthDate
     */
    function updateSelectedPatient(patient) {
        selectedPatient.name = patient.name;
        selectedPatient.gender = patient.gender;
        selectedPatient.medicalNo = patient.medicalNo;
        selectedPatient.birthDate = patient.birthDate;
    }

    function $reset() {
        openAppointments.value = [];
        doneAppointments.value = [];
        cancelAppointments.value = [];
        selectedMedicalNo.value = "";
        selectedDate.value = getCurrentDate();
        selectedParamedicId.value = "";
        selectedServiceUnitId.value = "";
        selectedGuarantorId.value = "";
        selectedStartDate.value = "";
        selectedEndDate.value = "";
        selectedEndDate.value = "";
        selectedPatient = {
            name: "-",
            gender: "-",
            medicalNo: "-",
            birthDate: "-",
        };
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
        updateSelectedGuarantorId,
        updateSelectedStartDate,
        updateSelectedEndDate,
        updateSelectedPatient,
        doneAppointments,
        cancelAppointments,
        openAppointments,
        selectedMedicalNo,
        selectedDate,
        closeAppointments,
        selectedParamedicId,
        selectedServiceUnitId,
        selectedGuarantorId,
        selectedStartDate,
        selectedEndDate,
        selectedPatient,
    };
});
