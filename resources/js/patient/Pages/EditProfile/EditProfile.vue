<script>
import { useAuthStore } from "@shared/+store/auth.store.js";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import SubmitButton from "@shared/Components/SubmitButton/SubmitButton.vue";
import Header from "@shared/Components/Header/Header.vue";
import useVuelidate from "@vuelidate/core";
import {mapActions, mapState} from "pinia";
import apiRequest from "@shared/utils/axios.js";
import {helpers, required} from "@vuelidate/validators";

export default {
  components: { Header, SubmitButton },
  setup() {
    return {
      v$: useVuelidate()
    }
  },
  data() {
    return {
      profileForm: {
        name: '',
        gender: '',
        birthDate: '',
        email: ''
      }
    }
  },
  validations() {
    return {
      profileForm: {
        name: {
          required: helpers.withMessage('Nama tidak boleh kosong', required),
        },
        gender: {
          required: helpers.withMessage('Jenis kelamin tidak boleh kosong', required),
        },
        birthDate: {
          required: helpers.withMessage('Tanggal lahir boleh kosong', required),
        },
        email: { }
      }
    }
  },
  computed: {
    ...mapState(useAuthStore, {
      userData: 'userData',
      userPatientData: 'userPatientData'
    }),
    ...mapState(useLayoutStore, {
      isLoading: 'isLoading',

    }),
  },
  mounted() {
    this.profileForm = {
      name: this.userData.userFullName,
      gender: this.userPatientData.gender,
      birthDate: this.userPatientData.birthDate,
      email: this.userData.userEmail,
    };
  },
  methods: {
    ...mapActions(useLayoutStore, {
      updateLoadingState: 'updateLoadingState',
      toggleSuccessAlert: 'toggleSuccessAlert',
      toggleErrorAlert: 'toggleErrorAlert'
    }),
    ...mapActions(useAuthStore, {
      updateUserData: 'updateUserData',
      updateUserPatientData: 'updateUserPatientData'
    }),
    async updateProfile() {
      this.updateLoadingState(true);
      const formValid = await this.v$.$validate();
      if (!formValid) {
        this.updateLoadingState(false);
        return;
      }

      apiRequest.put(`/api/v1/me/${this.userData.userId}`, {
        ...this.profileForm,
        birth_date: this.profileForm.birthDate
      }).then((response)=>{
        this.updateUserData({
          userFullName: this.profileForm.name,
          gender: this.profileForm.gender,
          userEmail: this.profileForm.email
        });
        this.updateUserPatientData({
          birthDate: this.profileForm.birthDate
        });

        this.toggleSuccessAlert(t('profile.edit.success'));
        this.$router.push({ name: 'ProfilePage' });
      }).catch((error) => {
        this.toggleErrorAlert(t('profile.edit.failed'));
      }).finally(() => {
        this.updateLoadingState(false);
      })
    }
  },
}

</script>
<template>
    <Header :title="$t('profile.edit.title')" :with-back-url="true" page-name="ProfilePage"></Header>

    <div class="px-4 pt-7">
        <form @submit.prevent="updateProfile"
              class="d-flex flex-column rows-gap-16">
            <div :class="{ error: v$.profileForm.name.$errors.length }">
                <label for="name">{{ $t('profile.edit.full_name') }}</label>
                <input type="text" name="name" id="name" placeholder="Muhammad John Doe"
                       class="form-control mt-2"
                       @input="v$.$touch()"
                       v-model="profileForm.name">
              <div class="error mt-2 fs-6 fw-bold text-red-200" v-for="error of v$.profileForm.name.$errors"
                   :key="error.$uid">
                {{ error.$message }}
              </div>
            </div>
            <div :class="{ error: v$.profileForm.gender.$errors.length }">
                <label for="gender">{{ $t('profile.edit.gender') }}<span class="text-red-200 fw-semibold">*</span></label>
                <div class="d-flex col-gap-20 mt-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" :id="$t('profile.edit.male')"
                            :value="$t('profile.edit.male')"
                               v-model="profileForm.gender"
                               @input="v$.$touch()">
                        <label class="form-check-label" :for="$t('profile.edit.male')">
                            {{ $t('profile.edit.male') }}
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="gender" :id="$t('profile.edit.female')"
                            :value="$t('profile.edit.female')" v-model="profileForm.gender"
                               @input="v$.$touch()">
                        <label class="form-check-label" :for="$t('profile.edit.female')">
                            {{ $t('profile.edit.female') }}
                        </label>
                    </div>
                </div>
              <div class="error mt-2 fs-6 fw-bold text-red-200" v-for="error of v$.profileForm.gender.$errors"
                   :key="error.$uid">
                {{ error.$message }}
              </div>
            </div>

            <div :class="{ error: v$.profileForm.birthDate.$errors.length }">
                <label for="birth_date">{{ $t('profile.edit.birth_date') }} <span
                        class="text-red-200 fw-semibold">*</span></label>
                <input type="date" name="birth_date" id="birth_date" class="form-control mt-2"
                       v-model="profileForm.birthDate"
                       @input="v$.$touch()">
              <div class="error mt-2 fs-6 fw-bold text-red-200" v-for="error of v$.profileForm.birthDate.$errors"
                   :key="error.$uid">
                {{ error.$message }}
              </div>
            </div>

            <div :class="{ error: v$.profileForm.email.$errors.length }">
                <label for="email">{{ $t('profile.edit.email') }}</label>
                <input type="email" name="email" id="email" placeholder="johndoe@example.com" class="form-control mt-2"
                    v-model="profileForm.email">
              <div class="error mt-2 fs-6 fw-bold text-red-200" v-for="error of v$.profileForm.email.$errors"
                   :key="error.$uid">
                {{ error.$message }}
              </div>
            </div>

            <SubmitButton :text="$t('profile.edit.update')" className="btn-blue-500-rounded" />

            <router-link :to="{name: 'ProfilePage'}"
                         class="text-blue-500 text-center text-decoration-none fw-semibold"
                        v-if="!isLoading">{{
                $t('profile.edit.cancel') }}</router-link>
        </form>
    </div>
</template>
