import {defineStore} from "pinia";
import {ref} from "vue";

export const useAuthStore = defineStore('auth', () =>{
    const phoneNumber = ref(null);
    const otpCreatedAt = ref(null);
    const otpUpdatedAt = ref(null);
    const otpTimeout = ref(0);
    const userEmail = ref(null);
    const userId = ref(0);
    const userToken = ref(null);

    function $reset() {
        phoneNumber.value = null;
        otpCreatedAt.value = null;
        otpUpdatedAt.value = null;
        otpTimeout.value = 0;
        userEmail.value = null;
        userId.value = 0;
        userToken.value = null;
    }

   return { $reset, phoneNumber, otpCreatedAt, otpTimeout, userEmail, userId, userToken, otpUpdatedAt };
});
