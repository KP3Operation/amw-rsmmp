import { defineStore } from "pinia";
import { ref } from "vue";

export const useAuthStore = defineStore("auth", () => {
    // user data
    let otpCreatedAt = ref(null);
    let otpUpdatedAt = ref(null);
    let phoneNumber = ref(null);
    let otpTimeout = ref(0);
    let userEmail = ref(null);
    let userId = ref(0);
    let userFullName = ref("");
    let userRole = ref("patient");

    // patient
    let ssn = ref(0);
    let patientId = ref(null);
    let birthDate = ref(null);
    let gender = ref(null);

    // doctor
    let doctorId = ref(null);
    let smfName = ref(null);

    // registration specific value
    let isRegistration = ref(false);

    function updateBirthDate (date) {
        birthDate.value = date;
    }

    function $reset() {
        // user data
        otpCreatedAt.value = null;
        otpUpdatedAt.value = null;
        otpTimeout.value = 0;
        phoneNumber.value = null;
        userEmail.value = null;
        userFullName.value = "";
        userId.value = 0;
        userRole.value = "patient";

        // patient
        ssn.value = 0;
        patientId.value = null;
        birthDate.value = null;
        gender.value = null;

        //doctor
        doctorId.value = null;
        smfName.value = null;

        // registration specific value
        isRegistration.value = false;
    }

    return {
        $reset,
        updateBirthDate,
        otpCreatedAt,
        otpTimeout,
        userEmail,
        phoneNumber,
        userId,
        otpUpdatedAt,
        userFullName,
        userRole,
        ssn,
        patientId,
        birthDate,
        gender,
        doctorId,
        smfName,
        isRegistration,
    };
});
