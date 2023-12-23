<script>

import Header from "@shared/Components/Header/Header.vue";
import SubmitButton from "@shared/Components/SubmitButton/SubmitButton.vue";
import { onMounted, reactive, ref } from "vue";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import { useRoute } from 'vue-router'
import router from "@patient/router.js";
import { convertDateToFormField } from "@shared/utils/helpers.js";
import apiRequest from "@shared/utils/axios.js";
import {helpers, maxLength, minLength, required} from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";

export default {
    components: {
      Header,
      SubmitButton
    },
    setup() {
        const callingCode = import.meta.env.VITE_APP_CALLING_CODE;
        const layoutStore = useLayoutStore();
        const isEditMode = ref(false);
        const route = useRoute();

        const form = reactive({
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
        });

        const rules = {
            id: {},
            name: {
                required: helpers.withMessage(
                    "Nama Lengkap tidak boleh kosong",
                    required
                ),
            },
            ssn: {
                required: helpers.withMessage(
                    "NIK tidak boleh kosong",
                    required
                ),
                minLength: helpers.withMessage(
                    "NIK kurang dari 16 digit",
                    minLength(16)
                ),
                maxLength: helpers.withMessage(
                    "NIK lebih dari 16 digit",
                    maxLength(16)
                ),
            },
            phone_number: {
                required: helpers.withMessage(
                    "No Hp tidak boleh kosong",
                    required
                ),
                minLength: helpers.withMessage(
                    "No Hp kurang dari 10 digit",
                    minLength(10)
                ),
                maxLength: helpers.withMessage(
                    "No Hp lebih dari 13 digit",
                    maxLength(13)
                ),
            },
            gender: {
                required: helpers.withMessage(
                    "Jenis kelamin tidak boleh kosong",
                    required
                ),
            },
            birth_date: {
                required: helpers.withMessage(
                    "Tanggal lahir tidak boleh kosong",
                    required
                ),
            },
            email: {},
            patient_id: { },
            medical_no: { },
        };

        const v$ = useVuelidate(rules, form);

        const fetchFamily = () => {
            layoutStore.isLoading = true;
            apiRequest.get(`/api/v1/patient/family/fetchsimrs/${route.params.id}`).then((response) => {
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
            apiRequest.put(`/api/v1/patient/family/${route.params.id}`, form).then((response) => {
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

        return {
            v$,
            callingCode,
            layoutStore,
            isEditMode,
            route,
            form,
            rules,
            fetchFamily,
            updateFamily
        };
    }
}
</script>

<template>
    <Header :title="$t('family.confirmation.title')" :with-back-url="false" :with-action-btn="false"></Header>

    <div class="px-4 pt-7 pb-4">

        <form @submit.prevent="updateFamily" class="d-flex flex-column rows-gap-16 mt-3">
            <div :class="{ error: v$.name.$errors.length }">
                <label for="nama">{{ $t('family.patient_name') }}
                </label>
                <input type="text" name="nama" id="nama" placeholder="John Doe" class="form-control mt-2"
                    v-model="form.name"
                       @input="v$.name.$touch()" readonly>
                <div
                    class="error mt-2 fs-6 fw-bold text-red-200"
                    v-for="error of v$.name.$errors"
                    :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>

            <div :class="{ error: v$.ssn.$errors.length }">
                <label for="nik">{{ $t('family.ssn') }}</label>
                <input type="number" name="nik" id="nik" placeholder="3123987564123" class="form-control mt-2"
                    v-model="form.ssn"
                       @input="v$.ssn.$touch()" readonly>

                <div
                        class="error mt-2 fs-6 fw-bold text-red-200"
                        v-for="error of v$.ssn.$errors"
                        :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>

            <div :class="{ error: v$.birth_date.$errors.length }">
                <label for="dob">{{ $t('family.birth_date') }}</label>
                <input type="date" name="dob" id="dob"
                       @input="v$.birth_date.$touch()" class="form-control mt-2" v-model="form.birth_date" readonly>
                <div
                        class="error mt-2 fs-6 fw-bold text-red-200"
                        v-for="error of v$.birth_date.$errors"
                        :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>

            <div :class="{ error: v$.phone_number.$errors.length }">
                <label for="nohp">{{ $t('family.phone_number') }} </label>
                <div class="input-group flex-nowrap mt-2">
                    <span class="input-group-text">{{ callingCode }}</span>
                    <input type="tel" name="no-hp"
                           @input="v$.phone_number.$touch()" id="no-hp" placeholder="8123940183020" class="form-control"
                        v-model="form.phone_number" readonly>
                </div>
                <div
                        class="error mt-2 fs-6 fw-bold text-red-200"
                        v-for="error of v$.phone_number.$errors"
                        :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>

            <div :class="{ error: v$.gender.$errors.length }">
                <label for="gender">{{ $t('family.gender') }}</label>
                <div class="d-flex col-gap-20 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" :id="$t('family.male')"
                            :value="$t('family.male')"
                               @input="v$.gender.$touch()" v-model="form.gender" readonly>
                        <label class="form-check-label" :for="$t('family.male')">
                            {{ $t('family.male') }}
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" :id="$t('family.female')"
                            :value="$t('family.female')"
                               @input="v$.gender.$touch()" v-model="form.gender" readonly>
                        <label class="form-check-label" :for="$t('family.female')">
                            {{ $t('family.female') }}
                        </label>
                    </div>
                </div>
                <div
                        class="error mt-2 fs-6 fw-bold text-red-200"
                        v-for="error of v$.gender.$errors"
                        :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>

            <div :class="{ error: v$.email.$errors.length }">
                <label for="email">{{ $t('family.email') }}</label>
                <input type="email" name="email" id="email" class="form-control mt-2" placeholder="email@example.com"
                    v-model="form.email"
                       @input="v$.email.$touch()" readonly>
                <div
                        class="error mt-2 fs-6 fw-bold text-red-200"
                        v-for="error of v$.email.$errors"
                        :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>

            <SubmitButton className="btn-blue-500-rounded" :text="$t('family.confirmation.verification')" />
            <router-link to="/family" class="text-center text-blue-500 text-decoration-none fw-bold">{{
                $t('family.cancel') }}</router-link>
        </form>
    </div>
</template>
