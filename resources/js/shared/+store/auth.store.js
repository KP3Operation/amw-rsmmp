import {defineStore} from "pinia";
import {ref} from "vue";

export const useAuthStore = defineStore('auth', () =>{
    let phoneNumber = ref(null);
    let otpCreatedAt = ref(null);
    let otpUpdatedAt = ref(null);
    let otpTimeout = ref(0);
    let userEmail = ref(null);
    let userId = ref(0);
    let userToken = ref(null);

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
