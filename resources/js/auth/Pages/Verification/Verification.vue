<script>
import { useAuthStore } from "@shared/+store/auth.store.js";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import SubmitButton from "@shared/Components/SubmitButton/SubmitButton.vue";
import axios from "@shared/utils/axios.js";
import { getSecondsLeft } from "@shared/utils/helpers.js";
import useVuelidate from "@vuelidate/core";
import { helpers, required } from "@vuelidate/validators";
import { mapActions, mapState } from "pinia";

export default {
    components: { SubmitButton },
    computed: {
        ...mapState(useAuthStore, {
            isRegistration: "isRegistration",
        }),
        ...mapState(useLayoutStore, {
            isLoading: "isLoading",
        }),
        ...mapState(useAuthStore, {
            otpData: "otpData",
            userData: "userData",
        }),
    },
    setup() {
        return {
            v$: useVuelidate(),
        };
    },
    data() {
        return {
            otpForm: {
                code: 12345, // TODO: Need to remove
            },
            isCountDownRunning: true,
            lastCodeUpdateDate: new Date(),
            futureDateTime: 0,
            paddedResendOtpTimeout: "",
            resendOtpTimeout: 0,
        };
    },
    validations() {
        return {
            otpForm: {
                code: {
                    required: helpers.withMessage(
                        "Kode OTP tidak boleh kosong",
                        required
                    ),
                },
            },
        };
    },
    methods: {
        ...mapActions(useLayoutStore, {
            updateLoadingState: "updateLoadingState",
            toggleErrorAlert: "toggleErrorAlert",
        }),
        ...mapActions(useAuthStore, {
            updateOtpData: "updateOtpData",
            updateUserData: "updateUserData",
            updateUserPatientData: "updateUserPatientData",
            updateUserDoctorData: "updateUserDoctorData",
        }),
        async otpVerification() {
            this.updateLoadingState(true);
            const otpFormValid = await this.v$.$validate();
            if (!otpFormValid) {
                this.updateLoadingState(false);
                return;
            }

            axios
                .post("/api/v1/verification", { code: this.otpForm.code })
                .then((response) => {
                    if (this.isRegistration) {
                        // Registration
                        this.updateUserData({
                            userId: response.data.user.id,
                            userFullName: response.data.user.name,
                        });

                        if (this.userData.userRole === "patient") {
                            this.updateUserData({
                                userEmail: response.data.userPatient.email,
                            });

                            this.updateUserPatientData({
                                ssn: response.data.userPatient.ssn,
                                birthDate: response.data.userPatient.birthDate,
                                gender:
                                    response.data.userPatient.gender === "F"
                                        ? "Perempuan"
                                        : "Laki-Laki",
                                medicalNo: response.data.userPatient.medicalNo,
                                patientId: response.data.userPatient.patientId,
                            });
                        } else {
                            this.updateUserDoctorData({
                                doctorId: response.data.userDoctor.doctorId,
                                smfName: response.data.userDoctor.smfName,
                            });
                        }
                        this.$router.push({ name: "ConfirmationPage" });
                    } else {
                        // Normal Login
                        if (this.userData.userRole === "patient") {
                            window.location.href = "/patient/home";
                        } else {
                            window.location.href = "/doctor/home";
                        }
                    }
                })
                .catch((error) => {
                    console.log(error);
                    if (error.response.status === 404) {
                        this.toggleErrorAlert(error.response.data.message);
                    } else {
                        this.toggleErrorAlert(error);
                    }
                })
                .finally(() => {
                    this.updateLoadingState(false);
                });
        },
        resendOtpCode() {
            this.updateLoadingState(true);
            if (this.userData.phoneNumber === "") {
                this.toggleErrorAlert(
                    "Terjadi kesalahan. Mohon untuk me-refresh halaman browser",
                    true
                );
                this.updateLoadingState(false);
                return;
            }

            if (!this.isCountDownRunning) {
                axios
                    .post("/api/v1/login", {
                        phoneNumber: this.userData.phoneNumber
                            .toString()
                            .replace(import.meta.env.VITE_APP_CALLING_CODE, ""),
                    })
                    .then((response) => {
                        this.updateOtpData({
                            otpCreatedAt: response.data.otpCreatedAt,
                            otpUpdatedAt: response.data.otpUpdatedAt,
                            otpTimeout: response.data.otpTimeout,
                        });

                        this.updateUserData({
                            phoneNumber: response.data.phoneNumber,
                        });

                        this.lastCodeUpdateDate = new Date(
                            this.otpData.otpUpdatedAt
                        );
                        this.futureDateTime =
                            this.lastCodeUpdateDate.getTime() +
                            this.otpData.otpTimeout;
                        this.paddedResendOtpTimeout = (
                            this.otpData.otpTimeout / 1000
                        ).toString();

                        this.countDown();
                        this.isCountDownRunning = true;
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
            }
        },
        countDown() {
            const timerId = setInterval(() => {
                this.resendOtpTimeout = getSecondsLeft(
                    new Date(),
                    new Date(this.futureDateTime)
                );
                if (parseInt(this.resendOtpTimeout) <= 0) {
                    clearInterval(timerId);
                    this.isCountDownRunning = false;
                } else {
                    this.resendOtpTimeout--;
                    this.paddedResendOtpTimeout = this.resendOtpTimeout
                        .toString()
                        .padStart(2, "0");
                }
            }, 1000);
        },
    },
    mounted() {
        if (
            this.userData.phoneNumber.toString().trim().length === 0 ||
            this.otpData.otpTimeout === 0
        ) {
            this.$router.push({ name: "LoginPage" });
        }

        this.lastCodeUpdateDate = new Date(this.otpData.otpUpdatedAt);
        this.futureDateTime =
            this.lastCodeUpdateDate.getTime() + this.otpData.otpTimeout;
        this.paddedResendOtpTimeout = (
            this.otpData.otpTimeout / 1000
        ).toString();
        this.countDown();
    },
};
</script>

<template>
    <div>
        <h1 class="fs-2 fw-bold mt-5">{{ $t("verification.title") }}</h1>
        <p>{{ $t("verification.subtitle") }}</p>
        <form
            id="verification-form"
            @submit.prevent="otpVerification"
            class="mt-5"
        >
            <div :class="{ error: v$.otpForm.code.$errors.length }">
                <label for="kode-otp">{{ $t("verification.otp_code") }}</label>
                <input
                    type="number"
                    name="code"
                    id="kode-otp"
                    :placeholder="$t('verification.enter_otp_code')"
                    class="form-control mt-2"
                    v-model="otpForm.code"
                />
                <div
                    class="error mt-2 fs-6 fw-bold text-red-200"
                    v-for="error of v$.otpForm.code.$errors"
                    :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>

            <div class="d-flex flex-column mt-3">
                <SubmitButton
                    :text="$t('verification.verify')"
                    className="btn-blue-700-rounded"
                />
                <router-link
                    :to="{ name: 'LoginPage' }"
                    class="btn btn-outline-white-rounded mt-3"
                    v-show="!isLoading"
                >
                    {{ $t("verification.cancel") }}
                </router-link>
            </div>
        </form>
        <p class="mt-4 text-center" v-show="!isLoading">
            {{ $t("verification.does_not_get_code") }}
            <a
                href="javascript:void(0);"
                @click="resendOtpCode"
                class="kirim-otp text-white fw-bold text-decoration-none"
                v-if="!isCountDownRunning"
            >
                {{ $t("verification.resend_code") }}</a
            >
            <span
                class="kirim-otp text-white fw-bold text-decoration-none"
                v-if="isCountDownRunning"
            >
                OO:{{ paddedResendOtpTimeout }}</span
            >
        </p>
    </div>
</template>
