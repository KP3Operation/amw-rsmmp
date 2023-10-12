import { defineStore } from "pinia";
import {reactive, ref} from "vue";

export const useMedicalHistoryStore = defineStore(
    "medical-history-store",
    () => {

        let vitalSignHistories = ref([]);
        let prescriptionHistories = ref([]);
        let labResultHistories = ref([]);
        let encounterHistories = ref([]);
        let selectedPatient = reactive({
            name: "-",
            gender: "-",
            medicalNo: "-",
            birthDate: "-",
        });
        let selectedPrescription = reactive({
            prescriptionNo: "-",
            prescriptionDate: "-",
            paramedicName: "-",
        });

        /**
         * @typedef {Object} VitalSign
         * @property {string} recordDate_yMdHms
         * @property {string} recordTime
         * @property {string} registrationNo
         * @property {string} vitalSignUnit
         * @property {string} vitalSignName
         *
         * @param {Array<VitalSign>} histories
         */
        function updateVitalSignHistories (histories) {
            vitalSignHistories.value = histories;
        }

        /**
         * @typedef {Object} PrescriptionHistory
         * @property {string} PrescriptionNo
         * @property {string} prescriptionDate_yMdHms
         * @property {string} paramedicName
         *
         * @param {Array<PrescriptionHistory>} histories
         */
        function updatePrescriptionHitories (histories) {
            prescriptionHistories.value = histories;
        }

        /**
         * @param {Array<Object>} histories
         */
        function updateLabResultHistories (histories) {
            prescriptionHistories.value = histories;
        }

        /**
         * @param {Array<Object>} histories
         */
        function updateEncounterHistories (histories) {
            prescriptionHistories.value = histories;
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

        /**
         * @param {Object} prescription
         * @param {string} prescription.prescriptionNo
         * @param {string} prescription.prescriptionDate
         * @param {string} prescription.paramedicName
         */
        function updateSelectedPrescription(prescription) {
            selectedPrescription.prescriptionNo = prescription.prescriptionNo;
            selectedPrescription.prescriptionDate = prescription.prescriptionDate;
            selectedPrescription.paramedicName = prescription.paramedicName;
        }

        function $reset() {
            vitalSignHistories.value = [];
            prescriptionHistories.value = [];
            labResultHistories.value = [];
            encounterHistories.value = [];
            selectedPatient = {
                name: "-",
                gender: "-",
                medicalNo: "-",
                birthDate: "-",
            }
        }

        return {
            $reset,
            updateSelectedPatient,
            updateVitalSignHistories,
            updatePrescriptionHitories,
            updateLabResultHistories,
            updateEncounterHistories,
            updateSelectedPrescription,
            vitalSignHistories,
            prescriptionHistories,
            labResultHistories,
            encounterHistories,
            selectedPatient,
            selectedPrescription
        };
    }
);
