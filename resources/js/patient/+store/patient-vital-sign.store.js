import { defineStore } from "pinia";
import { ref } from "vue";

export const usePatientVitalSignStore = defineStore(
    "patient-vital-sign",
    () => {
        let histories = ref([]);
        let patient = ref({});
        let patientData = ref({});
        let count = ref(3);

        function $reset() {
            histories.value = [];
            patient.value = {};
            patientData.value = {};
            count.value = 3;
        }

        return {
            $reset,
            histories,
            patient,
            patientData,
            count,
        };
    }
);
