import { defineStore } from "pinia";
import {reactive, ref} from "vue";

export const useMedicalHistoryStore = defineStore(
    "medical-history-store",
    () => {

        let selectedTab = ref("");
        let vitalSignHistories = ref([]);
        let prescriptionHistories = ref([]);

        /**
         * @typedef LabResult
         * @property {string} registrationNo
         * @property {string} executionDate
         * @property {string} superDisplaySequence
         * @property {string} transactionNo
         * @property {string} sequenceNo
         * @property {string} itemID
         * @property {number} level
         * @property {string} parentNo
         * @property {string} testName
         * @property {number} age
         * @property {string} ageType
         * @property {string} sex
         * @property {boolean} isCito
         * @property {string} resultValue
         * @property {string} itemUnit
         * @property {boolean} isDuplo
         * @property {number} normalValueMin
         * @property {number} normalValueMax
         * @property {number} isResultInput
         * @property {string} itemGroupName
         * @property {string} notes
         * @property {string} isDescriptionResult
         * @property {number} lv1
         * @property {number} lv2
         * @property {number} lv3
         * @property {number} lv4
         * @property {string} displaySequence
         * @property {string} executionDate_yMdHms
         *
         * @type {Ref<UnwrapRef<LabResult[]>>}
         */
        let labResultHistories = ref([]);
        let radResultHistories = ref([]);
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
        let selectedLabResult = reactive({
            sequenceNo: "-",
            executionDate: "-",
            age: "-",
            gender: "-",
            transactionNo: "-"
        });
        let selectedRadResult = reactive({
            itemID : '',
            itemName : '',
            readBy : '',
            registrationNo : '',
            transactionNo : '',
            testResult : '',
            testSummary : '',
            testResultDate : '',
            testSuggest : '',
        });
        let selectedEncounterResult = reactive({
            serviceUnitName: "-",
            paramedicName: "-",
            registrationQue: "-",
            registrationNo: "-",
            guarantorName: "-",
            registrationTime: "-",
            registrationDate_yMdHms: "-",
            visitTypeName: "-"
        });
        let selectedFamilyMemberId  = ref(0);

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
            labResultHistories.value = histories;
        }

        /**
         * @param {Array<Object>} histories
         */
        function updateRadResultHistories (histories) {
            radResultHistories.value = histories;
        }

        /**
         * @param {Array<Object>} histories
         */
        function updateEncounterHistories (histories) {
            encounterHistories.value = histories;
        }
        function clearEncounterHistories(){
            encounterHistories.value = [];
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

        /**
         * @param {Object} labResult
         * @param {string} labResult.sequenceNo
         * @param {string} labResult.executionDate
         * @param {string} labResult.age
         * @param {string} labResult.gender
         * @param {string} labResult.transactionNo
         */
        function updateSelectedLabResult(labResult) {
            selectedLabResult.sequenceNo = labResult.sequenceNo;
            selectedLabResult.executionDate = labResult.executionDate;
            selectedLabResult.age = labResult.age;
            selectedLabResult.gender = labResult.gender;
            selectedLabResult.transactionNo = labResult.transactionNo;
        }

        /**
         * @param {Object} radResult
         * @param {string} radResult.sequenceNo
         * @param {string} radResult.executionDate
         * @param {string} radResult.age
         * @param {string} radResult.gender
         * @param {string} radResult.transactionNo
         */
        function updateSelectedRadResult(radResult) {
            selectedRadResult.itemID = radResult.itemID;
            selectedRadResult.itemName = radResult.itemName;
            selectedRadResult.readBy = radResult.readBy;
            selectedRadResult.registrationNo = radResult.registrationNo;
            selectedRadResult.transactionNo = radResult.transactionNo;
            selectedRadResult.testResult = radResult.testResult;
            selectedRadResult.testSummary = radResult.testSummary;
            selectedRadResult.testResultDate = radResult.testResultDate;
            selectedRadResult.testSuggest = radResult.testSuggest;
        }

        /**
         * @param {string} tab
         */
        function updateSelectedTab(tab) {
           selectedTab.value = tab;
        }

        /**
         * @param {Object} encounter
         */
        function updateSelectedEncounter (encounter) {
            selectedEncounterResult.serviceUnitName = encounter.serviceUnitName;
            selectedEncounterResult.paramedicName = encounter.paramedicName;
            selectedEncounterResult.registrationQue = encounter.registrationQue;
            selectedEncounterResult.registrationNo = encounter.registrationNo;
            selectedEncounterResult.guarantorName = encounter.guarantorName;
            selectedEncounterResult.registrationTime = encounter.registrationTime;
            selectedEncounterResult.registrationDate_yMdHms = encounter.registrationDate_yMdHms;
            selectedEncounterResult.visitTypeName = encounter.visitTypeName;
        }

        /**
         * @param {number} id
         */
        function updateSelectedFamilyMemberId (id) {
            selectedFamilyMemberId.value = id;
        }

        function $reset() {
            vitalSignHistories.value = [];
            prescriptionHistories.value = [];
            labResultHistories.value = [];
            radResultHistories.value = [];
            encounterHistories.value = [];
            selectedPatient = {
                name: "-",
                gender: "-",
                medicalNo: "-",
                birthDate: "-",
            };
            selectedLabResult = {
                sequenceNo: "-",
                executionDate: "-",
                age: "-",
                gender: "-",
                transactionNo: "-"
            };
            selectedRadResult = {
                sequenceNo: "-",
                executionDate: "-",
                age: "-",
                gender: "-",
                transactionNo: "-"
            };
            selectedTab.value = "";
            selectedEncounterResult = {
                serviceUnitName: "-",
                paramedicName: "-",
                registrationQue: "-",
                registrationNo: "-",
                guarantorName: "-",
                registrationTime: "-",
                registrationDate_yMdHms: "-",
                visitTypeName: "-"
            };
            selectedFamilyMemberId.value = 0;
        }

        return {
            $reset,
            updateSelectedPatient,
            updateVitalSignHistories,
            updatePrescriptionHitories,
            updateLabResultHistories,
            updateRadResultHistories,
            updateEncounterHistories,
            updateSelectedPrescription,
            updateSelectedLabResult,
            updateSelectedRadResult,
            updateSelectedTab,
            updateSelectedEncounter,
            updateSelectedFamilyMemberId,
            vitalSignHistories,
            prescriptionHistories,
            labResultHistories,
            radResultHistories,
            encounterHistories,
            selectedPatient,
            selectedPrescription,
            selectedLabResult,
            selectedRadResult,
            selectedTab,
            selectedEncounterResult,
            selectedFamilyMemberId,
            clearEncounterHistories
        };
    }
);
