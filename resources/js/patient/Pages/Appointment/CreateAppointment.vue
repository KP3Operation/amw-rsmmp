<script>
import { useAppointmentStore } from "@patient/+store/appointment.store.js";
import { useDoctorScheduleStore } from "@patient/+store/doctor-schedule.store.js";
import { useFamilyStore } from "@patient/+store/family.store.js";
import router from "@patient/router.js";
import { useAuthStore } from "@shared/+store/auth.store.js";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import Header from "@shared/Components/Header/Header.vue";
import SubmitButton from "@shared/Components/SubmitButton/SubmitButton.vue";
import apiRequest from "@shared/utils/axios.js";
import useVuelidate from "@vuelidate/core";
import { helpers, required } from "@vuelidate/validators";
import { storeToRefs } from "pinia";
import { onMounted, reactive, ref, watch } from "vue";
import { useRoute } from "vue-router";

export default {
    components: { Header, SubmitButton },
    setup() {
        const doctorScheduleStore = useDoctorScheduleStore();
        const { schedules, selectedDate } = storeToRefs(doctorScheduleStore);
        const layoutStore = useLayoutStore();
        const { isLoading } = storeToRefs(layoutStore);
        const familyStore = useFamilyStore();
        const { families } = storeToRefs(familyStore);
        const authStore = useAuthStore();
        const { userData, userPatientData } = storeToRefs(authStore);
        const appointmentStore = useAppointmentStore();
        const { selectedServiceUnitId, selectedParamedicId } =
            storeToRefs(appointmentStore);
        const form = reactive({
            patient_name: "",
            patient_id: null,
            birth_date: null,
            gender: null,
            appointment_date: selectedDate,
            service_unit_id: selectedServiceUnitId.value,
            paramedic_id: selectedParamedicId.value,
            is_family_member: false
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
            paramedic_id: {
                required: helpers.withMessage(
                    "Dokter tidak boleh kosong",
                    required
                ),
            },
        };
        const v$ = useVuelidate(rules, form);
        const paramedics = ref([]);
        const serviceUnits = ref([]);
        const route = useRoute();
        const isReadonly = ref(false);
        const isFromDoctorSchedulePage = ref(false);
        const tempPatientName = ref("");
        const appointmentDateChanged = ref(true);
        const serviceUnitChanged = ref(true);

        const storeAppointment = async () => {
            layoutStore.updateLoadingState(true);

            // TODO: Somehow patient_name not populated
            // Double click in patient name
            if (
                (form.patient_name === null ||
                    form.patient_name === form.patient_id) &&
                tempPatientName.value !== ""
            ) {
                form.patient_name = tempPatientName.value;
            }

            const formValid = await v$.value.$validate();
            if (!formValid) {
                layoutStore.updateLoadingState(false);
                return;
            }

            apiRequest
                .post("/api/v1/patient/appointments/store", form)
                .then((response) => {
                    layoutStore.toggleSuccessAlert(
                        "Jadwal Konsultasi Berhasil Disimpan"
                    );
                    router.push({ name: "AppointmentPage" });
                })
                .catch((error) => {
                    if (error.response.status === 401) {
                        window.location.href = "/auth/login";
                    }

                    if (error.response) {
                        layoutStore.toggleErrorAlert(
                            `Jadwal Konsultasi Gagal Disimpan. Error: ${error.response.data.message}`
                        );
                        if (error.response.status !== 422) {
                            // router.push({ name: 'AppointmentPage' });
                        }
                    } else {
                        layoutStore.toggleErrorAlert(`${error}`);
                    }
                })
                .finally(() => {
                    layoutStore.updateLoadingState(false);
                });
        };

        const fetchFamily = () => {
            apiRequest
                .get(`/api/v1/patient/family`)
                .then((response) => {
                    const data = response.data;
                    familyStore.updateFamilies(data.families);
                    if (isFromDoctorSchedulePage.value) {
                        serviceUnits.value = data.service_units;
                        paramedics.value = data.paramedics;
                    }
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
                        // service_unit_id: `${selectedServiceUnitId.value}`
                    },
                })
                .then((response) => {
                    const data = response.data;
                    serviceUnits.value = data.schedules;
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
                } else if (newValue === userPatientData.value.patientId) {
                    form.patient_id = userPatientData.value.patientId;
                    form.birth_date = userPatientData.value.birthDate;
                    form.gender = userPatientData.value.gender;
                    tempPatientName.value = userData.value.userFullName;
                } else {
                    const foundFamily = families.value.find(
                        (family) => family.patient_id === newValue
                    );
                    if (foundFamily) {
                        form.patient_id = foundFamily.patient_id;
                        form.patient_name = newValue;
                        form.birth_date = foundFamily.birth_date;
                        form.gender = foundFamily.gender;
                        form.is_family_member = true;
                        tempPatientName.value = foundFamily.name;
                    }
                }
            }
        );

        watch(
            () => form.appointment_date,
            (newValue, oldValue) => {
                if (newValue && !isFromDoctorSchedulePage.value) {
                    serviceUnits.value = [];
                    paramedics.value = [];
                    form.service_unit_id = "";
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
                    const serviceUnit = serviceUnits.value.find(
                        (unit) => unit.serviceUnitID === newValue
                    );
                    if (serviceUnit) {
                        paramedics.value = serviceUnit.doctors;
                    }
                }
            }
        );

        onMounted(() => {
            form.patient_name = userPatientData.value.patientId;
            if (selectedDate) {
                fetchDoctorSchedules();
            }
            if (selectedParamedicId.value !== "") {
                isFromDoctorSchedulePage.value = true;
            }
            fetchFamily();
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
        };
    },
};
</script>

<template>
    <Header
        title="Buat Appointment"
        :with-back-url="true"
        page-name="AppointmentPage"
    ></Header>
    <div class="px-4 pt-7 pb-4">
        <p class="fs-5 text-red-500">* Wajib Diisi</p>
        <form
            @submit.prevent="storeAppointment"
            class="d-flex flex-column rows-gap-16 mt-3"
        >
            <p class="fs-3 fw-bold">Pasien</p>
            <div :class="{ error: v$.patient_name.$errors.length }">
                <label for="nama"
                    >Nama Pasien
                    <span class="text-red-500 fw-semibold">*</span></label
                >
                <select
                    name="nama"
                    id="nama"
                    class="form-select mt-2"
                    v-model="form.patient_name"
                    @input="v$.patient_name.$touch()"
                >
                    <option value="">Pilih Member</option>
                    <option :value="userPatientData.patientId">
                        {{ userData.userFullName }}
                    </option>
                    <option
                        v-for="family in families"
                        :value="family.patient_id"
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
                <label for="id-pasien">ID Pasien</label>
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
                    >Tanggal Lahir
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
                    >Jenis Kelamin
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
                            Laki-Laki
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
                            Perempuan
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
            <div v-if="!isFromDoctorSchedulePage">
                <p class="fs-3 fw-bold">Konsultasi</p>
                <div
                    class="mt-3"
                    :class="{ error: v$.appointment_date.$errors.length }"
                >
                    <label for="date"
                        >Tanggal Konsultasi
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
                    :class="{ error: v$.service_unit_id.$errors.length }"
                >
                    <label for="unit"
                        >Pilih Unit
                        <span class="text-red-500 fw-semibold">*</span></label
                    >
                    <select
                        name="unit"
                        id="unit"
                        class="form-select mt-2"
                        v-model="form.service_unit_id"
                        :disabled="serviceUnits.length < 1"
                    >
                        <option value="" selected>Pilih Unit</option>
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
                        >Pilih Dokter
                        <span class="text-red-500 fw-semibold">*</span></label
                    >
                    <select
                        name="paramedis"
                        id="paramedis"
                        class="form-select mt-2"
                        v-model="form.paramedic_id"
                        :disabled="paramedics.length < 1"
                    >
                        <option value="" selected>Pilih Dokter</option>
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

            <div v-if="isFromDoctorSchedulePage">
                <p class="fs-3 fw-bold">Konsultasi</p>
                <div
                    class="mt-3"
                    :class="{ error: v$.service_unit_id.$errors.length }"
                >
                    <label for="unit"
                        >Pilih Unit
                        <span class="text-red-500 fw-semibold">*</span></label
                    >
                    <select
                        name="unit"
                        id="unit"
                        class="form-select mt-2"
                        v-model="form.service_unit_id"
                        :disabled="isReadonly"
                    >
                        <option value="" selected>Pilih Unit</option>
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
                        >Pilih Dokter
                        <span class="text-red-500 fw-semibold">*</span></label
                    >
                    <select
                        name="paramedis"
                        id="paramedis"
                        class="form-select mt-2"
                        v-model="form.paramedic_id"
                        :disabled="isReadonly"
                    >
                        <option value="" selected>Pilih Dokter</option>
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
                <div
                    class="mt-3"
                    :class="{ error: v$.appointment_date.$errors.length }"
                >
                    <label for="date"
                        >Tanggal Konsultasi
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
            </div>
            <SubmitButton className="btn-blue-500-rounded" text="Simpan" />
            <router-link
                :to="{ name: 'AppointmentPage' }"
                class="text-center text-blue-500 text-decoration-none fw-bold"
                >Batal</router-link
            >
        </form>
    </div>
</template>
