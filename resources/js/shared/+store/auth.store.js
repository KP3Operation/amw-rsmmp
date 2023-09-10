import { defineStore } from "pinia";
import { ref } from "vue";

export const useAuthStore = defineStore("auth", () => {
    let phoneNumber = ref(null);
    let otpCreatedAt = ref(null);
    let otpUpdatedAt = ref(null);
    let otpTimeout = ref(0);
    let userEmail = ref(null);
    let userId = ref(0);
    let isRegistration = ref(false);
    let userFullName = ref("");
    let ssn = ref(0);

    function $reset() {
        phoneNumber.value = null;
        otpCreatedAt.value = null;
        otpUpdatedAt.value = null;
        otpTimeout.value = 0;
        userEmail.value = null;
        userFullName.value = "";
        userId.value = 0;
        isRegistration.value = false;
        ssn.value = 0;
    }

    return {
        $reset,
        phoneNumber,
        otpCreatedAt,
        otpTimeout,
        userEmail,
        userId,
        otpUpdatedAt,
        isRegistration,
        userFullName,
        ssn,
    };
});
