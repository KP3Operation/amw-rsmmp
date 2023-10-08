<script setup>
import Form from "vform";
import { onMounted, reactive } from "vue";
import { useAuthStore } from "@shared/+store/auth.store.js";
import router from "@auth/router.js";
import SubmitButton from "@shared/Components/SubmitButton/SubmitButton.vue";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import * as bootstrap from "bootstrap";

const modalState = reactive({
    notRegisteredModal: null,
});
const authStore = useAuthStore();
const layoutStore = useLayoutStore();
const callingCode = import.meta.env.VITE_APP_CALLING_CODE;
const form = reactive(
    new Form({
        phone_number: null
    })
);

const login = async () => {
    layoutStore.isLoading = true;
    form.post('/api/v1/login').then((response) => {
        const data = response.data.data;
        form.reset();
        authStore.phoneNumber = data.phone_number;
        authStore.otpCreatedAt = data.otp_created_at;
        authStore.otpUpdatedAt = data.otp_updated_at;
        authStore.otpTimeout = data.otp_timeout;
        router.push({ path: '/verification' });
    }).catch((error) => {
        if (error.response.status === 404) {
            modalState.notRegisteredModal.show();
        } else {
            layoutStore.toggleErrorAlert(`${error.response.data.message}`);
        }
    }).finally(() => {
        layoutStore.isLoading = false;
    });
}

const navigateToRegister = async () => {
    authStore.phoneNumber = form.phone_number;
    form.reset();
    modalState.notRegisteredModal.hide();
    router.push({ path: '/register' });
}

onMounted(() => {
    modalState.notRegisteredModal = new bootstrap.Modal("#modal-register");
});
</script>

<template>
    <div>
        <h1 class="fs-1 mt-6 fw-bold">{{ $t('welcome_message') }}</h1>
        <h2 class="mt-6 fs-3 fw-bold">{{ $t('login.login_to_account') }}</h2>
        <form id="login-form" class="mt-6" @submit.prevent="login" @keydown="form.onKeydown($event)">
            <div>
                <label for="no-hp">{{ $t('login.phone_number') }}</label>
                <div class="input-group flex-nowrap mt-2">
                    <span class="input-group-text">{{ callingCode }}</span>
                    <input type="tel" name="phone_number" id="no-hp" placeholder="8123940183020" class="form-control"
                        v-model="form.phone_number">
                </div>
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('phone_number')"
                    v-html="form.errors.get('phone_number')" />
            </div>

            <div class="mt-4 d-flex flex-column">
                <SubmitButton :text="$t('login.login')" className="btn-blue-700-rounded" />
            </div>
        </form>

        <p class="mt-4 text-center">
            {{ $t('login.does_not_have_account') }}
            <router-link to="/register" class="fw-bold text-decoration-none text-white">
                {{ $t('login.register') }}
            </router-link>
        </p>
    </div>

    <div class="modal" id="modal-register" aria-labelledby="Register Modal" data-bs-backdrop="static" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header d-flex justify-content-between">
                    <div class="d-flex align-items-center col-gap-8">
                        <i class="bi bi-info-circle-fill icon-blue-500 fs-3"></i>

                        <h5 class="modal-title">{{ $t('login.phone_number_not_registered') }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x fs-2 icon-black"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ $t('login.phone_number_not_registered_desc') }}</p>
                </div>

                <div class="modal-footer flex-nowrap">
                    <button type="button" class="w-50 btn btn-link" data-bs-dismiss="modal">{{ $t('register.cancel')
                    }}</button>
                    <button type="button" @click="navigateToRegister" class="w-50 btn-masuk btn btn-blue">
                        {{ $t('login.yes') }},
                        {{ $t('login.register') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
