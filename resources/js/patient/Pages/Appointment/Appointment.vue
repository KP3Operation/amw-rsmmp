<script setup>
import Header from "@shared/Components/Header/Header.vue";
import {onMounted, reactive, ref, watch} from "vue";
import axios from "axios";
import {useLayoutStore} from "@shared/+store/layout.store.js";
import {storeToRefs} from "pinia";
import {useAppointmentStore} from "@patient/+store/appointment.store.js";
import {convertDateTimeToDate} from "@shared/utils/helpers.js";
import * as bootstrap from "bootstrap";
import {useFamilyStore} from "@patient/+store/family.store.js";
import NotFoundImage from "@resources/static/images/not-found.png";

const modalState = reactive({
    cancelAppointmentModal: null,
    filterAppointmentModal: null
});
const layoutStore = useLayoutStore();
const { isLoading } = storeToRefs(layoutStore);
const appointmentStore = useAppointmentStore();
const { openAppointments, closeAppointments,
    selectedMedicalNo, selectedStartDate, selectedEndDate, selectedServiceUnitId  } = storeToRefs(appointmentStore);
const familyStore = useFamilyStore();
const { families } = storeToRefs(familyStore);
const serviceUnits = ref([]);
const selectedAppointmentNo = ref();
const selectedFamilyId = ref("");
const serviceUnitIdFilter = ref("");
const dateStartFilter = ref("");
const dateEndFilter = ref("");

const fetchAppointments = () => {
    layoutStore.updateLoadingState(true);
    axios.get(`/api/v1/patient/appointments`, {
        params: {
            medical_no: selectedMedicalNo.value,
            service_unit_id: selectedServiceUnitId.value,
            start_date: selectedStartDate.value,
            end_date: selectedEndDate.value
        }
    }).then((response) => {
        const data = response.data;
        appointmentStore.updateOpenAppointments(data.appointments.opens);
        appointmentStore.updateCloseAppointments(data.appointments.dones, data.appointments.cancels);
    }).catch((error) => {
        if (error.response) {
            layoutStore.toggleErrorAlert(`${error.response.data.message}`);
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
    }).catch((error) => {
        if (error.response) {
            layoutStore.toggleErrorAlert(`${error.response.data.message}`);
        } else {
            layoutStore.toggleErrorAlert(`${error}`);
        }
    });
}

const showCancelModal = (appointmentNo) => {
    selectedAppointmentNo.value = appointmentNo;
    modalState.cancelAppointmentModal.show();
}

const cancelAppointment = () => {
    axios.delete(`/api/v1/patient/appointments`, {
        params: {
            appointment_no: selectedAppointmentNo.value
        }
    }).then((response) => {
        console.log(response);
    }).catch((error) => {
        if (error.response) {
            layoutStore.toggleErrorAlert(`${error.response.data.message}`);
        } else {
            layoutStore.toggleErrorAlert(`${error}`);
        }
    }).finally(() => {
       fetchAppointments();
       selectedAppointmentNo.value = "";
       modalState.cancelAppointmentModal.hide();
    });
}

const resetFilter = () => {
    appointmentStore.$reset();
    fetchAppointments();
}

watch(selectedFamilyId, (newValue, oldValue) => {
    families.value.map(family => {
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

    fetchAppointments();
    fetchFamily();

    modalState.cancelAppointmentModal = new bootstrap.Modal("#modal-batal");
    modalState.filterAppointmentModal = new bootstrap.Modal("#modal-filter");
});
</script>

<template>
    <Header :title="$t('appointment.title')" :with-back-url="true" :with-action-btn="true">
        <router-link :to="{name: 'CreateAppointmentPage'}" class="add">
            <i class="bi bi-plus-circle-fill"></i>
        </router-link>
    </Header>
    <div class="px-4 pt-8">
        <div class="tab-appointment nav nav-pills nav-justified d-flex col-gap-20 mt-4" role="tablist">
            <button class="nav-link w-50 active" data-bs-toggle="pill" data-bs-target="#akan-datang" role="tab"
                    aria-controls="akan-datang" aria-selected="true" form="#">
                <p>Akan Datang</p>
            </button>
            <button class="nav-link w-50" data-bs-toggle="pill" data-bs-target="#selesai" role="tab" aria-controls="selesai"
                    aria-selected="false" tabindex="-1" form="#">
                <p>Selesai</p>
            </button>
        </div>
        <div class="tab-content mt-4" id="tab-content" v-if="!isLoading">
            <div class="tab-pane fade show active" id="akan-datang" role="tabpanel" aria-labelledby="akan-datang" tabindex="0">
                <section class="filter d-flex justify-content-between mt-4">
                    <p><span v-if="openAppointments.length > 0">{{ openAppointments.length }} Appointment Akan Datang</span></p>
                    <button id="btn-akan-datang" class="btn d-flex col-gap-8 align-items-center fw-bold text-blue-500 p-0"
                            @click="modalState.filterAppointmentModal.show()" form="#">
                        <i class="bi bi-filter-left"></i>
                        Filter
                    </button>
                </section>
                <section v-if="openAppointments.length > 0" class="d-flex flex-column rows-gap-16 mt-4" v-for="appointment in openAppointments">
                    <div class="d-flex flex-column rows-gap-20 bg-blue-100 rounded px-4 py-3">
                        <div class="d-flex justify-content-between col-gap-20">
                            <div class="d-flex col-gap-8 align-items-center">
                                <i class="bi bi-calendar-event fs-3 icon-blue-500"></i>
                                <p class="fs-5">{{ convertDateTimeToDate(appointment.appointmentDate_yMdHms) }}</p>
                            </div>
                            <div class="d-flex col-gap-8 justify-content-end align-items-center">
                                <i class="bi bi-clock fs-3 icon-blue-500"></i>
                                <p class="fs-5">{{ appointment.appointmentTime }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="fw-semibold">{{ appointment.paramedicName }}</p>
                            <p class="mt-2 text-gray-700 fs-5">{{ appointment.serviceUnitName }}</p>
                        </div>
                        <button class="btn text-red-500 fw-semibold p-0"
                                @click="showCancelModal(appointment.appointmentNo)" form="#">Batal</button>
                    </div>
                </section>
                <section class="d-flex flex-column rows-gap-16 mt-4"
                         v-if="openAppointments.length < 1">
                    <div class="text-center mt-3">
                        <img :src="NotFoundImage" alt="Ilustrasi" width="270" height="202">
                        <p class="mt-3 fw-bold fs-3">Janji Konsultasi Tidak Ditemukan</p>
                        <p class="mt-2 text-gray-700">Buat Janji Konsultasi Baru Dengan Mentap <br> Tombol “Buat
                            Janji Konsultasi”</p>
                    </div>
                </section>
            </div>
            <div class="tab-pane fade" id="selesai" role="tabpanel" aria-labelledby="selesai" tabindex="0">
                <section class="filter d-flex justify-content-between mt-4">
                    <p><span v-if="closeAppointments.length > 0">{{ closeAppointments.length }} Appointment Terdata</span></p>
                    <button id="filter-selesai" class="btn d-flex col-gap-8 align-items-center fw-bold text-blue-500 p-0"
                            @click="modalState.filterAppointmentModal.show()" form="#">
                        <i class="bi bi-filter-left"></i>
                        Filter
                    </button>
                </section>
                <section v-if="closeAppointments.length > 0" class="d-flex flex-column rows-gap-16 mt-4" v-for="appointment in closeAppointments">
                    <div class="d-flex flex-column rows-gap-20 bg-blue-100 rounded px-4 py-3">
                        <div class="d-flex justify-content-between col-gap-20">
                            <div class="d-flex col-gap-8 align-items-center">
                                <i class="bi bi-calendar-event fs-3 icon-blue-500"></i>
                                <p class="fs-5">{{ convertDateTimeToDate(appointment.appointmentDate_yMdHms) }}</p>
                            </div>
                            <div class="d-flex col-gap-8 justify-content-end align-items-center">
                                <i class="bi bi-clock fs-3 icon-blue-500"></i>
                                <p class="fs-5">{{ appointment.appointmentTime }}</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end align-items-end">
                            <div class="flex-fill">
                                <p class="fw-semibold">{{ appointment.paramedicName }}</p>
                                <p class="mt-2 text-gray-700 fs-5">{{ appointment.serviceUnitName }}</p>
                            </div>
                            <p class="px-2 py-1 bg-red-100 text-red-500 fw-bold text-sm rounded fs-5"
                               v-if="appointment.appointmentStatus === '03'">Dibatalkan</p>
                            <p class="px-2 py-1 bg-green-100 text-green-500 fw-bold text-sm rounded fs-5"
                                v-else>Selesai</p>
                        </div>
                    </div>
                </section>
                <section class="d-flex flex-column rows-gap-16 mt-4"
                         v-if="closeAppointments.length < 1">
                    <div class="text-center mt-3">
                        <img :src="NotFoundImage" alt="Ilustrasi" width="270" height="202">
                        <p class="mt-3 fw-bold fs-3">Tidak Ada Janji Konsultasi</p>
                        <p class="mt-2 text-gray-700">Anda Belum Memiliki Janji Konsultasi Yang<br> Sudah Selesai</p>
                    </div>
                </section>
            </div>
        </div>

        <div class="text-center mt-3" v-if="isLoading">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-batal" aria-labelledby="Modal Batal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div class="d-flex align-items-center col-gap-8">
                        <i class="bi bi-info-circle-fill icon-blue-500 fs-3"></i>
                        <h5 class="modal-title">Batalkan Janji Konsultasi</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            @click="modalState.cancelAppointmentModal.hide()"
                            aria-label="Close" form="#">
                        <i class="bi bi-x fs-2 icon-black"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin membatalkan Janji Konsultasi?</p>
                </div>
                <div class="modal-footer flex-nowrap">
                    <button type="button" class="w-50 btn btn-link fw-semibold" data-bs-dismiss="modal"
                            form="#" @click="modalState.cancelAppointmentModal.hide()">Tidak</button>
                    <button type="button" class="w-50 btn-batal-konsultasi btn btn-red-500-rounded"
                            data-bs-dismiss="modal" form="#" @click="cancelAppointment()">Ya, Batalkan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-filter" aria-labelledby="Modal Filter" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div class="d-flex align-items-center col-gap-8">
                        <i class="bi bi-info-circle-fill icon-blue-500 fs-3"></i>

                        <h5 class="modal-title">Filter</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            @click="modalState.filterAppointmentModal.hide()" form="#">
                        <i class="bi bi-x fs-2 icon-black"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" class="d-flex flex-column rows-gap-16">
                        <div>
                            <label for="member" class="d-block text-start">Member</label>
                            <select name="member" id="member" class="form-select mt-2" v-model="selectedFamilyId">
                                <option v-for="family in families" :value="family.id">{{ family.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="unit" class="d-block text-start">Unit</label>
                            <select name="unit" id="unit" class="form-select mt-2" v-model="serviceUnitIdFilter">
                                <option value="" selected>Semua</option>
                                <option v-for="unit in serviceUnits" :value="unit.serviceUnitID">
                                    {{ unit.serviceUnitName }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="from" class="d-block text-start">Dari</label>
                            <input type="date" name="from" id="from" class="form-control mt-2" v-model="dateStartFilter">
                        </div>
                        <div>
                            <label for="to" class="d-block text-start">Hingga</label>
                            <input type="date" name="to" id="to" class="form-control mt-2" v-model="dateEndFilter">
                        </div>
                        <div class="d-flex col-gap-20">
                            <button type="reset" class="w-50 btn btn-red-500-rounded" data-bs-dismiss="modal"
                                    @click="resetFilter" form="#">Reset</button>
                            <button type="button" class="w-50 btn btn-blue-500-rounded" data-bs-dismiss="modal"
                                    @click="fetchAppointments" form="#">Terapkan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
