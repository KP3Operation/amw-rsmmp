<script>
import * as bootstrap from "bootstrap";
import useVuelidate from "@vuelidate/core";
import {
    helpers,
    maxLength,
    minLength,
    required,
    requiredIf,
} from "@vuelidate/validators";
import SubmitButton from "@shared/Components/SubmitButton/SubmitButton.vue";
import { mapActions, mapState } from "pinia";
import { useAuthStore } from "@shared/+store/auth.store.js";
import axios from "axios";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import {onlyNumberInput} from "@shared/utils/helpers.js";

export default {
    components: { SubmitButton },
    computed: {
        ...mapState(useAuthStore, {
            userData: "userData",
        }),
        ...mapState(useLayoutStore, ["isLoading"]),
    },
    setup() {
        return {
            v$: useVuelidate(),
        };
    },
    data() {
        return {
            registerForm: {
                phoneNumber: "",
                role: "1",
                doctorId: "",
                name: "",
                ssn: "",
            },
            callingCode: import.meta.env.VITE_APP_CALLING_CODE,
            modalState: {
                alreadyRegisteredModal: null,
            },
        };
    },
    validations() {
        return {
            registerForm: {
                phoneNumber: {
                    required: helpers.withMessage(
                        "No Hp tidak boleh kosong",
                        required
                    ),
                    minLength: helpers.withMessage(
                        "No Hp kurang dari 9 digit",
                        minLength(9)
                    ),
                    maxLength: helpers.withMessage(
                        "No Hp lebih dari 13 digit",
                        maxLength(13)
                    ),
                },
                role: { required },
                doctorId: {
                    requiredIf: helpers.withMessage(
                        "ID Dokter tidak boleh kosong",
                        requiredIf(this.registerForm.role === "2")
                    ),
                },
                name: {
                    requiredIf: helpers.withMessage(
                        "Nama Lengkap tidak boleh kosong",
                        requiredIf(this.registerForm.role === "1")
                    ),
                },
                ssn: {
                    requiredIf: helpers.withMessage(
                        "NIK tidak boleh kosong",
                        requiredIf(this.registerForm.role === "1")
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
            },
        };
    },
    methods: {
        onlyNumberInput,
        useLayoutStore,
        ...mapActions(useAuthStore, {
            updateOtpData: "updateOtpData",
            updateUserData: "updateUserData",
            updateUserPatientData: "updateUserPatientData",
            updateUserDoctorData: "updateUserDoctorData",
            updateRegistrationFlag: "updateRegistrationFlag",
        }),
        ...mapActions(useLayoutStore, {
            toggleErrorAlert: "toggleErrorAlert",
            updateLoadingState: "updateLoadingState",
        }),
        async register() {
            const formValid = await this.v$.$validate();
            if (!formValid) {
                return;
            }
            this.updateLoadingState(true);
            axios
                .post(
                    this.registerForm.role === "1"
                        ? "/api/v1/register/patient"
                        : "/api/v1/register/doctor",
                    this.registerForm
                )
                .then((response) => {
                    this.updateRegistrationFlag(true);
                    this.updateOtpData({
                        otpCreatedAt: response.data.otpCreatedAt,
                        otpUpdatedAt: response.data.otpUpdatedAt,
                        otpTimeout: response.data.otpTimeout,
                    });

                    this.updateUserData({
                        userId: response.data.id,
                        userFullName: response.data.name,
                        phoneNumber: this.registerForm.phoneNumber,
                        userRole:
                            this.registerForm.role === "1"
                                ? "patient"
                                : "doctor",
                    });

                    if (this.registerForm.role === "1") {
                        // Patient
                        this.updateUserPatientData({
                            ssn: this.registerForm.ssn,
                        });
                    } else {
                        // Doctor
                        this.updateUserDoctorData({
                            doctorId: response.data.doctorId,
                            smfName: response.data.smfName,
                        });
                    }

                    this.$router.push({ name: "VerificationPage" });
                })
                .catch((error) => {
                    if (error.response.status === 409) {
                        this.modalState.alreadyRegisteredModal.show();
                    } else {
                        if (error.response.data.message) {
                            this.toggleErrorAlert(error.response.data.message);
                        } else {
                            this.toggleErrorAlert(error);
                        }
                    }
                })
                .finally(() => {
                    this.updateLoadingState(false);
                });
        },
        navigateToLogin() {
            this.updateLoadingState(true);
            axios
                .post("/api/v1/login", {
                    phoneNumber: this.registerForm.phoneNumber,
                })
                .then((response) => {
                    this.updateOtpData(response.data);
                    this.modalState.alreadyRegisteredModal.hide();
                    this.updateUserData({
                        phoneNumber: this.registerForm.phoneNumber,
                    });
                    this.$router.push({ name: "VerificationPage" });
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
        this.modalState.alreadyRegisteredModal = new bootstrap.Modal(
            "#modal-register",
            {}
        );
        this.registerForm.phoneNumber = this.userData.phoneNumber;
    },
};
</script>
<template>
    <h1 class="fs-1 lh-150 fw-bolder mt-4 mb-0">{{ $t("welcome_message") }}</h1>
    <p class="fs-3 fw-bold mt-4">{{ $t("register.title") }}</p>
    <form id="register-form" class="mt-4" @submit.prevent="register">
        <div :class="{ error: v$.registerForm.phoneNumber.$errors.length }">
            <label for="phone_number">{{ $t("register.phone_number") }}</label>
            <div class="input-group flex-nowrap mt-2">
                <span class="input-group-text">{{ callingCode }}</span>
                <input
                    type="number"
                    name="phone_number"
                    id="phone_number"
                    @input="v$.registerForm.phoneNumber.$touch()"
                    placeholder="8123940183020"
                    class="form-control"
                    v-model="registerForm.phoneNumber"
                    @keypress="onlyNumberInput($event)"
                />
            </div>
            <div
                class="error mt-2 fs-6 fw-bold text-app-warning"
                v-for="error of v$.registerForm.phoneNumber.$errors"
                :key="error.$uid"
            >
                {{ error.$message }}
            </div>
        </div>
        <div
            class="mt-3"
            :class="{ error: v$.registerForm.role.$errors.length }"
        >
            <label for="role">{{ $t("register.register_as") }}</label>
            <select
                name="role"
                id="role"
                class="form-select mt-2"
                v-model="registerForm.role"
            >
                <option value="1">{{ $t("register.patient") }}</option>
                <option value="2">{{ $t("register.doctor") }}</option>
            </select>
            <div
                class="error mt-2 fs-6 fw-bold text-app-warning"
                v-for="error of v$.registerForm.role.$errors"
                :key="error.$uid"
            >
                {{ error.$message }}
            </div>
        </div>
        <div
            class="mt-3"
            :class="{ error: v$.registerForm.ssn.$errors.length }"
            v-if="registerForm.role === '1'"
        >
            <label for="ssn">{{ $t("register.ssn") }}</label>
            <input
                type="number"
                name="ssn"
                id="ssn"
                placeholder="3829380183984920"
                class="form-control mt-2"
                @input="v$.registerForm.ssn.$touch()"
                v-model="registerForm.ssn"
                @keypress="onlyNumberInput($event)"
            />
            <div
                class="error mt-2 fs-6 fw-bold text-app-warning"
                v-for="error of v$.registerForm.ssn.$errors"
                :key="error.$uid"
            >
                {{ error.$message }}
            </div>
        </div>
        <div
            class="mt-3"
            :class="{ error: v$.registerForm.name.$errors.length }"
            v-if="registerForm.role === '1'"
        >
            <label for="name">{{ $t("register.full_name") }}</label>
            <input
                type="text"
                name="name"
                id="name"
                placeholder="Muhammad Denis Adiswara"
                class="form-control mt-2"
                @input="v$.registerForm.name.$touch()"
                v-model="registerForm.name"
            />
            <div
                class="error mt-2 fs-6 fw-bold text-app-warning"
                v-for="error of v$.registerForm.name.$errors"
                :key="error.$uid"
            >
                {{ error.$message }}
            </div>
        </div>
        <div
            class="mt-3"
            :class="{ error: v$.registerForm.doctorId.$errors.length }"
            v-if="registerForm.role === '2'"
        >
            <label for="doctor_id">{{ $t("register.doctor_id") }}</label>
            <input
                type="text"
                name="doctor_id"
                id="doctor_id"
                placeholder="3829380183984920"
                class="form-control mt-2"
                @input="v$.registerForm.doctorId.$touch()"
                v-model="registerForm.doctorId"
            />
            <div
                class="error mt-2 fs-6 fw-bold text-app-warning"
                v-for="error of v$.registerForm.doctorId.$errors"
                :key="error.$uid"
            >
                {{ error.$message }}
            </div>
        </div>
        <div class="mt-3 d-flex flex-column">
            <SubmitButton
                :text="$t('register.register')"
                className="btn-app-rounded"
            />
            <router-link
                to="/login"
                class="rounded-pill mt-3 border-white text-white px-3 py-2 text-center text-decoration-none border border-1"
                v-show="!isLoading"
            >
                {{ $t("register.cancel") }}
            </router-link>
        </div>
    </form>
    <div class="mt-4 text-center" v-show="!isLoading">
        <p>
            {{ $t("register.already_have_an_account") }}
            <router-link
                to="/login"
                class="fw-bold text-white text-decoration-none"
                >{{ $t("register.login") }}</router-link
            >
        </p>
    </div>
    <div
        class="modal"
        id="modal-register"
        aria-labelledby="Register Modal"
        data-bs-backdrop="static"
        aria-hidden="true"
        tabindex="-1"
    >
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div class="d-flex align-items-center col-gap-8">
                        <i
                            class="bi bi-info-circle-fill icon-blue-500 fs-3"
                        ></i>
                        <h5 class="modal-title">
                            {{ $t("register.phone_number_already_registered") }}
                        </h5>
                    </div>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                        form="#"
                    >
                        <i class="bi bi-x fs-2 icon-black"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        {{
                            $t("register.phone_number_already_registered_desc")
                        }}
                    </p>
                </div>
                <div class="modal-footer flex-nowrap">
                    <button
                        type="button"
                        class="w-50 btn btn-link"
                        :class="isLoading ? 'disabled' : ''"
                        data-bs-dismiss="modal"
                        form="#"
                    >
                        {{ $t("register.cancel") }}
                    </button>
                    <button
                        type="button"
                        @click="navigateToLogin"
                        class="w-50 btn-masuk btn btn-blue"
                        :class="isLoading ? 'disabled' : ''"
                        form="#"
                    >
                        {{ $t("register.login") }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
