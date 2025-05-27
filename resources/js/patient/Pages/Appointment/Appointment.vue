<script setup>
import { useAppointmentStore } from "@patient/+store/appointment.store.js";
import { useFamilyStore } from "@patient/+store/family.store.js";
import PatientCard from "@patient/Components/PatientCard/PatientCard.vue";
import NotFoundImage from "@resources/static/images/not-found.png";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import Header from "@shared/Components/Header/Header.vue";
import apiRequest from "@shared/utils/axios.js";
import { convertDateTimeToDate } from "@shared/utils/helpers.js";
import * as bootstrap from "bootstrap";
import { storeToRefs } from "pinia";
import { onMounted, reactive, ref, watch } from "vue";

const modalState = reactive({
    cancelAppointmentModal: null,
    filterAppointmentModal: null,
});
const layoutStore = useLayoutStore();
const { isLoading } = storeToRefs(layoutStore);
const appointmentStore = useAppointmentStore();
const {
    openAppointments,
    closeAppointments,
    selectedMedicalNo,
    selectedStartDate,
    selectedEndDate,
    selectedServiceUnitId,
} = storeToRefs(appointmentStore);
const familyStore = useFamilyStore();
const { families } = storeToRefs(familyStore);
const serviceUnits = ref([]);
const selectedAppointmentNo = ref();
const selectedFamilyId = ref("");
const serviceUnitIdFilter = ref("");
const dateStartFilter = ref("");
const dateEndFilter = ref("");
let selectedPatient = reactive({
    name: "-",
    gender: "-",
    medicalNo: "-",
    birthDate: "-",
});

const cancelNote = ref(null);
const errorMessage = ref("");

const cancelNoteChange = () => {
    errorMessage.value = ""
}

const fetchAppointments = () => {
    layoutStore.updateLoadingState(true);

    let params = {
        medical_no: selectedMedicalNo.value,
        service_unit_id: selectedServiceUnitId.value,
        start_date: selectedStartDate.value,
        end_date: selectedEndDate.value,
    };

    if (selectedMedicalNo.value === null || selectedMedicalNo.value === '') {
        params = {
            medical_no: selectedMedicalNo.value,
            family_id: selectedFamilyId.value,
            service_unit_id: selectedServiceUnitId.value,
            start_date: selectedStartDate.value,
            end_date: selectedEndDate.value,
        };
    }

    apiRequest
        .get(`/api/v1/patient/appointments`, {
            params: params,
        })
        .then((response) => {
            const data = response.data;
            appointmentStore.updateOpenAppointments(data.appointments.opens);
            appointmentStore.updateCloseAppointments(
                data.appointments.dones,
                data.appointments.cancels
            );

            selectedPatient.name = data.patient.name;
            selectedPatient.gender = data.patient.gender;
            selectedPatient.medicalNo = data.patient.medical_no;
            selectedPatient.birthDate = data.patient.birth_date;

        })
        .catch((error) => {
            if (error.response) {
                if (error.response.status !== 404) {
                    layoutStore.toggleErrorAlert(
                        `${error.response.data.message}`
                    );
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
            serviceUnits.value = data.service_units;
        })
        .catch((error) => {
            if (error.response) {
                layoutStore.toggleErrorAlert(`${error.response.data.message}`);
            } else {
                layoutStore.toggleErrorAlert(`${error}`);
            }
        });
};

const showCancelModal = (appointmentNo) => {
    selectedAppointmentNo.value = appointmentNo;
    modalState.cancelAppointmentModal.show();
};

const cancelAppointment = () => {
    if(cancelNote.value == null || cancelNote.value == '') {
        errorMessage.value = "Alasan pembatalan tidak boleh kosong."
    }
    else {

        apiRequest
        .put(`/api/v1/patient/appointments/cancel`, {
            appointment_no: selectedAppointmentNo.value,
            note : cancelNote.value
        })
        .then((response) => {
            console.log('pembatalan berhasil')
            fetchAppointments();
            selectedAppointmentNo.value = "";
            modalState.cancelAppointmentModal.hide();
        })
        .catch((error) => {
            console.log('error')
            console.log(error.response?.data)
            errorMessage.value = error.response?.data?.message
        })
        .finally(() => {
            console.log('finally')
            //modalState.cancelAppointmentModal.hide();
        });
    }
};

const resetFilter = () => {
    appointmentStore.$reset();
    selectedFamilyId.value = "";
    serviceUnitIdFilter.value = "";
    dateStartFilter.value = "";
    dateEndFilter.value = "";
    fetchAppointments();
};

watch(selectedFamilyId, (newValue, oldValue) => {
    families.value.map((family) => {
        if (family.id === newValue) {
            appointmentStore.updateSelectedMedicalNo(family.medical_no);
        }
    });
});

watch(dateStartFilter, (newValue, oldValue) => {
    appointmentStore.updateSelectedStartDate(newValue);
});

watch(dateEndFilter, (newValue, oldValue) => {
    appointmentStore.updateSelectedEndDate(newValue);
});

watch(serviceUnitIdFilter, (newValue, oldValue) => {
    appointmentStore.updateSelectedServiceUnitId(newValue);
});

onMounted(() => {
    if (selectedMedicalNo === "") {
        appointmentStore.updateSelectedMedicalNo("");
    }
    appointmentStore.updateSelectedEndDate(null);
    appointmentStore.updateSelectedStartDate(null);


    fetchFamily();
    fetchAppointments();

    modalState.cancelAppointmentModal = new bootstrap.Modal("#modal-batal");
    modalState.filterAppointmentModal = new bootstrap.Modal("#modal-filter");
});
</script>

<template>
    <Header :title="$t('appointment.title')" :with-back-url="true" :with-action-btn="true">
        <router-link :to="{ name: 'CreateAppointmentPage' }" class="add">
            <i class="bi bi-plus-circle-fill"></i>
        </router-link>
    </Header>
    <div class="px-4 pt-8">
        <PatientCard class="mt-3" :name="selectedPatient.name"
            :gender="(selectedPatient.gender === 'M' || selectedPatient.gender === 'Laki-Laki') ? 'Laki-Laki' : 'Perempuan'"
            :medicalNo="selectedPatient.medicalNo" :birthDate="selectedPatient.birthDate" />

        <div class="tab-appointment nav nav-pills nav-justified d-flex col-gap-20 mt-4" role="tablist">
            <button class="nav-link w-50 active" data-bs-toggle="pill" data-bs-target="#akan-datang" role="tab"
                aria-controls="akan-datang" aria-selected="true" tabindex="0" form="#">
                <p> {{ $t('appointment.future') }}</p>
            </button>
            <button class="nav-link w-50" data-bs-toggle="pill" data-bs-target="#selesai" role="tab" aria-controls="selesai"
                aria-selected="false" tabindex="1" form="#">
                <p> {{ $t('appointment.done') }}</p>
            </button>
        </div>
        <div class="text-center mt-3" v-if="isLoading">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="tab-content mt-4" id="tab-content">
            <div class="tab-pane fade show active" id="akan-datang" role="tabpanel" aria-labelledby="akan-datang"
                tabindex="0">
                <section class="filter d-flex justify-content-between mt-4">
                    <p>
                        <span v-if="openAppointments.length > 0">
                            {{ openAppointments.length }}
                            {{ $t('appointment.future_appointment') }}
                        </span>
                    </p>
                    <button id="btn-akan-datang" class="btn d-flex col-gap-8 align-items-center fw-bold text-blue-500 p-0"
                        @click="modalState.filterAppointmentModal.show()" form="#">
                        <i class="bi bi-filter-left"></i>
                        {{ $t('appointment.filter') }}
                    </button>
                </section>
                <section v-if="openAppointments.length > 0" class="d-flex flex-column rows-gap-16 mt-4"
                    v-for="appointment in openAppointments">
                    <div class="d-flex flex-column rows-gap-20 bg-blue-100 rounded px-4 py-3">
                        <div class="d-flex justify-content-between col-gap-20">
                            <div class="d-flex col-gap-8 align-items-center">
                                <i class="bi bi-calendar-event fs-3 icon-blue-500"></i>
                                <p class="fs-5">
                                    {{
                                        convertDateTimeToDate(
                                            appointment.appointmentDate_yMdHms
                                        )
                                    }}
                                </p>
                            </div>
                            <div class="d-flex col-gap-8 justify-content-end align-items-center">
                                <i class="bi bi-clock fs-3 icon-blue-500"></i>
                                <p class="fs-5">
                                    {{ appointment.appointmentTime }}
                                </p>
                            </div>
                        </div>
                        <div>
                            <p class="fw-semibold">
                                {{ appointment.paramedicName }}
                            </p>
                            <p class="mt-2 text-gray-700 fs-5">
                                {{ appointment.serviceUnitName }}
                            </p>
                        </div>
                        <div class="d-flex col-gap-20 bg-gray-100 rounded px-2 py-2">
                            <div class="w-50">
                                <p class="fs-6 text-gray-700">Status</p>
                                <p class="fs-5 fw-semibold" v-if="appointment.appointmentStatus === '01'">{{ $t('appointment.booking') }}</p>
                                <p class="fs-5 fw-semibold" v-else>{{ $t('appointment.confirm') }}</p>
                            </div>

                            <div class="w-50 text-end" v-if="appointment.AppointmentQueFormattedNo !== ''">
                                <p class="fs-6 text-gray-700">No.Antrian</p>
                                <p class="fs-5 fw-semibold">{{ appointment.AppointmentQueFormattedNo }}</p>
                            </div>
                        </div>
                        <button class="btn text-red-500 fw-semibold p-0" @click="showCancelModal(appointment.appointmentNo)"
                            form="#">
                            {{ $t('appointment.actions.cancel') }}
                        </button>
                    </div>
                </section>
                <section class="d-flex flex-column rows-gap-16 mt-4" v-if="openAppointments.length < 1">
                    <div class="text-center mt-3">
                        <img :src="NotFoundImage" alt="Ilustrasi" width="270" height="202" />
                        <p class="mt-3 fw-bold fs-3">
                            {{ $t('appointment.no_appointment') }}
                        </p>
                        <p class="mt-2 text-gray-700" v-html="$t('appointment.create_appointment_desc')"></p>
                    </div>
                </section>
            </div>
            <div class="tab-pane fade" id="selesai" role="tabpanel" aria-labelledby="selesai" tabindex="1">
                <section class="filter d-flex justify-content-between mt-4">
                    <p>
                        <span v-if="closeAppointments.length > 0">
                            {{ closeAppointments.length }} {{ $t('appointment.indexed_appointment') }}
                        </span>
                    </p>
                    <button id="filter-selesai" class="btn d-flex col-gap-8 align-items-center fw-bold text-blue-500 p-0"
                        @click="modalState.filterAppointmentModal.show()" form="#">
                        <i class="bi bi-filter-left"></i>
                        {{ $t('appointment.filter') }}
                    </button>
                </section>
                <section v-if="closeAppointments.length > 0" class="d-flex flex-column rows-gap-16 mt-4"
                    v-for="appointment in closeAppointments">
                    <div class="d-flex flex-column rows-gap-20 bg-blue-100 rounded px-4 py-3">
                        <div class="d-flex justify-content-between col-gap-20">
                            <div class="d-flex col-gap-8 align-items-center">
                                <i class="bi bi-calendar-event fs-3 icon-blue-500"></i>
                                <p class="fs-5">
                                    {{convertDateTimeToDate(appointment.appointmentDate_yMdHms)}}
                                </p>
                            </div>
                            <div class="d-flex col-gap-8 justify-content-end align-items-center">
                                <i class="bi bi-clock fs-3 icon-blue-500"></i>
                                <p class="fs-5">
                                    {{ appointment.appointmentTime }}
                                </p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end align-items-end">
                            <div class="flex-fill">
                                <p class="fw-semibold">
                                    {{ appointment.paramedicName }}
                                </p>
                                <p class="mt-2 text-gray-700 fs-5">
                                    {{ appointment.serviceUnitName }}
                                </p>
                            </div>
                        </div>
                        <div class="d-flex col-gap-20 bg-gray-100 rounded px-2 py-2">
                            <div class="w-50">
                                <p class="fs-6 text-gray-700">Status</p>
                                <p class="fs-5 fw-semibold text-red-500" v-if="appointment.appointmentStatus === '03'">{{ $t('appointment.canceled') }}</p>
                                <p class="fs-5 fw-semibold" v-else>{{ $t('appointment.done') }}</p>
                            </div>

                            <div class="w-50 text-end" v-if="appointment.AppointmentQueFormattedNo !== ''">
                                <p class="fs-6 text-gray-700">No.Antrian</p>
                                <p class="fs-5 fw-semibold">{{ appointment.AppointmentQueFormattedNo }}</p>
                            </div>
                        </div>
                        <div  v-if="appointment.appointmentStatus === '03'" class="d-flex col-gap-20 bg-gray-100 rounded px-2 py-2">
                            <p class="fs-5 text-gray-700">{{ appointment.notes }}</p>
                        </div>
                    </div>
                </section>
                <section class="d-flex flex-column rows-gap-16 mt-4" v-if="closeAppointments.length < 1">
                    <div class="text-center mt-3">
                        <img :src="NotFoundImage" alt="Ilustrasi" width="270" height="202" />
                        <p class="mt-3 fw-bold fs-3">
                            {{ $t('appointment.no_consultation') }}
                        </p>
                        <p class="mt-2 text-gray-700" v-html="$t('appointment.no_consultation_desc')"></p>
                    </div>
                </section>
            </div>
        </div>


    </div>

    <div class="modal" id="modal-batal" aria-labelledby="Modal Batal" tabindex="-1" style="display: none"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div class="d-flex align-items-center col-gap-8">
                        <i class="bi bi-info-circle-fill icon-blue-500 fs-3"></i>
                        <h5 class="modal-title">{{ $t('appointment.cancel_consultation_modal.title') }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        @click="modalState.cancelAppointmentModal.hide()" aria-label="Close" form="#">
                        <i class="bi bi-x fs-2 icon-black"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-start">{{ $t('appointment.cancel_consultation_modal.message') }}</p>
                    <select class="form-select mt-2" v-model="cancelNote" required @change="cancelNoteChange()">
                        <option value="Batal Berobat">Batal Berobat</option>
                        <option value="Salah Pilih Dokter">Salah Pilih Dokter</option>
                        <option value="Salah Pilih Jadwal Dokter">Salah Pilih Jadwal Dokter</option>
                        <option value="Jadwal Dokter Berubah">Jadwal Dokter Berubah</option>
                        <option value="Ada Keperluan Lain">Ada Keperluan Lain</option>
                    </select>
                    <p class="fs-6 mt-2" style="font-style: italic;color: red;">{{ errorMessage }}</p>
                </div>

                <div class="modal-footer flex-nowrap">
                    <button type="button" class="w-50 btn btn-link fw-semibold" data-bs-dismiss="modal" form="#"
                        @click="modalState.cancelAppointmentModal.hide()">
                        {{ $t('appointment.cancel_consultation_modal.no') }}
                    </button>
                    <button type="button" class="w-50 btn-batal-konsultasi btn btn-red-500-rounded"
                        form="#" @click="cancelAppointment()">
                        {{ $t('appointment.cancel_consultation_modal.yes') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-filter" aria-labelledby="Modal Filter" tabindex="-1" style="display: none"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div class="d-flex align-items-center col-gap-8">
                        <i class="bi bi-info-circle-fill icon-blue-500 fs-3"></i>

                        <h5 class="modal-title">{{ $t('appointment.filter_modal.title') }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        @click="modalState.filterAppointmentModal.hide()" form="#">
                        <i class="bi bi-x fs-2 icon-black"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" class="d-flex flex-column rows-gap-16">
                        <div>
                            <label for="member" class="d-block text-start">{{ $t('appointment.filter_modal.member')
                            }}</label>
                            <select name="member" id="member" class="form-select mt-2" v-model="selectedFamilyId">
                                <option v-for="family in families" :value="family.id">
                                    {{ family.name }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="unit" class="d-block text-start">{{ $t('appointment.filter_modal.unit') }}</label>
                            <select name="unit" id="unit" class="form-select mt-2" v-model="serviceUnitIdFilter">
                                <option value="" selected>{{ $t('appointment.filter_modal.all') }}</option>
                                <option v-for="unit in serviceUnits" :value="unit.serviceUnitID">
                                    {{ unit.serviceUnitName }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="from" class="d-block text-start">{{ $t('appointment.filter_modal.from') }}</label>
                            <input type="date" name="from" id="from" class="form-control mt-2" v-model="dateStartFilter" />
                        </div>
                        <div>
                            <label for="to" class="d-block text-start">{{ $t('appointment.filter_modal.to') }}</label>
                            <input type="date" name="to" id="to" class="form-control mt-2" v-model="dateEndFilter" />
                        </div>
                        <div class="d-flex col-gap-20">
                            <button type="reset" class="w-50 btn btn-red-500-rounded" data-bs-dismiss="modal"
                                @click="resetFilter" form="#">
                                {{ $t('appointment.filter_modal.reset') }}
                            </button>
                            <button type="button" class="w-50 btn btn-blue-500-rounded" data-bs-dismiss="modal"
                                @click="fetchAppointments" form="#">
                                {{ $t('appointment.filter_modal.apply') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
