<script>
import { useAppointmentStore } from "@patient/+store/appointment.store.js";
import { useDoctorScheduleStore } from "@patient/+store/doctor-schedule.store.js";
import { useFamilyStore } from "@patient/+store/family.store.js";
import { useGuarantorStore } from "@patient/+store/guarantor.store.js";
import router from "@patient/router.js";
import { getCurrentDate } from "@resources/js/shared/utils/helpers";
import { useAuthStore } from "@shared/+store/auth.store.js";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import Header from "@shared/Components/Header/Header.vue";
import SubmitButton from "@shared/Components/SubmitButton/SubmitButton.vue";
import apiRequest from "@shared/utils/axios.js";
import useVuelidate from "@vuelidate/core";
import { helpers, required } from "@vuelidate/validators";
import { storeToRefs } from "pinia";
import { onMounted, onUnmounted, reactive, ref, watch } from "vue";
import { useRoute } from "vue-router";

export default {
    components: { Header, SubmitButton },
    setup() {
        const doctorScheduleStore = useDoctorScheduleStore();
        const { schedules, selectedDate } = storeToRefs(doctorScheduleStore);
        const layoutStore = useLayoutStore();
        const { isLoading } = storeToRefs(layoutStore);
        const familyStore = useFamilyStore();
        const guarantorStore = useGuarantorStore();
        const { families } = storeToRefs(familyStore);
        const { guarantors } = storeToRefs(guarantorStore);
        const authStore = useAuthStore();
        const { userData, userPatientData } = storeToRefs(authStore);
        const appointmentStore = useAppointmentStore();

        const {
            selectedServiceUnitId,
            selectedGuarantorId,
            selectedParamedicId,
            selectedStartDate,
            selectedEndDate,
        } = storeToRefs(appointmentStore);

        const form = reactive({
            patient_name: "",
            patient_id: null,
            birth_date: null,
            gender: null,
            appointment_date: selectedDate,
            service_unit_id: selectedServiceUnitId.value,
            guarantor_id: selectedGuarantorId.value,
            paramedic_id: selectedParamedicId.value,
            is_family_member: false,
            family_id: "",
        });
        const rules = {
            patient_name: {
                required: helpers.withMessage(
                    "Nama pasien tidak boleh kosong",
                    required
                ),
            },
            patient_id: {},
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
            appointment_date: {
                required: helpers.withMessage(
                    "Tanggal konsultasi tidak boleh kosong",
                    required
                ),
            },
            service_unit_id: {
                required: helpers.withMessage(
                    "Unit tidak boleh kosong",
                    required
                ),
            },
            guarantor_id: {
                required: helpers.withMessage(
                    "Penjamin tidak boleh kosong",
                    required
                ),
            },
            paramedic_id: {
                required: helpers.withMessage(
                    "Dokter tidak boleh kosong",
                    required
                ),
            },
            family_id: {},
            guarantor_id: {},
        };
        const v$ = useVuelidate(rules, form);
        const paramedics = ref([]);
        const serviceUnits = ref([]);
        // const guarantors = ref([]);
        const route = useRoute();
        const isReadonly = ref(false);
        const isFromDoctorSchedulePage = ref(false);
        const tempPatientName = ref("");
        const appointmentDateChanged = ref(true);
        const serviceUnitChanged = ref(true);
        const selectedFamilyId = ref("");

        const storeAppointment = async () => {
            layoutStore.updateLoadingState(true);

            if (
                form.patient_name === null ||
                (form.patient_name === form.patient_id &&
                    tempPatientName.value !== "") ||
                form.patient_name === "nopatientid" ||
                form.patient_name === "familynopatientid"
            ) {
                form.patient_name = tempPatientName.value;
            }

            const formValid = await v$.value.$validate();
            if (!formValid) {
                layoutStore.updateLoadingState(false);
                return;
            }

            if (
                form.is_family_member &&
                (form.patient_id === null || form.patient_id == "")
            ) {
                form.family_id = selectedFamilyId.value;
                form.guarantor_id = selectedGuarantorId.value;
            }

            apiRequest
                .post("/api/v1/patient/appointments/store", form)
                .then((response) => {
                    layoutStore.toggleSuccessAlert(
                        "Jadwal Konsultasi Berhasil Disimpan"
                    );
                    selectedStartDate.value = null;
                    selectedEndDate.value = null;
                    router.push({ name: "AppointmentPage" });
                })
                .catch((error) => {
                    if (error.response.status === 401) {
                        window.location.href = "/auth/login";
                    }

                    if (error.response) {
                        layoutStore.toggleErrorAlert(
                            `Jadwal Konsultasi Gagal Disimpan. Pesan Error : ${error.response.data.message}`
                        );
                        router.push({ name: "AppointmentPage" });
                        if (error.response.status !== 422) {
                            // NOTE: We can force the user to redirect
                            // to appointments page
                            // router.push({ name: 'AppointmentPage' });
                        }
                    } else {
                        layoutStore.toggleErrorAlert(`${error}`);
                    }
                })
                .finally(() => {
                    layoutStore.updateLoadingState(false);
                    selectedFamilyId.value = "";
                });
        };

        const fetchFamily = () => {
            apiRequest
                .get(`/api/v1/patient/family`)
                .then((response) => {
                    const data = response.data;
                    familyStore.updateFamilies(data.families);
                })
                .catch((error) => {
                    layoutStore.toggleErrorAlert(
                        `${error.response.data.message}`
                    );
                });
        };

        const fetchGuarantor = () => {
            apiRequest
                .get(`/api/v1/patient/guarantors`)
                .then((response) => {
                    const data = response.data.guarantor.data;

                    guarantorStore.updateGuarantors(data);
                    // guarantors.value = data;
                })
                .catch((error) => {
                    layoutStore.toggleErrorAlert(
                        `${error.response.data.message}`
                    );
                });
        };

        const fetchDoctorSchedules = () => {
            apiRequest
                .get(`/api/v1/patient/doctor/schedules/format`, {
                    params: {
                        date: `${selectedDate.value}`,
                    },
                })
                .then((response) => {
                    const data = response.data;
                    serviceUnits.value = data.schedules;
                    if (isFromDoctorSchedulePage.value) {
                        const serviceUnit = serviceUnits.value.find(
                            (unit) =>
                                unit.serviceUnitID ===
                                selectedServiceUnitId.value
                        );
                        if (serviceUnit) {
                            paramedics.value = serviceUnit.doctors;
                        }
                    }
                })
                .catch((error) => {
                    layoutStore.toggleErrorAlert(
                        `${error.response.data.message}`
                    );
                })
                .finally(() => {});
        };

        watch(
            () => form.patient_name,
            (newValue, oldValue) => {
                if (newValue === "") {
                    form.patient_id = "";
                    form.birth_date = "";
                    form.gender = "";
                    tempPatientName.value = "";
                } else if (
                    newValue === userPatientData.value.patientId ||
                    newValue === "nopatientid"
                ) {
                    form.patient_id = userPatientData.value.patientId;
                    form.birth_date = userPatientData.value.birthDate;
                    form.gender = userPatientData.value.gender;
                    tempPatientName.value = userData.value.userFullName;
                    form.is_family_member = false;
                } else {
                    let foundFamily = families.value.find(
                        (family) =>
                            family.patient_id === newValue ||
                            family.name === newValue
                    );

                    if (foundFamily) {
                        form.patient_id = foundFamily.patient_id;
                        form.patient_name = newValue;
                        form.birth_date = foundFamily.birth_date;
                        form.gender = foundFamily.gender;
                        form.is_family_member = true;
                        tempPatientName.value = foundFamily.name;
                        selectedFamilyId.value = foundFamily.id;
                    } else {
                        // Try to find family by name
                        // foundFamily = families.value.find(
                        //     (family) => family.name === newValue
                        // );

                        form.patient_id = "";
                        form.patient_name = newValue;
                        // form.birth_date = foundFamily.birth_date;
                        // form.gender = foundFamily.gender;
                        // form.is_family_member = true;
                        // tempPatientName.value = foundFamily.name;
                        // selectedFamilyId.value = foundFamily.id;
                    }
                }
            }
        );

        watch(
            () => form.appointment_date,
            (newValue, oldValue) => {
                if (newValue) {
                    serviceUnits.value = [];
                    paramedics.value = [];
                    form.service_unit_id = "";
                    form.guarantor_id = "";
                    form.paramedic_id = "";
                    doctorScheduleStore.updateSelectedDate(newValue);
                    fetchDoctorSchedules();
                }
            }
        );

        watch(
            () => form.service_unit_id,
            (newValue, oldValue) => {
                if (newValue && !isFromDoctorSchedulePage.value) {
                    appointmentStore.updateSelectedServiceUnitId(newValue);
                    const serviceUnit = serviceUnits.value.find(
                        (unit) => unit.serviceUnitID === newValue
                    );
                    if (serviceUnit) {
                        paramedics.value = serviceUnit.doctors;
                    }
                }

                if (newValue && isFromDoctorSchedulePage.value) {
                    appointmentStore.updateSelectedServiceUnitId(newValue);
                    fetchDoctorSchedules();
                }
            }
        );

        watch(
            () => form.paramedic_id,
            (newValue, oldValue) => {
                if (newValue) {
                    appointmentStore.updateSelectedParamedicId(newValue);
                }
            }
        );

        watch(
            () => form.guarantor_id,
            (newValue, oldValue) => {
                if (newValue) {
                    appointmentStore.updateSelectedGuarantorId(newValue);
                }
            }
        );

        onMounted(() => {
            isFromDoctorSchedulePage.value = false;
            form.patient_name = userPatientData.value.patientId
                ? userPatientData.value.patientId
                : "nopatientid";
            fetchFamily();
            fetchGuarantor();

            if (
                selectedParamedicId.value !== "" &&
                selectedServiceUnitId.value !== ""
            ) {
                isFromDoctorSchedulePage.value = true;
                fetchDoctorSchedules();
            } else {
                fetchDoctorSchedules();
                isFromDoctorSchedulePage.value = false;
                appointmentStore.updateSelectedDate(getCurrentDate());
                appointmentStore.updateSelectedParamedicId("");
                appointmentStore.updateSelectedServiceUnitId("");
                form.service_unit_id = "";
                form.paramedic_id = "";
                form.guarantor_id = "";
                form.appointment_date = getCurrentDate();
            }
        });

        onUnmounted(() => {
            isFromDoctorSchedulePage.value = false;
            appointmentStore.updateSelectedServiceUnitId("");
            appointmentStore.updateSelectedGuarantorId("");
            appointmentStore.updateSelectedParamedicId("");
            appointmentStore.updateSelectedStartDate(getCurrentDate());
        });

        return {
            v$,
            schedules,
            selectedDate,
            isLoading,
            families,
            userData,
            userPatientData,
            selectedServiceUnitId,
            selectedGuarantorId,
            selectedParamedicId,
            form,
            paramedics,
            serviceUnits,
            route,
            isReadonly,
            isFromDoctorSchedulePage,
            tempPatientName,
            appointmentDateChanged,
            serviceUnitChanged,
            storeAppointment,
            fetchFamily,
            fetchDoctorSchedules,
            fetchGuarantor,
            guarantors,
        };
    },
};
</script>

<template>
    <Header
        :title="$t('appointment.create_appointment.title')"
        :with-back-url="true"
        page-name="AppointmentPage"
    ></Header>
    <div class="px-4 pt-7 pb-4">
        <p class="fs-5 text-red-500">
            {{ $t("appointment.create_appointment.required_fields") }}
        </p>
        <form
            @submit.prevent="storeAppointment"
            class="d-flex flex-column rows-gap-16 mt-3"
        >
            <p class="fs-3 fw-bold">
                {{ $t("appointment.create_appointment.patient") }}
            </p>
            <div :class="{ error: v$.patient_name.$errors.length }">
                <label for="nama"
                    >{{ $t("appointment.create_appointment.patient_name") }}
                    <span class="text-red-500 fw-semibold">*</span></label
                >
                <select
                    name="nama"
                    id="nama"
                    class="form-select mt-2"
                    v-model="form.patient_name"
                    @change="v$.patient_name.$touch()"
                >
                    <option value="">
                        {{ $t("appointment.create_appointment.member") }}
                    </option>
                    <option
                        :value="
                            userPatientData.patientId
                                ? userPatientData.patientId
                                : 'nopatientid'
                        "
                    >
                        {{ userData.userFullName }}
                    </option>
                    <option
                        v-for="family in families"
                        :value="
                            family.patient_id ? family.patient_id : family.name
                        "
                    >
                        {{ family.name }}
                    </option>
                </select>
                <div
                    class="error mt-2 fs-6 fw-bold text-red-200"
                    v-for="error of v$.patient_name.$errors"
                    :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>
            <div :class="{ error: v$.patient_id.$errors.length }">
                <label for="id-pasien">{{
                    $t("appointment.create_appointment.patient_id")
                }}</label>
                <input
                    type="text"
                    class="form-control mt-2"
                    v-model="form.patient_id"
                    id="id-pasien"
                    @input="v$.patient_id.$touch()"
                    readonly
                />
                <div
                    class="error mt-2 fs-6 fw-bold text-red-200"
                    v-for="error of v$.patient_id.$errors"
                    :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>
            <div :class="{ error: v$.birth_date.$errors.length }">
                <label for="dob"
                    >{{ $t("appointment.create_appointment.birth_date") }}
                    <span class="text-red-500 fw-semibold">*</span></label
                >
                <input
                    type="date"
                    v-model="form.birth_date"
                    name="dob"
                    id="dob"
                    class="form-control mt-2"
                    @input="v$.birth_date.$touch()"
                />
                <div
                    class="error mt-2 fs-6 fw-bold text-red-200"
                    v-for="error of v$.birth_date.$errors"
                    :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>
            <div :class="{ error: v$.gender.$errors.length }">
                <label for="gender"
                    >{{ $t("appointment.create_appointment.gender") }}
                    <span class="text-red-500 fw-semibold">*</span></label
                >
                <div class="d-flex col-gap-20 mt-2">
                    <div class="form-check">
                        <input
                            v-model="form.gender"
                            class="form-check-input"
                            type="radio"
                            name="gender"
                            id="Laki-Laki"
                            value="Laki-Laki"
                        />
                        <label class="form-check-label" for="Laki-Laki">
                            {{ $t("appointment.create_appointment.male") }}
                        </label>
                    </div>
                    <div class="form-check">
                        <input
                            v-model="form.gender"
                            class="form-check-input"
                            type="radio"
                            name="gender"
                            value="Perempuan"
                            id="Perempuan"
                        />
                        <label class="form-check-label" for="Perempuan">
                            {{ $t("appointment.create_appointment.female") }}
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
            <div>
                <p class="fs-3 fw-bold">Konsultasi</p>
                <div
                    class="mt-3"
                    :class="{ error: v$.appointment_date.$errors.length }"
                >
                    <label for="date"
                        >{{
                            $t(
                                "appointment.create_appointment.consultation_date"
                            )
                        }}
                        <span class="text-red-500 fw-semibold">*</span></label
                    >
                    <input
                        v-model="form.appointment_date"
                        type="date"
                        name="date"
                        id="date"
                        class="form-control mt-2"
                        @input="v$.appointment_date.$touch()"
                    />
                    <div
                        class="error mt-2 fs-6 fw-bold text-red-200"
                        v-for="error of v$.appointment_date.$errors"
                        :key="error.$uid"
                    >
                        {{ error.$message }}
                    </div>
                </div>

                <div
                    class="mt-3"
                    :class="{ error: v$.guarantor_id.$errors.length }"
                >
                    <label for="guarantor"
                        >{{
                            $t("appointment.create_appointment.guarantor_name")
                        }}
                        <span class="text-red-500 fw-semibold">*</span></label
                    >
                    <div style="overflow-x: auto; max-width: 100%">
                        <select
                            name="guarantor"
                            id="guarantor"
                            class="form-select mt-2"
                            v-model="form.guarantor_id"
                            style="
                                min-width: 300px;
                                white-space: nowrap;
                                overflow-x: auto;
                                text-overflow: ellipsis;
                            "
                        >
                            <option value="" selected disabled>
                                Pilih Penjamin
                            </option>
                            <option
                                v-for="guarantor in guarantors"
                                :value="guarantor.guarantorID"
                                :title="guarantor.guarantorName"
                            >
                                {{
                                    guarantor.guarantorName.length > 30
                                        ? guarantor.guarantorName.slice(0, 30) +
                                          "..."
                                        : guarantor.guarantorName
                                }}
                            </option>
                        </select>
                    </div>
                    <div
                        class="error mt-2 fs-6 fw-bold text-red-200"
                        v-for="error of v$.guarantor_id.$errors"
                        :key="error.$uid"
                    >
                        {{ error.$message }}
                    </div>
                </div>
                <div
                    class="mt-3"
                    :class="{ error: v$.service_unit_id.$errors.length }"
                >
                    <label for="unit"
                        >{{ $t("appointment.create_appointment.unit") }}
                        <span class="text-red-500 fw-semibold">*</span></label
                    >
                    <select
                        name="unit"
                        id="unit"
                        class="form-select mt-2"
                        v-model="form.service_unit_id"
                        :disabled="serviceUnits && serviceUnits.length < 1"
                    >
                        <option value="" selected disabled>Pilih Unit</option>
                        <option
                            v-for="unit in serviceUnits"
                            :value="unit.serviceUnitID"
                        >
                            {{ unit.serviceUnitName }}
                        </option>
                    </select>
                    <div
                        class="error mt-2 fs-6 fw-bold text-red-200"
                        v-for="error of v$.service_unit_id.$errors"
                        :key="error.$uid"
                    >
                        {{ error.$message }}
                    </div>
                </div>
                <div
                    class="mt-3"
                    :class="{ error: v$.paramedic_id.$errors.length }"
                >
                    <label for="paramedis"
                        >{{ $t("appointment.create_appointment.paramedic") }}
                        <span class="text-red-500 fw-semibold">*</span></label
                    >
                    <select
                        name="paramedis"
                        id="paramedis"
                        class="form-select mt-2"
                        v-model="form.paramedic_id"
                        :disabled="paramedics && paramedics.length < 1"
                    >
                        <option value="" selected disabled>Pilih Dokter</option>
                        <option
                            v-for="paramedic in paramedics"
                            :value="paramedic.paramedicId"
                        >
                            {{ paramedic.paramedicName }}
                        </option>
                    </select>
                    <div
                        class="error mt-2 fs-6 fw-bold text-red-200"
                        v-for="error of v$.paramedic_id.$errors"
                        :key="error.$uid"
                    >
                        {{ error.$message }}
                    </div>
                </div>
            </div>

            <SubmitButton
                className="btn-blue-500-rounded"
                :text="$t('appointment.create_appointment.save')"
            />
            <router-link
                v-if="!isLoading"
                :to="{ name: 'AppointmentPage' }"
                class="text-center text-blue-500 text-decoration-none fw-bold"
                >{{ $t("appointment.create_appointment.cancel") }}</router-link
            >
        </form>
    </div>
</template>
