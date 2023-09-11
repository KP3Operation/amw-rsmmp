<script setup>
import Form from "vform";
import { reactive } from "vue";
import {useAuthStore} from "@shared/+store/auth.store.js";

const authStore = useAuthStore();
const callingCode = import.meta.env.VITE_APP_CALLING_CODE;
const form = reactive(
    new Form({
        phone_number: authStore.phoneNumber.replace(import.meta.env.VITE_APP_CALLING_CODE, ""),
        ssn: authStore.ssn,
        name: authStore.userFullname,
        gender: null,
        birth_date: null,
        email: null
    })
);

const confirm = async () => {
    const response = await form.put(`/api/v1/register/patient/${authStore.phoneNumber.replace(import.meta.env.VITE_APP_CALLING_CODE, "")}`);
    authStore.phoneNumber = response.data.data.phone_number;
    authStore.otpCreatedAt = response.data.data.otp_created_at;;
    authStore.otpUpdatedAt = response.data.data.otp_updated_at;
    authStore.otpTimeout = response.data.data.otp_timeout;

    form.reset();
    window.location.href = `/patient/home`;
}

</script>

<template>
    <div>
        <h1 class="fs-2 lh-150 fw-bolder mt-4 mb-0">{{ $t('confirmation.title') }}</h1>
        <p class="fs-5 mt-4 text-red-200">{{ $t('confirmation.subtitle') }}</p>
        <form  @submit.prevent="confirm" @keydown="form.onKeydown($event)" class="mt-4">
            <div>
                <label for="phone_number">{{ $t('confirmation.phone_number') }}<span class="text-red-200 fw-semibold">*</span></label>
                <div class="input-group flex-nowrap mt-2">
                    <span class="input-group-text">{{ callingCode }}</span>
                    <input type="tel" name="phone_number" id="phone_number" placeholder="8123940183020"
                        class="form-control"
                        v-model="form.phone_number"
                        readonly>
                </div>
                <div class="error mt-2 fs-6 fw-bold text-red-200"
                   v-if="form.errors.has('phone_number')" v-html="form.errors.get('phone_number')" />
            </div>

            <div class="mt-3">
                <label for="ssn">{{ $t('confirmation.ssn') }}<span class="text-red-200 fw-semibold">*</span></label>
                <input type="number" name="ssn" id="ssn" placeholder="3829380183984920"
                    class="form-control mt-2"
                    v-model="form.ssn">
                <div class="error mt-2 fs-6 fw-bold text-red-200"
                   v-if="form.errors.has('ssn')" v-html="form.errors.get('ssn')" />
            </div>
            <div class="mt-3">
                <label for="name">{{ $t('confirmation.full_name') }}<span class="text-red-200 fw-semibold">*</span></label>
                <input type="text" name="name" id="name" placeholder="Muhammad Denis Adiswara"
                    class="form-control mt-2"
                    v-model="form.name">
                <div class="error mt-2 fs-6 fw-bold text-red-200"
                   v-if="form.errors.has('name')" v-html="form.errors.get('name')" />
            </div>

            <div class="mt-3">
                <label for="gender">{{ $t('confirmation.gender') }}<span class="text-red-200 fw-semibold">*</span></label>
                <div class="d-flex col-gap-20 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" :id="$t('confirmation.male')"
                            :value="$t('confirmation.male')"
                            v-model="form.gender">
                        <label class="form-check-label" :for="$t('confirmation.male')">
                            {{ $t('confirmation.male') }}
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" :id="$t('confirmation.female')" :value="$t('confirmation.female')"
                            v-model="form.gender">
                        <label class="form-check-label" :for="$t('confirmation.female')">
                            {{ $t('confirmation.female') }}
                        </label>
                    </div>
                </div>
                <div class="error mt-2 fs-6 fw-bold text-red-200"
                   v-if="form.errors.has('gender')" v-html="form.errors.get('gender')" />
            </div>

            <div class="mt-3">
                <label for="birth_date">{{ $t('confirmation.birth_date') }} <span class="text-red-200 fw-semibold">*</span></label>
                <input type="date" name="birth_date" id="birth_date" class="form-control mt-2" v-model="form.birth_date">
                <div class="error mt-2 fs-6 fw-bold text-red-200"
                   v-if="form.errors.has('birth_date')" v-html="form.errors.get('birth_date')" />
            </div>

            <div class="mt-3">
                <label for="email">{{ $t('confirmation.email') }}</label>
                <input type="email" name="email" id="email" placeholder="johndoe@example.com" class="form-control mt-2" v-model="form.email">
                <div class="error mt-2 fs-6 fw-bold text-red-200"
                   v-if="form.errors.has('email')" v-html="form.errors.get('email')" />
            </div>

            <div class="mt-3 d-flex flex-column">
                <button type="submit" class="btn btn-blue-700-rounded">{{ $t('confirmation.save') }}</button>

                <router-link to="/register"
                    class="rounded-pill mt-3 border-white text-white px-3 py-2 text-center text-decoration-none border border-1">{{ $t('confirmation.cancel') }}</router-link>
            </div>
        </form>
    </div>
</template>
