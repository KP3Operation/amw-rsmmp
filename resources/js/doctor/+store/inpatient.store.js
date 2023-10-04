import { defineStore } from "pinia";
import { ref } from "vue";

export const useInpatientStore = defineStore(
    "inpatient-store",
    () => {
        let patients = ref([]);
        let selectedPatient = ref({});
        let patientCount = ref(0);

        function setSelectedPatient (patient) {
            selectedPatient.value = patient;
        }

        function $reset() {
            patients.value = [];
            selectedPatient.value = {};
            patientCount.value = 0;
        }

        return {
            $reset,
            setSelectedPatient,
            patients,
            selectedPatient,
            patientCount
        };
    }
);
