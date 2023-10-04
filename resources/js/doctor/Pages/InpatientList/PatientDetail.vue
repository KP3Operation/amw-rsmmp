<script setup>
import {useInpatientStore} from "@doctor/+store/inpatient.store.js";
import Header from "@shared/Components/Header/Header.vue";
import {storeToRefs} from "pinia";

const inpatientStore = useInpatientStore();
const { selectedPatient } = storeToRefs(inpatientStore);
</script>
<template>
    <div>
        <Header title="Detail Pasien Rawat Inap" :with-back-url="true"></Header>
        <div class="px-4 pt-8 mt-4">
            <div class="d-flex flex-column rows-gap-16 mt-2">
                <div class="d-flex justify-content-between pb-3 border-bottom border-gray-400">
                    <div class="w-50">
                        <p class="fs-6 text-gray-700">No. Rekam Medis</p>
                        <p class="mt-2">23002911</p>
                    </div>

                    <div class="w-50 text-end">
                        <p class="fs-6 text-gray-700">Nama Ruang</p>
                        <p class="mt-2">Anggrek</p>
                    </div>
                </div>

                <div class="pb-3 border-bottom border-gray-400">
                    <div>
                        <p class="fs-6 text-gray-700">Nama Pasien</p>
                        <p class="mt-2">{{ selectedPatient.PatientName }}</p>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <div class="w-50">
                            <p class="fs-6 text-gray-700">Jenis Kelamin</p>
                            <p class="mt-2">Laki-Laki</p>
                        </div>

                        <div class="w-50 text-end">
                            <p class="fs-6 text-gray-700">Tanggal Lahir</p>
                            <p class="mt-2">
                                23/08/1998
                                (25 Tahun)
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pb-3">
                    <p class="fs-6 text-gray-700">Riwayat Medis</p>

                    <div class="accordion d-flex flex-column rows-gap-16 mt-3" id="accordion">
                        <div class="accordion-item rawat-inap">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed col-gap-20" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#riwayat-medis-1" aria-expanded="false" aria-controls="riwayat-medis-1">
                                    <div class="d-flex justify-content-between">
                                        <div class="w-75 d-flex flex-column rows-gap-16 fs-6">
                                            <div>
                                                <p class="fw-bold">Tanggal</p>
                                                <p class="fw-normal">25/03/2023</p>
                                            </div>

                                            <div>
                                                <p class="fw-bold">No. Registrasi</p>
                                                <p class="fw-normal">REG/20232503/001</p>
                                            </div>

                                            <div>
                                                <p class="fw-bold">Dibuat Oleh</p>
                                                <p class="fw-normal">Prof. Dr. Muhammad Rizal Sobirin S.Pok</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-25 text-end">
                                        <p class="fs-6 text-gray-700">Tipe CPPT</p>
                                        <p class="fs-3 fw-bold">SOAP</p>
                                    </div>
                                </button>
                            </h2>

                            <div id="riwayat-medis-1" class="accordion-collapse collapse" data-bs-parent="#riwayat-medis-1">
                                <div class="accordion-body">
                                    <div class="accordion-divider"></div>

                                    <ul class="mt-3 pl-1">
                                        <li>S: Pasien mengeluh nyeri perut sejak kemarin setelah makan.</li>

                                        <li>
                                            O: Terdapat nyeri tekan di daerah epigastrium. Tanda-tanda vital stabil.
                                        </li>

                                        <li>
                                            A: Kemungkinan gastritis akut.
                                        </li>

                                        <li>P: Berikan antasida, anjurkan diet lunak, pantau perkembangan</li>

                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item rawat-inap">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed col-gap-20" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#riwayat-medis-2" aria-expanded="false" aria-controls="riwayat-medis-2">
                                    <div class="d-flex justify-content-between">
                                        <div class="w-75 d-flex flex-column rows-gap-16 fs-6">
                                            <div>
                                                <p class="fw-bold">Tanggal</p>
                                                <p class="fw-normal">25/03/2023</p>
                                            </div>

                                            <div>
                                                <p class="fw-bold">No. Registrasi</p>
                                                <p class="fw-normal">REG/20232503/001</p>
                                            </div>

                                            <div>
                                                <p class="fw-bold">Dibuat Oleh</p>
                                                <p class="fw-normal">Prof. Dr. Muhammad Rizal Sobirin S.Pok</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="w-25 text-end">
                                        <p class="fs-6 text-gray-700">Tipe CPPT</p>
                                        <p class="fs-3 fw-bold">SBAR</p>
                                    </div>
                                </button>
                            </h2>

                            <div id="riwayat-medis-2" class="accordion-collapse collapse" data-bs-parent="#riwayat-medis-2">
                                <div class="accordion-body">
                                    <div class="accordion-divider"></div>

                                    <ul class="mt-3 pl-1">
                                        <li>S: Pasien, usia 60 tahun, mengalami peningkatan suhu tubuh hingga 38.5°C.
                                        </li>

                                        <li>
                                            B: Riwayat diabetes dan hipertensi, sedang menjalani pengobatan antibiotik untuk infeksi saluran pernapasan.
                                        </li>

                                        <li>
                                            A : Kemungkinan infeksi bakteri semakin memburuk.
                                        </li>

                                        <li>R: Saran untuk mengevaluasi kembali pengobatan antibiotik dan memantau respons pasien dalam 24 jam.</li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
