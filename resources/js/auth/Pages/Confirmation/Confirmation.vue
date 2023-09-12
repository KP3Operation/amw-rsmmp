<script setup>
import Form from "vform";
import { reactive } from "vue";
import {useAuthStore} from "@shared/+store/auth.store.js";
import DefaultAvatar from "@resources/static/images/avatar-default.png";
import SubmitButton from "@shared/Components/SubmitButton/SubmitButton.vue";
import { useLayoutStore } from "@shared/+store/layout.store.js";

const authStore = useAuthStore();
const layoutStore = useLayoutStore();
const callingCode = import.meta.env.VITE_APP_CALLING_CODE;
const form = reactive(
    new Form({
        phone_number: authStore.phoneNumber.replace(import.meta.env.VITE_APP_CALLING_CODE, ""),
        ssn: authStore.ssn,
        name: authStore.userFullName,
        gender: null,
        birth_date: null,
        email: null,
        doctor_id: authStore.doctorId,
        smf_name: authStore.smfName
    })
);

const confirm = async () => {
    layoutStore.isLoading = true;
    const doctorUrl = `/api/v1/register/doctor/${authStore.phoneNumber.replace(import.meta.env.VITE_APP_CALLING_CODE, "")}`;
    const patientUrl = `/api/v1/register/patient/${authStore.phoneNumber.replace(import.meta.env.VITE_APP_CALLING_CODE, "")}`;
    form.put(authStore.userRole === 'patient' ? patientUrl : doctorUrl)
        .then((response) => {
            form.reset();
            const data = response.data.data;
            authStore.$patch({
                otpCreatedAt: data.otp_created_at,
                otpUpdatedAt: data.otp_updated_at,
                otpTimeout: data.otp_timeout,
                phoneNumber: data.phone_number
            });
            if (authStore.userRole === 'patient') {
                window.location.href = `/patient/home`;
            }

            if (authStore.userRole === 'doctor') {
                window.location.href = `/doctor/home`;
            }
        }).catch((error) => {
            //
    }).finally(() => {
        layoutStore.isLoading = false;
    });
}

</script>

<template>
    <div>
        <h1 class="fs-2 lh-150 fw-bolder mt-4 mb-0" v-if="authStore.userRole === 'patient'">{{ $t('confirmation.title.patient') }}</h1>
        <h1 class="fs-2 lh-150 fw-bolder mt-4 mb-0" v-if="authStore.userRole === 'doctor'">{{ $t('confirmation.title.doctor') }}</h1>
        <p class="fs-5 mt-4 text-red-200" v-if="authStore.userRole === 'patient'">{{ $t('confirmation.subtitle') }}</p>
        <form  @submit.prevent="confirm" @keydown="form.onKeydown($event)" class="mt-4">

            <div v-if="authStore.userRole === 'doctor'">
                <img :src="DefaultAvatar" alt="Foto Dokter" width="60" height="60"
                     class="rounded-pill border border-1 border-white">
            </div>

            <div class="mt-3" v-if="authStore.userRole === 'doctor'">
                <label for="email">{{ $t('confirmation.doctor_id') }}</label>
                <input type="text" name="text" id="smf_name" class="form-control mt-2" v-model="form.doctor_id" readonly>
                <div class="error mt-2 fs-6 fw-bold text-red-200"
                     v-if="form.errors.has('doctor_id')" v-html="form.errors.get('doctor_id')" />
            </div>

            <div v-if="authStore.userRole === 'patient'">
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

            <div class="mt-3" v-if="authStore.userRole === 'patient'">
                <label for="ssn">{{ $t('confirmation.ssn') }}<span class="text-red-200 fw-semibold">*</span></label>
                <input type="number" name="ssn" id="ssn" placeholder="3829380183984920"
                    class="form-control mt-2"
                    v-model="form.ssn">
                <div class="error mt-2 fs-6 fw-bold text-red-200"
                   v-if="form.errors.has('ssn')" v-html="form.errors.get('ssn')" />
            </div>

            <div class="mt-3">
                <label for="name" v-if="authStore.userRole === 'patient'">{{  $t('confirmation.full_name.patient') }}<span class="text-red-200 fw-semibold">*</span></label>
                <label for="name" v-if="authStore.userRole === 'doctor'">{{  $t('confirmation.full_name.doctor') }}</label>
                <input type="text" name="name" id="name" placeholder="Muhammad Denis Adiswara"
                    class="form-control mt-2"
                    v-model="form.name"
                :readonly="authStore.userRole === 'doctor'">
                <div class="error mt-2 fs-6 fw-bold text-red-200"
                   v-if="form.errors.has('name')" v-html="form.errors.get('name')"/>
            </div>

            <div class="mt-3" v-if="authStore.userRole === 'patient'">
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

            <div class="mt-3" v-if="authStore.userRole === 'patient'">
                <label for="birth_date">{{ $t('confirmation.birth_date') }} <span class="text-red-200 fw-semibold">*</span></label>
                <input type="date" name="birth_date" id="birth_date" class="form-control mt-2" v-model="form.birth_date">
                <div class="error mt-2 fs-6 fw-bold text-red-200"
                   v-if="form.errors.has('birth_date')" v-html="form.errors.get('birth_date')" />
            </div>

            <div class="mt-3" v-if="authStore.userRole === 'patient'">
                <label for="email">{{ $t('confirmation.email') }}</label>
                <input type="email" name="email" id="email" placeholder="johndoe@example.com" class="form-control mt-2" v-model="form.email">
                <div class="error mt-2 fs-6 fw-bold text-red-200"
                   v-if="form.errors.has('email')" v-html="form.errors.get('email')" />
            </div>

            <div class="mt-3" v-if="authStore.userRole === 'doctor'">
                <label for="email">{{ $t('confirmation.smf_name') }}</label>
                <input type="text" name="text" id="smf_name" class="form-control mt-2" v-model="form.smf_name" readonly>
                <div class="error mt-2 fs-6 fw-bold text-red-200"
                     v-if="form.errors.has('smf_name')" v-html="form.errors.get('smf_name')" />
            </div>

            <div class="mt-3 d-flex flex-column">
                <SubmitButton :text="$t('confirmation.save')" className="btn-blue-700-rounded" />
                <router-link to="/register"
                    class="rounded-pill mt-3 border-white text-white px-3 py-2 text-center text-decoration-none border border-1">{{ $t('confirmation.cancel') }}</router-link>
            </div>
        </form>
    </div>
</template>
