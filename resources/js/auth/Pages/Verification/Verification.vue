<script setup>
import Form from "vform";
import {reactive, ref} from "vue";
import axios from "axios";
import {useAuthStore} from "@shared/+store/auth.store.js";
import {getSecondsLeft} from "@shared/utils/helpers.js";
import router from "@auth/router.js";

const authStore = useAuthStore();
let lastCodeUpdatedDate = new Date(authStore.otpUpdatedAt);
let futureDateTime = lastCodeUpdatedDate.getTime() + authStore.otpTimeout;
let resendOtpTimeout = ref(0);
let paddedResendOtpTimeout = ref("");
let isCountDownRunning = ref(true);
const form = reactive(
    new Form({
        code: null
    })
);

const otpVerification = () => {
    form.post(`/api/v1/verification`).then((response) => {
        form.reset();
        if (authStore.isRegistration) {
            router.push({path: '/confirmation'});
        } else {
            window.location.href = "/patient/home";
        }
    }).catch((error) => {
       // TODO: error handling
    });
}

const resendOtpCode = async () => {
    if (isCountDownRunning) {
        const response = await axios.post(`/api/v1/login`, {
            "phone_number": authStore.phoneNumber.replace(import.meta.env.VITE_APP_CALLING_CODE, "")
        });
        authStore.phoneNumber = response.data.data.phone_number;
        authStore.otpCreatedAt = response.data.data.otp_created_at;
        authStore.otpUpdatedAt = response.data.data.otp_updated_at;
        authStore.otpTimeout = response.data.data.otp_timeout;

        lastCodeUpdatedDate = new Date(authStore.otpUpdatedAt);
        futureDateTime = lastCodeUpdatedDate.getTime() + authStore.otpTimeout;
        countDown();
        isCountDownRunning.value = true;
    }
}

const countDown = () => {
    const timerId = setInterval(() => {
        resendOtpTimeout.value = getSecondsLeft(new Date(), new Date(futureDateTime));
        if (resendOtpTimeout.value <= 0) {
            clearInterval(timerId);
            isCountDownRunning.value = false;
        } else {
            resendOtpTimeout.value--;
            paddedResendOtpTimeout.value = resendOtpTimeout.value.toString().padStart(2, "0");
        }
    }, 1000);
}

countDown();

</script>

<template>
    <div>
        <h1 class="fs-2 fw-bold mt-5">{{ $t('verification.title') }}</h1>
        <p>{{ $t('verification.subtitle') }}</p>
        <form id="verification-form"  @submit.prevent="otpVerification" @keydown="form.onKeydown($event)" class="mt-5">
            <div>
                <label for="kode-otp">{{ $t('verification.otp_code') }}</label>
                <input type="text" name="code" id="kode-otp" :placeholder="$t('verification.enter_otp_code')"
                       class="form-control mt-2"
                       v-model="form.code">
                <div class="error mt-2 fs-6 fw-bold text-red-200"
                     v-if="form.errors.has('code')" v-html="form.errors.get('code')" />
            </div>

            <div class="d-flex flex-column mt-3">
                <button type="submit" class="btn btn-blue-700-rounded" :disabled="form.busy" form="verification-form">
                    {{ $t('verification.verify') }}</button>
                <router-link to="/login" class="btn btn-outline-white-rounded mt-3">
                    {{ $t('verification.cancel') }}</router-link>
            </div>
        </form>

        <p class="mt-4 text-center">{{ $t('verification.does_not_get_code') }}
            <a href="#" @click="resendOtpCode" class="kirim-otp text-white fw-bold text-decoration-none" v-if="!isCountDownRunning">
                {{ $t('verification.resend_code') }}</a>
            <span class="kirim-otp text-white fw-bold text-decoration-none" v-if="isCountDownRunning">
                OO:{{ paddedResendOtpTimeout }}</span>
        </p>
    </div>
</template>
