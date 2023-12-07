<script>
import { useAuthStore } from "@shared/+store/auth.store.js";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import SubmitButton from "@shared/Components/SubmitButton/SubmitButton.vue";
import axios from "@shared/utils/axios.js";
import useVuelidate from "@vuelidate/core";
import { helpers, maxLength, minLength, required } from "@vuelidate/validators";
import * as bootstrap from "bootstrap";
import { mapActions, mapState } from "pinia";
import {onlyNumberInput} from "@shared/utils/helpers.js";

export default {
    name: "LoginPage",
    components: { SubmitButton },
    setup() {
        return {
            v$: useVuelidate(),
        };
    },
    computed: {
        ...mapState(useLayoutStore, {
            isLoading: "isLoading",
        }),
        ...mapState(useAuthStore, {
            userData: "userData",
        }),
    },
    data() {
        return {
            loginForm: {
                phoneNumber: null,
            },
            callingCode: "",
            modalState: {
                notRegisteredModal: null,
            },
        };
    },
    watch: { },
    validations() {
        return {
            loginForm: {
                phoneNumber: {
                    required: helpers.withMessage(
                        "No Hp tidak boleh kosong",
                        required
                    ),
                    minLength: helpers.withMessage(
                        "No Hp kurang dari 10 digit",
                        minLength(9)
                    ),
                    maxLength: helpers.withMessage(
                        "No Hp lebih dari 13 digit",
                        maxLength(13)
                    )
                },
            },
        };
    },
    methods: {
        onlyNumberInput,
        ...mapActions(useLayoutStore, {
            toggleErrorAlert: "toggleErrorAlert",
            updateLoadingState: "updateLoadingState",
        }),
        ...mapActions(useAuthStore, {
            updateUserData: "updateUserData",
            updateOtpData: "updateOtpData",
        }),
        async login() {
            this.updateLoadingState(true);
            const formValid = await this.v$.$validate();
            if (!formValid) {
                this.updateLoadingState(false);
                return;
            }

            axios
                .post("/api/v1/login", this.loginForm)
                .then((response) => {
                    this.updateOtpData({
                        otpCreatedAt: response.data.otpCreatedAt,
                        otpUpdatedAt: response.data.otpUpdatedAt,
                        otpTimeout: response.data.otpTimeout,
                    });

                    this.updateUserData({
                        phoneNumber: this.loginForm.phoneNumber,
                    });

                    this.$router.push({ name: "VerificationPage" });
                })
                .catch((error) => {
                    if (error.response.status === 404) {
                        this.modalState.notRegisteredModal.show();
                    } else {
                        // console.log(error);
                        this.toggleErrorAlert(`${error.response.data.message}`);
                    }
                })
                .finally(() => {
                    this.updateLoadingState(false);
                });
        },
        navigateToRegister() {
            this.modalState.notRegisteredModal.hide();
            this.updateUserData({ phoneNumber: this.loginForm.phoneNumber });
            this.$router.push({ name: "RegisterPage" });
        },
    },
    mounted() {
        this.callingCode = import.meta.env.VITE_APP_CALLING_CODE;
        this.loginForm.phoneNumber = this.userData.phoneNumber;
        this.modalState.notRegisteredModal = new bootstrap.Modal(
            "#modal-register",
            {}
        );
    },
};
</script>

<template>
    <div>
        <h1 class="fs-1 mt-6 fw-bold">{{ $t("welcome_message") }}</h1>
        <h2 class="mt-6 fs-3 fw-bold">{{ $t("login.login_to_account") }}</h2>
        <form id="login-form" class="mt-6" @submit.prevent="login">
            <div :class="{ error: v$.loginForm.phoneNumber.$errors.length }">
                <label for="no-hp">{{ $t("login.phone_number") }}</label>
                <div class="input-group flex-nowrap mt-2">
                    <span class="input-group-text">{{ callingCode }}</span>
                    <input
                        type="number"
                        name="phone_number"
                        id="no-hp"
                        placeholder="8123940183020"
                        class="form-control"
                        @input="v$.loginForm.phoneNumber.$touch()"
                        v-model.number="loginForm.phoneNumber"
                        @keypress="onlyNumberInput($event)"
                    />
                </div>
                <div
                    class="error mt-2 fs-6 fw-bold text-app-warning"
                    v-for="error of v$.loginForm.phoneNumber.$errors"
                    :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>
            <div class="mt-4 d-flex flex-column">
                <!-- <SubmitButton :text="$t('login.login')" className="btn-blue-700-rounded" /> -->
                <SubmitButton
                    :text="$t('login.login')"
                    className="btn-app-rounded"
                />
            </div>
        </form>
        <p class="mt-4 text-center">
            {{ $t("login.does_not_have_account") }}
            <router-link
                :to="{ name: 'RegisterPage' }"
                class="fw-bold text-decoration-none text-white"
            >
                {{ $t("login.register") }}
            </router-link>
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
                            {{ $t("login.phone_number_not_registered") }}
                        </h5>
                    </div>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    >
                        <i class="bi bi-x fs-2 icon-black"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ $t("login.phone_number_not_registered_desc") }}</p>
                </div>

                <div class="modal-footer flex-nowrap">
                    <button
                        type="button"
                        class="w-50 btn btn-link"
                        data-bs-dismiss="modal"
                    >
                        {{ $t("register.cancel") }}
                    </button>
                    <button
                        type="button"
                        @click="navigateToRegister"
                        class="w-50 btn-masuk btn btn-blue"
                    >
                        {{ $t("login.yes") }},
                        {{ $t("login.register") }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
