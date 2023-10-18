<script setup>
import Header from "@shared/Components/Header/Header.vue";
import {onMounted, reactive, ref, watch} from "vue";
import Form from "vform";
import {useLayoutStore} from "@shared/+store/layout.store.js";
import {storeToRefs} from "pinia";
import SubmitButton from "@shared/Components/SubmitButton/SubmitButton.vue";
import axios from "axios";
import {useFamilyStore} from "@patient/+store/family.store.js";
import {useAuthStore} from "@shared/+store/auth.store.js";
import {useRoute} from "vue-router";
import router from "@patient/router.js";
import {useAppointmentStore} from "@patient/+store/appointment.store.js";

const layoutStore = useLayoutStore();
const { isLoading } = storeToRefs(layoutStore);
const familyStore = useFamilyStore();
const { families } = storeToRefs(familyStore);
const authStore = useAuthStore();
const { patientId, userFullName} = storeToRefs(authStore);
const appointmentStore = useAppointmentStore();
const { selectedServiceUnitId, selectedParamedicId } = storeToRefs(appointmentStore);
const form = reactive(
    new Form({
        patient_name: "",
        patient_id: null,
        birth_date: null,
        gender: null,
        appointment_date: null,
        service_unit_id: selectedServiceUnitId.value,
        paramedic_id: selectedParamedicId.value
    })
);
const paramedics = ref([]);
const serviceUnits = ref([]);
const route = useRoute();
const isReadonly = ref(false);

const storeAppointment = () => {
    layoutStore.updateLoadingState(true);
    form.post('/api/v1/patient/appointments/store').then((response) => {
        layoutStore.toggleSuccessAlert('Jadwal Konsultasi Berhasil Disimpan');
        router.push({ name: 'AppointmentPage' });
    }).catch((error) => {
        if (error.response) {
            layoutStore.toggleErrorAlert(`Jadwal Konsultasi Gagal Disimpan. Error: ${error.response.data.message}`);
            if (error.response.status !== 422) {
                router.push({ name: 'AppointmentPage' });
            }
        } else {
            layoutStore.toggleErrorAlert(`${error}`);
        }

    }).finally(() => {
        layoutStore.updateLoadingState(false);
    });
}

const fetchFamily = () => {
    axios.get(`/api/v1/patient/family`).then((response) => {
        const data = response.data;
        familyStore.updateFamilies(data.families);
        serviceUnits.value = data.service_units;
        paramedics.value = data.paramedics;
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    });
}

watch(form, (newValue, oldValue) => {
    if (newValue.patient_name === patientId.value) {
        form.patient_id = patientId;
    } else {
        families.value.map((family) => {
           if (family.patient_id === newValue.patient_name) {
               form.patient_id = family.patient_id;
           }
        });
    }
});

onMounted(() => {
    if (selectedParamedicId.value !== '') {
        isReadonly.value = true;
    }
    fetchFamily();
})
</script>

<template>
    <Header title="Buat Appointment" :with-back-url="true" page-name="AppointmentPage"></Header>
    <div class="px-4 pt-7 pb-4">
        <p class="fs-5 text-red-500">* Wajib Diisi</p>
        <form @submit.prevent="storeAppointment" class="d-flex flex-column rows-gap-16 mt-3" @keydown="form.onKeydown($event)">
            <p class="fs-3 fw-bold">Pasien</p>
            <div>
                <label for="nama">Nama Pasien <span class="text-red-500 fw-semibold">*</span></label>
                <select name="nama" id="nama" class="form-select mt-2" v-model="form.patient_name">
                    <option value="">Pilih Member</option>
                    <option :value="patientId">{{ userFullName }}</option>
                    <option v-for="family in families" :value="family.patient_id">{{ family.name }}</option>
                </select>
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('patient_name')"
                     v-html="form.errors.get('patient_name')" />
            </div>
            <div>
                <label for="id-pasien">ID Pasien</label>
                <input type="text" class="form-control mt-2" v-model="form.patient_id" id="id-pasien" readonly>
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('patient_id')"
                     v-html="form.errors.get('patient_id')" />
            </div>
            <div>
                <label for="dob">Tanggal Lahir <span class="text-red-500 fw-semibold">*</span></label>
                <input type="date" v-model="form.birth_date" name="dob" id="dob" class="form-control mt-2">
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('birth_date')"
                     v-html="form.errors.get('birth_date')" />
            </div>
            <div>
                <label for="gender">Jenis Kelamin <span class="text-red-500 fw-semibold">*</span></label>
                <div class="d-flex col-gap-20 mt-2">
                    <div class="form-check">
                        <input v-model="form.gender" class="form-check-input" type="radio" name="gender" id="Laki-Laki" value="Laki-Laki">
                        <label class="form-check-label" for="Laki-Laki">
                            Laki-Laki
                        </label>
                    </div>
                    <div class="form-check">
                        <input v-model="form.gender" class="form-check-input" type="radio" name="gender" value="Perempuan">
                        <label class="form-check-label" for="Perempuan">
                            Perempuan
                        </label>
                    </div>
                </div>
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('gender')"
                     v-html="form.errors.get('gender')" />
            </div>
            <p class="fs-3 fw-bold">Konsultasi</p>
            <div>
                <label for="date">Tanggal Konsultasi <span class="text-red-500 fw-semibold">*</span></label>
                <input v-model="form.appointment_date" type="datetime-local" name="date" id="date" class="form-control mt-2">
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('appointment_date')"
                     v-html="form.errors.get('appointment_date')" />
            </div>
            <div>
                <label for="unit">Pilih Unit <span class="text-red-500 fw-semibold">*</span></label>
                <select name="unit" id="unit" class="form-select mt-2" v-model="form.service_unit_id" :disabled="isReadonly">
                    <option value="" selected>Pilih Unit</option>
                    <option v-for="unit in serviceUnits" :value="unit.serviceUnitID">{{ unit.serviceUnitName }}</option>
                </select>
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('service_unit_id')"
                     v-html="form.errors.get('service_unit_id')" />
            </div>
            <div>
                <label for="paramedis">Pilih Dokter <span class="text-red-500 fw-semibold">*</span></label>
                <select name="paramedis" id="paramedis" class="form-select mt-2" v-model="form.paramedic_id" :disabled="isReadonly">
                    <option value="" selected>Pilih Dokter</option>
                    <option v-for="paramedic in paramedics" :value="paramedic.paramedicId">{{ paramedic.paramedicName }}</option>
                </select>
                <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('paramedic_id')"
                     v-html="form.errors.get('paramedic_id')" />
            </div>
            <SubmitButton className="btn-blue-500-rounded"
                          text="Simpan" />
            <router-link :to="{name: 'AppointmentPage'}"
                         class="text-center text-blue-500 text-decoration-none fw-bold">Batal</router-link>
        </form>
    </div>
</template>
