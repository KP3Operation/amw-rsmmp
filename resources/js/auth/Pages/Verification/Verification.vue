<script setup>
import Form from "vform";
import {reactive} from "vue";
import axios from "axios";
import {useAuthStore} from "@shared/+store/auth.store.js";

const authStore = useAuthStore();
const form = reactive(
    new Form({
        code: null
    })
);

const otpVerification = async () => {
    const response = await form.post(`/api/v1/verification`);
    form.reset();
    console.log("res: ", response);
}

const resendOtpCode = async () => {
    const response = await axios.post(`/api/v1/login`, {
        "phone_number": authStore.phoneNumber.replace(import.meta.env.VITE_APP_CALLING_CODE, "")
    });
    authStore.phoneNumber = response.data.data.phone_number;
    authStore.otpCreatedAt = response.data.data.created_at;
    authStore.otpUpdatedAt = response.data.data.updated_at;
    authStore.otpTimeout = response.data.data.timeout;
    console.log("resend: ", response);
}

// TODO: Implement timout counting

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
            <a href="#" @click="resendOtpCode" class="kirim-otp text-white fw-bold text-decoration-none">
                {{ $t('verification.resend_code') }}</a></p>

        <div class="d-none alert alert-dismissible alert-red d-flex col-gap-16 shadow text-red-500 fs-5 fw-semibold"
             role="alert">
            <p></p>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="bi bi-x icon-red-600 fs-2 fw-bold"></i>
            </button>
        </div>
    </div>
</template>
