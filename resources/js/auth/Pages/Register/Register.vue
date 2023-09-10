<script setup>
import * as bootstrap from 'bootstrap';
import { onMounted, reactive, ref } from 'vue';
import {useAuthStore} from "@shared/+store/auth.store.js";
import router from "@auth/router.js";
import Form from "vform";
import axios from 'axios';

const authStore = useAuthStore();
const callingCode = import.meta.env.VITE_APP_CALLING_CODE;
const modalState = reactive({
    alreadyRegisteredModal: null
});
const form = reactive(
    new Form({
        phone_number: 82286393815,
        name: "John Doe",
        ssn: 2121212121212121,
        role: 1
    })
);

onMounted(() => {
    modalState.alreadyRegisteredModal = new bootstrap.Modal("#modal-register", {});
});

const showAlreadyRegisteredModal = () => {
    modalState.alreadyRegisteredModal.show();
}

const register = () => {
    form.post('/api/v1/register/patient').then((response) => {
        authStore.phoneNumber = response.data.data.phone_number;
        authStore.otpCreatedAt = response.data.data.otp_created_at;;
        authStore.otpUpdatedAt = response.data.data.otp_updated_at;
        authStore.otpTimeout = response.data.data.otp_timeout;
        authStore.ssn = response.data.data.ssn;
        authStore.userFullName = response.data.data.name;
        authStore.isRegistration = true;

        form.reset();
        router.push({path: '/verification'});
    }).catch((error) => {
        if (error.response.status === 409) {
            showAlreadyRegisteredModal();
        }

        if (error.response.status > 499) {
            // TODO: handle 5xx error
        }
    });
}

const navigateToLogin = async () => {
    const response = await axios.post('/api/v1/login', {
        "phone_number": form.phone_number
    });

    authStore.phoneNumber = response.data.data.phone_number;
    authStore.otpCreatedAt = response.data.data.otp_created_at;;
    authStore.otpUpdatedAt = response.data.data.otp_updated_at;
    authStore.otpTimeout = response.data.data.otp_timeout;

    form.reset();
    modalState.alreadyRegisteredModal.hide();
    router.push({path: '/verification'});
}

</script>
<template>
    <h1 class="fs-1 lh-150 fw-bolder mt-4 mb-0">{{ $t('welcome_message') }}</h1>
    <p class="fs-3 fw-bold mt-4">{{ $t('register.title') }}</p>
    <form id="register-form" class="mt-4"
          @submit.prevent="register" @keydown="form.onKeydown($event)">
        <div>
            <label for="phone_number">{{ $t('register.phone_number') }}</label>

            <div class="input-group flex-nowrap mt-2">
                <span class="input-group-text">{{ callingCode }}</span>
                <input type="tel" name="phone_number" id="phone_number" placeholder="8123940183020" class="form-control"
                       v-model="form.phone_number">
            </div>
            <div class="error mt-2 fs-6 fw-bold text-red-200"
                 v-if="form.errors.has('phone_number')" v-html="form.errors.get('phone_number')" />
        </div>

        <div class="mt-3">
            <label for="role">{{ $t('register.register_as') }}</label>
            <select name="role" id="role" class="form-select mt-2" v-model="form.role">
                <option value="1">{{ $t('register.patient') }}</option>
                <option value="2">{{ $t('register.doctor') }}</option>
            </select>
            <div class="error mt-2 fs-6 fw-bold text-red-200"
                 v-if="form.errors.has('role')" v-html="form.errors.get('role')" />
        </div>

        <div class="mt-3" v-if="!isDoctor">
            <label for="ssn">{{ $t('register.ssn') }}</label>
            <input type="number" name="ssn" id="ssn" placeholder="3829380183984920" class="form-control mt-2"
                   v-model="form.ssn">
            <div class="error mt-2 fs-6 fw-bold text-red-200"
                 v-if="form.errors.has('ssn')" v-html="form.errors.get('ssn')" />
        </div>

        <div class="mt-3">
            <label for="name">{{ $t('register.full_name') }}</label>
            <input type="text" name="name" id="name" placeholder="Muhammad Denis Adiswara" class="form-control mt-2"
                   v-model="form.name">
            <div class="error mt-2 fs-6 fw-bold text-red-200"
                 v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
        </div>

        <div class="mt-3 d-flex flex-column">
            <button type="submit" class="btn btn-blue-700-rounded">{{ $t('register.register') }}</button>

            <router-link to="/login"
                         class="rounded-pill mt-3 border-white text-white px-3 py-2 text-center text-decoration-none border border-1">{{ $t('register.cancel') }}</router-link>
        </div>
    </form>

    <div class="mt-4 text-center">
        <p>{{ $t('register.already_have_an_account') }} <router-link to="/login" class="fw-bold text-white text-decoration-none">{{ $t('register.login') }}</router-link></p>
    </div>

    <div class="modal" id="modal-register" aria-labelledby="Register Modal"  data-bs-backdrop="static" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header d-flex justify-content-between">
                    <div class="d-flex align-items-center col-gap-8">
                        <i class="bi bi-info-circle-fill icon-blue-500 fs-3"></i>

                        <h5 class="modal-title">{{ $t('register.phone_number_already_registered') }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x fs-2 icon-black"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ $t('register.phone_number_already_registered_desc') }}</p>
                </div>

                <div class="modal-footer flex-nowrap">
                    <button type="button" class="w-50 btn btn-link" data-bs-dismiss="modal">{{ $t('register.cancel') }}</button>
                    <button type="button" @click="navigateToLogin" class="w-50 btn-masuk btn btn-blue">{{ $t('register.login') }}</button>
                </div>
            </div>
        </div>
    </div>
</template>
