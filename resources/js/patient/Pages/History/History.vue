<script setup>
import MicroscopeWhite from "@resources/static/icons/microscope-white.svg";
import Header from "@shared/Components/Header/Header.vue";
import axios from "axios";
import { onMounted } from "vue";
import { usePatientVitalSignStore } from "@patient/+store/patient-vital-sign.store";
import { useLayoutStore } from "@shared/+store/layout.store";
import { convertDateTimeToDate, calculateAge } from "@shared/utils/helpers";

const patientVitalSignStore = usePatientVitalSignStore();
const layoutStore = useLayoutStore();

const fetchVitalSignHistory = () => {
    layoutStore.isLoading = true;
    patientVitalSignStore.$reset();
    axios.get('/api/v1/patient/medical/history/vitalsign').then((response) => {
        const data = response.data.data;
        data.histories.forEach((history) => {
            console.log(history);
            patientVitalSignStore.$patch((state) => {
                state.histories.push(history);
            });
        });
        patientVitalSignStore.$patch({
            patient: data.patient,
            patientData: data.patient.patient_data
        });
    }).catch((error) => {
        //
    }).finally(() => {
        layoutStore.isLoading = false;
    });
}

onMounted(() => {
    fetchVitalSignHistory();
});

</script>

<template>
    <div>
        <Header :title="$t('history.title')" :with-back-url="true" :with-action-btn="true">
            <button id="btn-akan-datang" class="btn d-flex col-gap-8 align-items-center fw-bold text-blue-500 p-0">
                <i class="bi bi-filter-left fs-2"></i>
            </button>
        </Header>

        <div class="px-4 pt-7">
            <div class="bg-blue-100 rounded p-3">
                <div class="d-flex col-gap-20">
                    <div class="w-50">
                        <p class="fs-6 text-gray-700">Nama</p>
                        <p class="fs-6 fw-semibold">{{ patientVitalSignStore.patient.name }}</p>
                    </div>

                    <div class="w-50 text-end">
                        <p class="fs-6 text-gray-700">No. RM</p>
                        <p class="fs-6 fw-semibold">12345678</p>
                    </div>
                </div>

                <div class="d-flex col-gap-20 mt-2">
                    <div class="w-50">
                        <p class="fs-6 text-gray-700">Jenis Kelamin</p>
                        <p class="fs-6 fw-semibold mt-1">{{ patientVitalSignStore.patientData.gender }}</p>
                    </div>

                    <div class="w-50 text-end">
                        <p class="fs-6 text-gray-700">Tanggal Lahir</p>
                        <p class="fs-6 fw-semibold mt-1"><span
                                v-text="convertDateTimeToDate(patientVitalSignStore.patientData.birth_date)"></span>&nbsp;
                            (<span v-text="calculateAge(patientVitalSignStore.patientData.birth_date)"></span> Tahun)
                        </p>
                    </div>
                </div>

            </div>

            <div class="tab-riwayat-medis nav nav-pills d-flex flex-nowrap col-gap-20 mt-4">
                <button class="nav-link unit-vital active" data-bs-toggle="pill" data-bs-target="#unit-vital" role="tab"
                    aria-controls="unit-vital" aria-selected="true">
                    <div class="icon">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>

                    <p>Tanda Vital</p>
                </button>

                <button class="nav-link resep-obat" data-bs-toggle="pill" data-bs-target="#resep-obat" role="tab"
                    aria-controls="resep-obat" aria-selected="true">
                    <div class="icon">
                        <i class="bi bi-capsule"></i>
                    </div>

                    <p>Resep Obat</p>
                </button>

                <button class="nav-link hasil-lab" data-bs-toggle="pill" data-bs-target="#hasil-lab" role="tab"
                    aria-controls="hasil-lab" aria-selected="true">
                    <div class="icon">
                        <img :src="MicroscopeWhite" alt="Icon" width="16" height="16">
                    </div>

                    <p>Hasil Lab</p>
                </button>

                <button class="nav-link pertemuan" data-bs-toggle="pill" data-bs-target="#pertemuan" role="tab"
                    aria-controls="pertemuan" aria-selected="true">
                    <div class="icon">
                        <i class="bi bi-clipboard-heart-fill"></i>
                    </div>

                    <p>Pertemuan</p>
                </button>
            </div>


            <!-- START LIST TAB-->
            <div class="tab-content mt-4" id="tab-content">

                <!-- START UNIT VITAL-->
                <section class="tab-pane fade show active" id="unit-vital" role="tabpanel" aria-labelledby="unit-vital"
                    tabindex="0">
                    <!-- START FILTER -->
                    <div id="multiselect" class="dropdown filter-sticky d-flex col-gap-20 align-items-center">
                        <p>Filter</p>
                        <button
                            class="d-flex align-items-center w-100 px-3 py-2 border border-gray-400 rounded-3 bg-white justify-content-between text-black"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span>Semua</span>

                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu px-4 py-3 shadow rounded-0 border-0 mt-2">
                            <li>
                                <input class="form-check-input on" type="checkbox" name="filter" value="Semua" id="semua"
                                    checked>
                                <label class="form-check-label" for="semua">
                                    Semua
                                </label>
                            </li>

                            <li class="mt-3">
                                <input class="form-check-input" type="checkbox" name="filter" value="Denyut Nadi"
                                    id="denyut-nadi" checked>
                                <label class="form-check-label" for="denyut-nadi">
                                    Denyut Nadi
                                </label>
                            </li>

                            <li class="mt-3">
                                <input class="form-check-input" type="checkbox" name="filter" value="Suhu Tubuh"
                                    id="suhu-tubuh" checked>
                                <label class="form-check-label" for="suhu-tubuh">
                                    Suhu Tubuh
                                </label>
                            </li>

                            <li class="mt-3">
                                <input class="form-check-input" type="checkbox" name="filter" value="Tekanan Darah"
                                    id="tekanan-darah" checked>
                                <label class="form-check-label" for="tekanan-darah">
                                    Tekanan Darah
                                </label>
                            </li>
                        </ul>
                    </div>
                    <!-- END FILTER -->

                    <!-- START LIST UNIT VITAL-->
                    <div class="d-flex flex-column rows-gap-16 mt-4"
                        v-for="(history, index) in patientVitalSignStore.histories">
                        <div class="d-flex flex-column rows-gap-16 rounded-3 p-3 bg-blue-100 border border-blue-200">
                            <div class="d-flex justify-between">
                                <div class="w-50">
                                    <p class="fs-6 text-gray-700">Tanggal Pencatatan</p>
                                    <p class="mt-2 fs-5 fw-semibold"
                                        v-text="convertDateTimeToDate(history.recordDate_yMdHms)"></p>
                                </div>

                                <div class="w-50 text-end">
                                    <p class="fs-6 text-gray-700">Jam Pencatatan</p>
                                    <p class="mt-2 fs-5 fw-semibold">{{ history.recordTime }}</p>
                                </div>
                            </div>

                            <div class="d-flex justify-between">
                                <div class="w-50">
                                    <p class="fs-6 text-gray-700">No. Registrasi</p>
                                    <p class="mt-2 fs-5 fw-semibold">{{ history.registrationNo }}</p>
                                </div>

                                <div class="w-50 text-end">
                                    <p class="fs-6 text-gray-700">Unit Tanda Vital</p>
                                    <p class="mt-2 fs-5 fw-semibold">{{ history.vitalSignUnit }}</p>
                                </div>
                            </div>

                            <div>
                                <p class="fs-6 text-gray-700">Unit</p>
                                <p class="mt-2 fs-5 fw-semibold">
                                <p class="mt-2 fs-5 fw-semibold">{{ history.vitalSignName }}</p>
                                </p>
                            </div>
                        </div>

                    </div>
                    <!-- END LIST UNIT VITAL-->
                </section>
                <!-- END UNIT VITAL-->


                <div class="text-center mt-3" v-if="layoutStore.isLoading">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

            </div>
            <!-- END LIST TAB-->
        </div>
        <!-- END CONTAINER-->

        <!-- START MODAL FILTER -->
        <div class="modal" id="modal-filter" aria-labelledby="Modal Filter" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">

                    <!-- START POPUP HEADER -->
                    <div class="modal-header d-flex justify-content-between">
                        <div class="d-flex align-items-center col-gap-8">
                            <i class="bi bi-info-circle-fill icon-blue-500 fs-3"></i>

                            <h5 class="modal-title">Filter</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x fs-2 icon-black"></i>
                        </button>
                    </div>
                    <!-- END POPUP HEADER-->

                    <!-- START POPUP BODY-->
                    <div class="modal-body">
                        <form action="" class="d-flex flex-column rows-gap-16">
                            <div>
                                <label for="member" class="d-block text-start">Member</label>
                                <select name="member" id="member" class="form-select mt-2">
                                    <option value="Muhammad John Doe" selected>Muhammad John Doe</option>
                                    <option value="Diego Rizki">Diego Rizki</option>
                                    <option value="Wanda Rachel">Wanda Rachel</option>
                                </select>
                            </div>

                            <div class="d-flex col-gap-20">

                                <button type="reset" class="w-50 btn btn-red-500-rounded"
                                    data-bs-dismiss="modal">Reset</button>

                                <button type="button" class="w-50 btn btn-blue-500-rounded"
                                    data-bs-dismiss="modal">Terapkan</button>
                            </div>

                        </form>
                    </div>
                    <!-- END POPUP BODY-->
                </div>
            </div>
        </div>
        <!-- END MODAL FILTER -->
    </div>
</template>
