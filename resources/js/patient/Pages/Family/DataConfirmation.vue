<script setup>
import Header from "@shared/Components/Header/Header.vue";
import SubmitButton from "@shared/Components/SubmitButton/SubmitButton.vue";
import { onMounted, reactive, ref } from "vue";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import Form from "vform";
import { useRoute } from 'vue-router'
import router from "@patient/router.js";
import { convertDateToFormField } from "@shared/utils/helpers.js";
import axios from "axios";

const callingCode = import.meta.env.VITE_APP_CALLING_CODE;
const layoutStore = useLayoutStore();
const isEditMode = ref(false);
const route = useRoute();

const form = reactive(
    new Form({
        id: null,
        user_id: null,
        ssn: null,
        phone_number: null,
        name: null,
        gender: null,
        birth_date: null,
        email: null,
        patient_id: null,
        medical_no: null,
    })
);

const fetchFamily = () => {
    layoutStore.isLoading = true;
    axios.get(`/api/v1/patient/family/fetchsimrs/${route.params.id}`).then((response) => {
        const data = response.data;
        form.id = data.family.id;
        form.name = data.family.name;
        form.ssn = data.family.ssn;
        form.phone_number = data.family.phone_number.replace(callingCode, "");
        form.gender = (data.family.gender === "F") ? "Perempuan" : "Laki-Laki";
        form.birth_date = convertDateToFormField(data.family.birth_date);
        form.email = data.family.email;
        form.user_id = data.family.user_id;
        form.patient_id = data.family.patient_id;
        form.medical_no = data.family.medical_no;
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = false;
    });
}

const updateFamily = () => {
    layoutStore.isLoading = true;
    form.put(`/api/v1/patient/family/${route.params.id}`).then((response) => {
        router.push({ path: '/family' });
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = false;
    });
}

onMounted(() => {
    layoutStore.$patch({
        isFullView: true
    });
    fetchFamily();
});
</script>

<template>
    <Header :title="$t('family.confirmation.title')" :with-back-url="false" :with-action-btn="false"></Header>

    <div class="px-4 pt-7 pb-4">

        <form @submit.prevent="updateFamily" @keydown="form.onKeydown($event)" class="d-flex flex-column rows-gap-16 mt-3">
            <div>
                <label for="user_id">{{ $t('family.main_user') }}
                </label>
                <input type="text" name="user_id" id="user_id" placeholder="0" class="form-control mt-2"
                    v-model="form.user_id" readonly>
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('user_id')"
                    v-html="form.errors.get('user_id')" />
            </div>
            <div>
                <label for="nama">{{ $t('family.patient_name') }}
                </label>
                <input type="text" name="nama" id="nama" placeholder="John Doe" class="form-control mt-2"
                    v-model="form.name" readonly>
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('name')"
                    v-html="form.errors.get('name')" />
            </div>

            <div>
                <label for="nik">{{ $t('family.ssn') }}</label>
                <input type="number" name="nik" id="nik" placeholder="3123987564123" class="form-control mt-2"
                    v-model="form.ssn" readonly>
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('ssn')"
                    v-html="form.errors.get('ssn')" />
            </div>

            <div>
                <label for="dob">{{ $t('family.birth_date') }}</label>
                <input type="date" name="dob" id="dob" class="form-control mt-2" v-model="form.birth_date" readonly>
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('birth_date')"
                    v-html="form.errors.get('birth_date')" />
            </div>

            <div>
                <label for="nohp">{{ $t('family.phone_number') }} </label>
                <div class="input-group flex-nowrap mt-2">
                    <span class="input-group-text">{{ callingCode }}</span>
                    <input type="tel" name="no-hp" id="no-hp" placeholder="8123940183020" class="form-control"
                        v-model="form.phone_number" readonly>
                </div>
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('phone_number')"
                    v-html="form.errors.get('phone_number')" />
            </div>

            <div>
                <label for="gender">{{ $t('family.gender') }}</label>
                <div class="d-flex col-gap-20 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" :id="$t('family.male')"
                            :value="$t('family.male')" v-model="form.gender" readonly>
                        <label class="form-check-label" :for="$t('family.male')">
                            {{ $t('family.male') }}
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" :id="$t('family.female')"
                            :value="$t('family.female')" v-model="form.gender" readonly>
                        <label class="form-check-label" :for="$t('family.female')">
                            {{ $t('family.female') }}
                        </label>
                    </div>
                </div>
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('gender')"
                    v-html="form.errors.get('gender')" />
            </div>

            <div>
                <label for="email">{{ $t('family.email') }}</label>
                <input type="email" name="email" id="email" class="form-control mt-2" placeholder="email@example.com"
                    v-model="form.email" readonly>
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('email')"
                    v-html="form.errors.get('email')" />
            </div>

            <SubmitButton className="btn-blue-500-rounded" :text="$t('family.confirmation.verification')" />
            <router-link to="/family" class="text-center text-blue-500 text-decoration-none fw-bold">{{
                $t('family.cancel') }}</router-link>
        </form>
    </div>
</template>
