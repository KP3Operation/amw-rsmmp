<script>
import Header from "@shared/Components/Header/Header.vue";
import SubmitButton from "@shared/Components/SubmitButton/SubmitButton.vue";
import { onMounted, reactive, ref } from "vue";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import Form from "vform";
import * as bootstrap from "bootstrap";
import router from "@patient/router.js";
import { useRoute } from "vue-router";
import { convertDateToFormField } from "@shared/utils/helpers.js";
import apiRequest from "@shared/utils/axios.js";
import {helpers, maxLength, minLength, required, requiredIf} from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";

export default {
  components: {
    Header, SubmitButton
  },
  setup() {
    const callingCode = import.meta.env.VITE_APP_CALLING_CODE;
    const layoutStore = useLayoutStore();
    const newFamilyId = ref(0);
    const isEditMode = ref(false);
    const route = useRoute();

    const modalState = reactive({
      familyDataConfirmation: null,
    });

    const form = reactive({
      id: null,
      ssn: null,
      phone_number: null,
      name: null,
      gender: null,
      birth_date: null,
      email: null
    });

    const rules = {
      id: {  },
      name: {
        required: helpers.withMessage(
            "Nama Lengkap tidak boleh kosong",
            required),
      },
      ssn: {
        required: helpers.withMessage("NIK tidak boleh kosong",
            required),
        minLength: helpers.withMessage("NIK kurang dari 16 digit", minLength(16)),
        maxLength: helpers.withMessage("NIK lebih dari 16 digit", maxLength(16)),
      },
      phone_number: {
        required: helpers.withMessage("No Hp tidak boleh kosong", required),
        minLength: helpers.withMessage("No Hp kurang dari 10 digit", minLength(10)),
        maxLength: helpers.withMessage("No Hp lebih dari 13 digit", maxLength(13)),
      },
      gender: {
        required: helpers.withMessage("Jenis kelamin tidak boleh kosong", required),
      },
      birth_date: {
        required: helpers.withMessage("Tanggal lahir tidak boleh kosong", required),
      },
      email: {  }
    }

    const v$ = useVuelidate(rules, form);

    const confirmation = () => {
      modalState.familyDataConfirmation.show();
    }

    const storeFamilyConfirmFalse = () => {
      modalState.familyDataConfirmation.hide();
      router.push({ path: '/family' });
    }


    const storeFamilyConfirmTrue = () => {
      modalState.familyDataConfirmation.hide();
      router.push({ path: `/family/confirm/${newFamilyId.value}` });
    }

    const storeFamily = async () => {
      layoutStore.isLoading = true;

      const formValid = await v$.value.$validate()

      if (!formValid) {
        layoutStore.isLoading = false;
        return;
      }

      apiRequest.post('/api/v1/patient/family', form).then((response) => {
        const data = response.data.data;
        newFamilyId.value = data.family.id;
        modalState.familyDataConfirmation.show();
      }).catch((error) => {
        if (error.response.status === 401) {
          window.location.href = '/auth/login';
        }
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
      }).finally(() => {
        layoutStore.isLoading = false;
      });
    }

    const updateFamily = () => {
      layoutStore.isLoading = true;
      apiRequest.put(`/api/v1/patient/family/${route.params.id}`, form).then((response) => {
        const data = response.data.data;
        layoutStore.toggleInfoAlert(`Berhasil Mengubah Data Member ${data.family.name}`);
        router.push({ path: '/family' });
      }).catch((error) => {
        if (error.response.status === 401) {
          window.location.href = '/auth/login';
        }
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
      }).finally(() => {
        layoutStore.isLoading = false;
      });
    }

    const fetchFamily = () => {
      layoutStore.isLoading = true;
      apiRequest.get(`/api/v1/patient/family/${route.params.id}`).then((response) => {
        const data = response.data;
        form.id = data.family.id;
        form.name = data.family.name;
        form.ssn = data.family.ssn;
        form.phone_number = data.family.phone_number.replace(callingCode, "");
        form.gender = (data.family.gender === "F" || data.family.gender === "Perempuan") ? "Perempuan" : "Laki-Laki";
        form.birth_date = convertDateToFormField(data.family.birth_date);
        form.email = data.family.email;
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
      modalState.familyDataConfirmation = new bootstrap.Modal("#modal-konfirmasi");
      if (route.params.id !== undefined) {
        fetchFamily();
        isEditMode.value = true;
      }
    });

    return {
      v$,
      callingCode,
      layoutStore,
      newFamilyId,
      isEditMode,
      route,
      modalState,
      form,
      confirmation,
      storeFamilyConfirmTrue,
      storeFamilyConfirmFalse,
      storeFamily,
      updateFamily,
      fetchFamily
    }
  }
}
</script>

<template>
    <Header :title="isEditMode ? $t('family.edit.title') : $t('family.create.title')" :with-back-url="true"
        pageName="FamilyPage" :with-action-btn="false">
    </Header>

    <div class="px-4 pt-7 pb-4">
        <p class="fs-5 text-red-500">* {{ $t('family.required_field') }}</p>

        <form @submit.prevent="isEditMode ? updateFamily() : storeFamily()"
            class="d-flex flex-column rows-gap-16 mt-3">
            <div :class="{ error: v$.name.$errors.length }">
                <label for="nama">{{ $t('family.patient_name') }} <span class="text-red-500 fw-semibold">*</span>
                </label>
                <input type="text" name="nama" id="nama"
                       placeholder="John Doe" class="form-control mt-2"
                       @input="v$.name.$touch()"
                       v-model="form.name">
              <div class="error mt-2 fs-6 fw-bold text-red-200" v-for="error of v$.name.$errors"
                   :key="error.$uid">
                {{ error.$message }}
              </div>
            </div>

            <div :class="{ error: v$.ssn.$errors.length }">
                <label for="nik">{{ $t('family.ssn') }} <span class="text-red-500 fw-semibold">*</span></label>
                <input type="number" name="nik" id="nik"
                       placeholder="3123987564123" class="form-control mt-2"
                       @input="v$.ssn.$touch()"
                       v-model="form.ssn" :readonly="isEditMode">
              <div class="error mt-2 fs-6 fw-bold text-red-200" v-for="error of v$.ssn.$errors"
                   :key="error.$uid">
                {{ error.$message }}
              </div>
            </div>

            <div :class="{ error: v$.birth_date.$errors.length }">
                <label for="dob">{{ $t('family.birth_date') }} <span class="text-red-500 fw-semibold">*</span></label>
                <input type="date" name="dob" id="dob" class="form-control mt-2"
                       @input="v$.birth_date.$touch()"
                       v-model="form.birth_date">
              <div class="error mt-2 fs-6 fw-bold text-red-200" v-for="error of v$.birth_date.$errors"
                   :key="error.$uid">
                {{ error.$message }}
              </div>
            </div>

            <div :class="{ error: v$.phone_number.$errors.length }">
                <label for="nohp">{{ $t('family.phone_number') }} <span class="text-red-500 fw-semibold">*</span></label>
                <div class="input-group flex-nowrap mt-2">
                    <span class="input-group-text">{{ callingCode }}</span>
                    <input type="tel" name="no-hp" id="no-hp" placeholder="8123940183020"
                           class="form-control"
                           @input="v$.phone_number.$touch()"
                           v-model="form.phone_number">
                </div>
              <div class="error mt-2 fs-6 fw-bold text-red-200" v-for="error of v$.phone_number.$errors"
                   :key="error.$uid">
                {{ error.$message }}
              </div>
            </div>

            <div :class="{ error: v$.gender.$errors.length }">
                <label for="gender">{{ $t('family.gender') }} <span class="text-red-500 fw-semibold">*</span></label>
                <div class="d-flex col-gap-20 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" :id="$t('family.male')"
                            :value="$t('family.male')" v-model="form.gender"
                               @input="v$.gender.$touch()">
                        <label class="form-check-label" :for="$t('family.male')">
                            {{ $t('family.male') }}
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" :id="$t('family.female')"
                            :value="$t('family.female')" v-model="form.gender"
                               @input="v$.$touch()">
                        <label class="form-check-label" :for="$t('family.female')">
                            {{ $t('family.female') }}
                        </label>
                    </div>
                </div>
              <div class="error mt-2 fs-6 fw-bold text-red-200" v-for="error of v$.gender.$errors"
                   :key="error.$uid">
                {{ error.$message }}
              </div>
            </div>

            <div :class="{ error: v$.email.$errors.length }">
                <label for="email">{{ $t('family.email') }}</label>
                <input type="email" name="email" id="email" class="form-control mt-2" placeholder="email@example.com"
                       v-model="form.email"
                       @input="v$.email.$touch()">
              <div class="error mt-2 fs-6 fw-bold text-red-200" v-for="error of v$.email.$errors"
                   :key="error.$uid">
                {{ error.$message }}
              </div>
            </div>

            <SubmitButton className="btn-blue-500-rounded"
                :text="isEditMode ? $t('family.edit.save') : $t('family.save')" />
            <router-link v-show="!layoutStore.isLoading" :to="{name: 'FamilyPage'}"
                class="text-center text-blue-500 text-decoration-none fw-bold">{{
                    $t('family.cancel') }}</router-link>
        </form>
    </div>

    <div class="modal" id="modal-konfirmasi" aria-labelledby="Confirmation Modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div class="d-flex align-items-center col-gap-8">
                        <i class="bi bi-info-circle-fill icon-blue-500 fs-3"></i>
                        <h5 class="modal-title">{{ $t('family.create.confirmation_modal.title') }}</h5>
                    </div>
                    <button type="button" class="btn-close" @click="modalState.familyDataConfirmation.hide()"
                        aria-label="Close">
                        <i class="bi bi-x fs-2 icon-black"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ $t('family.create.confirmation_modal.message') }}</p>
                </div>
                <div class="modal-footer flex-nowrap">
                    <button @click="storeFamilyConfirmFalse" type="button"
                        class="btn w-50 text-center text-gray-800 fw-bold text-decoration-none">{{
                            $t('family.create.confirmation_modal.no') }}</button>
                    <button @click="storeFamilyConfirmTrue" type="button" class="w-50 btn-next btn btn-blue">{{
                        $t('family.create.confirmation_modal.yes') }}</button>
                </div>
            </div>
        </div>
    </div>
</template>
