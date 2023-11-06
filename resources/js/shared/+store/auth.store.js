import { defineStore } from "pinia";
import {reactive, ref} from "vue";

export const useAuthStore = defineStore("auth", () => {
    /**
     * @typedef {Object} UserData
     * @property {number} userId
     * @property {string} userFullName
     * @property {string} userEmail
     * @property {string} userRole
     * @property {string} phoneNumber
     *
     * @type {Ref<UnwrapRef<UserData>>}
     */
    let userData = ref({
        userId: 0,
        userFullName: '',
        userEmail: '',
        userRole: 'patient',
        phoneNumber: '',
    });

    /**
     * @typedef {Object} OtpData
     * @property {string} otpCreatedAt
     * @property {string} otpUpdatedAt
     * @property {number} otpTimeout
     *
     * @type {Ref<UnwrapRef<OtpData>>}
     */
    let otpData = ref({
        otpCreatedAt: '',
        otpUpdatedAt: '',
        otpTimeout: 0,
    });

    /**
     * @typedef {Object} UserPatientData
     * @property {string} ssn
     * @property {string} patientId
     * @property {string} birthDate
     * @property {string} gender
     * @property {string} medicalNo
     *
     * @type {Ref<UnwrapRef<UserPatientData>>}
     */
    let userPatientData = ref({
         ssn:  '',
         patientId:  '',
         birthDate:  '',
         gender:  '',
         medicalNo:  '',
    });

    /**
     * @typedef {Object} UserDoctorData
     * @property {string} doctorId
     * @property {string} smfName
     *
     * @type {Ref<UnwrapRef<UserDoctorData>>}
     */
    let userDoctorData = ref({
        smfName: '',
        doctorId: '',
    });

    /**
     * @type {Ref<UnwrapRef<boolean>>}
     */
    let isRegistration = ref(false);


    /**
     * @param {UserData} data
     */
    function updateUserData (data) {
        // userData.value.userId = data.userId;
        // userData.value.userFullName = data.userFullName;
        // userData.value.userEmail = data.userEmail;
        // userData.value.userRole = data.userRole;
        // userData.value.phoneNumber = data.phoneNumber;
        Object.assign(userData.value, data);
    }

    /**
     * @param {OtpData} data
     */
    function updateOtpData (data) {
        // otpData.value.otpCreatedAt = data.otpCreatedAt;
        // otpData.value.otpUpdatedAt = data.otpUpdatedAt;
        // otpData.value.otpTimeout = data.otpTimeout;
        Object.assign(otpData.value, data);
    }

    /**
     * @param {UserPatientData} patient
     */
    function updateUserPatientData (patient) {
        // userPatientData.value.ssn = patient.ssn;
        // userPatientData.value.patientId = patient.patientId;
        // userPatientData.value.medicalNo = patient.medicalNo;
        // userPatientData.value.gender = patient.gender;
        // userPatientData.value.birthDate = patient.birthDate;
        Object.assign(userPatientData.value, patient);
    }

    /**
     * @param {UserDoctorData} data
     */
    function updateUserDoctorData (data) {
        // userDoctorData.value.doctorId = data.doctorId;
        // userDoctorData.value.smfName = data.smfName;
        Object.assign(userDoctorData.value, data);
    }

    /**
     * @param {boolean} status
     */
    function updateRegistrationFlag (status) {
        isRegistration.value = status;
    }

    function $reset() {
        isRegistration.value = false;

        updateUserData({
            userId: 0,
            userFullName: '',
            userEmail: '',
            userRole: 'patient',
            phoneNumber: '',
        });

        updateOtpData({
            otpCreatedAt: '',
            otpUpdatedAt: '',
            otpTimeout: 0,
        });

        updateUserPatientData({
            ssn:  '',
            patientId:  '',
            birthDate:  '',
            gender:  '',
            medicalNo:  '',
        });

        updateUserDoctorData({
            doctorId: '',
            smfName: '',
        });
    }

    return {
        $reset,
        updateUserData,
        updateOtpData,
        updateUserPatientData,
        updateUserDoctorData,
        updateRegistrationFlag,
        userData,
        otpData,
        userPatientData,
        userDoctorData,
        isRegistration,
    };
});
