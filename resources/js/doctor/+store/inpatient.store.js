import { defineStore } from "pinia";
import { ref } from "vue";

export const useInpatientStore = defineStore(
    "inpatient-store",
    () => {
        let patients = ref([]);
        let inpatientRooms = ref([]);
        let selectedPatient = ref({});
        let patientCount = ref(0);
        let selectedRegistrationNo = ref("");

        function setSelectedPatient (patient) {
            selectedPatient.value = patient;
        }

        function $reset() {
            inpatientRooms.value = [];
            patients.value = [];
            selectedPatient.value = {};
            patientCount.value = 0;
            selectedRegistrationNo.value = "";
        }

        return {
            $reset,
            setSelectedPatient,
            patients,
            selectedPatient,
            patientCount,
            selectedRegistrationNo,
            inpatientRooms
        };
    }
);
