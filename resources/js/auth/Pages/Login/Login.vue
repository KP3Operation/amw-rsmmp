<script setup>
import Form from "vform";
import { reactive } from "vue";
import {useAuthStore} from "@shared/+store/auth.store.js";
import router from "@auth/router.js";

const authStore = useAuthStore();
const callingCode = import.meta.env.VITE_APP_CALLING_CODE;
const form = reactive(
    new Form({
        phone_number: null
    })
);

const login = async () => {
    const response = await form.post('/api/v1/login');
    authStore.phoneNumber = response.data.data.phone_number;
    authStore.otpCreatedAt = response.data.data.created_at;
    authStore.otpUpdatedAt = response.data.data.updated_at;
    authStore.otpTimeout = response.data.data.timeout;
    form.reset();
    router.push({path: '/verification'});
}

</script>

<template>
    <div>
        <h1 class="fs-1 mt-6 fw-bold">{{ $t('welcome_message') }}</h1>
        <h2 class="mt-6 fs-3 fw-bold">{{ $t('login.login_to_account') }}</h2>
        <form id="login-form" class="mt-6"
              @submit.prevent="login" @keydown="form.onKeydown($event)">
            <div>
                <label for="no-hp">{{ $t('login.phone_number') }}</label>
                <div class="input-group flex-nowrap mt-2">
                    <span class="input-group-text">{{ callingCode }}</span>
                    <input type="tel" name="phone_number"
                           id="no-hp" placeholder="8123940183020"
                           class="form-control"
                           v-model="form.phone_number">
                </div>
                <div class="error mt-2 fs-6 fw-bold text-red-200"
                   v-if="form.errors.has('phone_number')" v-html="form.errors.get('phone_number')" />
            </div>

            <div class="mt-4 d-flex flex-column">
                <button class="btn btn-blue-700-rounded" type="submit" form="login-form" :disabled="form.busy">
                    {{ $t('login') }}</button>
            </div>
        </form>

        <p class="mt-4 text-center">
            {{ $t('login.does_not_have_account') }} <a href="#" class="fw-bold text-decoration-none text-white">{{ $t('login.register')
            }}</a>
        </p>
    </div>
</template>
