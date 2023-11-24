<script>
import DefaultAvatar from "@resources/static/images/avatar-default.png";
import { useAuthStore } from "@shared/+store/auth.store.js";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import SubmitButton from "@shared/Components/SubmitButton/SubmitButton.vue";
import apiRequest from "@shared/utils/axios.js";
import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import { mapActions, mapState } from "pinia";

export default {
    components: {
        SubmitButton,
        DefaultAvatar,
    },
    computed: {
        ...mapState(useAuthStore, {
            userData: "userData",
            userPatientData: "userPatientData",
            userDoctorData: "userDoctorData",
        }),
        ...mapState(useLayoutStore, {
            isLoading: "isLoading",
        }),
    },
    setup() {
        return {
            v$: useVuelidate(),
        };
    },
    data() {
        return {
            confirmationForm: {
                phoneNumber: null,
                ssn: null, // TODO: SSN from registration not persistence in state
                name: null,
                gender: null,
                birthDate: null,
                email: null,
                doctorId: null,
                smfName: null,
            },
            patientConfirmationForm: {},
            doctorConfirmationForm: {},
            callingCode: "",
            userRole: "patient",
        };
    },
    validations() {
        return {
            confirmationForm: {
                phoneNumber: {
                    requiredIf: this.userData.userRole === "patient",
                },
                ssn: {
                    requiredIf: this.userData.userRole === "patient",
                },
                name: { required },
                gender: {
                    requiredIf: this.userData.userRole === "patient",
                },
                birthDate: {
                    requiredIf: this.userData.userRole === "patient",
                },
                email: {},
                doctorId: {
                    requiredIf: this.userData.userRole === "doctor",
                },
                smfName: {
                    requiredIf: this.userData.userRole === "doctor",
                },
            },
        };
    },
    methods: {
        ...mapActions(useLayoutStore, {
            updateLoadingState: "updateLoadingState",
            toggleErrorAlert: "toggleErrorAlert",
        }),
        updateConfirmationForm(data) {
            Object.assign(this.confirmationForm, data);
        },
        async confirm() {
            this.updateLoadingState(true);
            const confirmationFormValid = await this.v$.$validate();
            if (!confirmationFormValid) {
                 this.updateLoadingState(false);
                 return;
            }

            apiRequest
                .put(
                    `/api/v1/register/${
                        this.userData.userRole === "patient"
                            ? "patient"
                            : "doctor"
                    }/${this.confirmationForm.phoneNumber
                        .toString()
                        .replace(this.callingCode, "")}`,
                    {
                        ...this.confirmationForm,
                    }
                )
                .then((response) => {
                    if (this.userRole === "patient") {
                        window.location.href = "/patient/home";
                    } else {
                        window.location.href = "/doctor/home";
                    }
                })
                .catch((error) => {
                    if (error.response.data.message) {
                        this.toggleErrorAlert(error.response.data.message);
                    } else {
                        this.toggleErrorAlert(error);
                    }
                })
                .finally(() => {
                    this.updateLoadingState(false);
                });
        },
    },
    mounted() {
        this.callingCode = import.meta.env.VITE_APP_CALLING_CODE;
        this.userRole = this.userData.userRole;

        if (this.userData.phoneNumber === "") {
            // window.location.href = import.meta.env.VITE_APP_BASE_URL + "/auth/login";
        }

        this.updateConfirmationForm({
            name: this.userData.userFullName,
            phoneNumber: this.userData.phoneNumber,
        });

        if (this.userData.userRole === "patient") {
            let birthDate = "";
            if (!this.userPatientData.birthDate) {
                birthDate = new Date();
            } else {
                birthDate = new Date(this.userPatientData.birthDate)
                    .toISOString()
                    .split("T")[0];
            }

            this.updateConfirmationForm({
                ssn: this.userPatientData.ssn,
                gender: this.userPatientData.gender,
                birthDate: birthDate,
                email: this.userData.userEmail,
            });
        } else {
            this.updateConfirmationForm({
                doctorId: this.userDoctorData.doctorId,
                smfName: this.userDoctorData.smfName,
            });
        }
    },
};
</script>

<template>
    <div>
        <h1
            class="fs-2 lh-150 fw-bolder mt-4 mb-0"
            v-if="userData.userRole === 'patient'"
        >
            {{ $t("confirmation.title.patient") }}
        </h1>
        <h1
            class="fs-2 lh-150 fw-bolder mt-4 mb-0"
            v-if="userData.userRole === 'doctor'"
        >
            {{ $t("confirmation.title.doctor") }}
        </h1>
        <p
            class="fs-5 mt-4 text-red-200"
            v-if="userData.userRole === 'patient'"
        >
            {{ $t("confirmation.subtitle") }}
        </p>
        <form @submit.prevent="confirm" class="mt-4">
            <div v-if="userData.userRole === 'doctor'">
                <img
                    src="@resources/static/images/avatar-default.png"
                    alt="Foto Dokter"
                    width="60"
                    height="60"
                    class="rounded-pill border border-1 border-white"
                />
            </div>
            <div
                :class="{ error: v$.confirmationForm.doctorId.$errors.length }"
                class="mt-3"
                v-if="userData.userRole === 'doctor'"
            >
                <label for="email">{{ $t("confirmation.doctor_id") }}</label>
                <input
                    type="text"
                    name="doctorId"
                    id="smf_name"
                    class="form-control mt-2"
                    v-model="confirmationForm.doctorId"
                    readonly
                />
                <div
                    class="error mt-2 fs-6 fw-bold text-red-200"
                    v-for="error of v$.confirmationForm.doctorId.$errors"
                    :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>

            <div
                v-if="userData.userRole === 'patient'"
                :class="{
                    error: v$.confirmationForm.phoneNumber.$errors.length,
                }"
            >
                <label for="phone_number"
                    >{{ $t("confirmation.phone_number")
                    }}<span class="text-red-200 fw-semibold">*</span></label
                >
                <div class="input-group flex-nowrap mt-2">
                    <span class="input-group-text">{{ callingCode }}</span>
                    <input
                        type="tel"
                        name="phone_number"
                        id="phone_number"
                        placeholder="8123940183020"
                        class="form-control"
                        v-model="confirmationForm.phoneNumber"
                        readonly
                    />
                </div>
                <div
                    class="error mt-2 fs-6 fw-bold text-red-200"
                    v-for="error of v$.confirmationForm.phoneNumber.$errors"
                    :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>
            <div
                class="mt-3"
                v-if="userData.userRole === 'patient'"
                :class="{ error: v$.confirmationForm.ssn.$errors.length }"
            >
                <label for="ssn"
                    >{{ $t("confirmation.ssn")
                    }}<span class="text-red-200 fw-semibold">*</span></label
                >
                <input
                    type="number"
                    name="ssn"
                    id="ssn"
                    placeholder="3829380183984920"
                    class="form-control mt-2"
                    v-model="confirmationForm.ssn"
                    readonly
                />
                <div
                    class="error mt-2 fs-6 fw-bold text-red-200"
                    v-for="error of v$.confirmationForm.ssn.$errors"
                    :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>
            <div
                class="mt-3"
                :class="{ error: v$.confirmationForm.name.$errors.length }"
            >
                <label for="name" v-if="userData.userRole === 'patient'">
                    {{ $t("confirmation.full_name.patient")
                    }}<span class="text-red-200 fw-semibold">*</span>
                </label>
                <label for="name" v-if="userData.userRole === 'doctor'">
                    {{ $t("confirmation.full_name.doctor")
                    }}<span class="text-red-200 fw-semibold">*</span>
                </label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    placeholder="Muhammad Denis Adiswara"
                    class="form-control mt-2"
                    v-model="confirmationForm.name"
                    :readonly="userData.userRole === 'doctor'"
                />
                <div
                    class="error mt-2 fs-6 fw-bold text-red-200"
                    v-for="error of v$.confirmationForm.name.$errors"
                    :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>
            <div
                class="mt-3"
                v-if="userData.userRole === 'patient'"
                :class="{ error: v$.confirmationForm.gender.$errors.length }"
            >
                <label for="gender"
                    >{{ $t("confirmation.gender")
                    }}<span class="text-red-200 fw-semibold">*</span></label
                >
                <div class="d-flex col-gap-20 mt-2">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="gender"
                            :id="$t('confirmation.male')"
                            :value="$t('confirmation.male')"
                            v-model="confirmationForm.gender"
                        />
                        <label
                            class="form-check-label"
                            :for="$t('confirmation.male')"
                        >
                            {{ $t("confirmation.male") }}
                        </label>
                    </div>

                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="gender"
                            :id="$t('confirmation.female')"
                            :value="$t('confirmation.female')"
                            v-model="confirmationForm.gender"
                        />
                        <label
                            class="form-check-label"
                            :for="$t('confirmation.female')"
                        >
                            {{ $t("confirmation.female") }}
                        </label>
                    </div>
                </div>
                <div
                    class="error mt-2 fs-6 fw-bold text-red-200"
                    v-for="error of v$.confirmationForm.gender.$errors"
                    :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>
            <div
                class="mt-3"
                v-if="userData.userRole === 'patient'"
                :class="{ error: v$.confirmationForm.birthDate.$errors.length }"
            >
                <label for="birth_date"
                    >{{ $t("confirmation.birth_date") }}
                    <span class="text-red-200 fw-semibold">*</span></label
                >
                <input
                    type="date"
                    name="birth_date"
                    id="birth_date"
                    class="form-control mt-2"
                    v-model="confirmationForm.birthDate"
                />
                <div
                    class="error mt-2 fs-6 fw-bold text-red-200"
                    v-for="error of v$.confirmationForm.birthDate.$errors"
                    :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>
            <div
                class="mt-3"
                v-if="userData.userRole === 'patient'"
                :class="{ error: v$.confirmationForm.email.$errors.length }"
            >
                <label for="email">{{ $t("confirmation.email") }}</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="johndoe@example.com"
                    class="form-control mt-2"
                    v-model="confirmationForm.email"
                />
                <div
                    class="error mt-2 fs-6 fw-bold text-red-200"
                    v-for="error of v$.confirmationForm.email.$errors"
                    :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>
            <div
                class="mt-3"
                v-if="userData.userRole === 'doctor'"
                :class="{ error: v$.confirmationForm.smfName.$errors.length }"
            >
                <label for="email">{{ $t("confirmation.smf_name") }}</label>
                <input
                    type="text"
                    name="text"
                    id="smf_name"
                    class="form-control mt-2"
                    v-model="confirmationForm.smfName"
                    readonly
                />
                <div
                    class="error mt-2 fs-6 fw-bold text-red-200"
                    v-for="error of v$.confirmationForm.email.$errors"
                    :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>
            <div class="mt-3 d-flex flex-column">
                <SubmitButton
                    :text="$t('confirmation.save')"
                    className="btn-app-rounded"
                />
                <router-link
                    v-show="!isLoading"
                    :to="{ name: 'RegisterPage' }"
                    class="rounded-pill mt-3 border-white text-white px-3 py-2 text-center text-decoration-none border border-1"
                >
                    {{ $t("confirmation.cancel") }}
                </router-link>
            </div>
        </form>
    </div>
</template>
