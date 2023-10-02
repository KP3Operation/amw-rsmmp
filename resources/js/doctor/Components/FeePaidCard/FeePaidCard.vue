<script setup>

import {toRefs} from "vue";
import {toIdrFormat} from "@shared/utils/helpers.js";

const props = defineProps({
    id: String,
    registrationNo: String,
    medicalNo: String,
    patientName: String,
    itemName: String,
    qty: String,
    guarantorName: String,
    paidAmount: String
});

let { id, registrationNo, medicalNo, patientName, itemName, qty, guarantorName, paidAmount } = toRefs(props);

</script>

<template>
    <div :id="'item-bayar-' + id" class="item accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed col-gap-20" type="button"
                    data-bs-toggle="collapse" :data-bs-target="'#terbayar-' + id" aria-expanded="false"
                    :aria-controls="'terbayar-' + id">

                <div class="w-100 d-flex align-items-center justify-content-between col-gap-8">
                    <div class="flex-fill">
                        <p class="mt-2 fw-bold text-green-600 fs-3">+ {{ toIdrFormat(parseInt(paidAmount)) }}</p>
                    </div>

                    <div>
                        <!-- APABILA DATANYA BARU DAN BELUM DI KLIK MAKA KASIH TITIK-->
                        <!-- TODO: Need to add conditions -->
                        <!--  <div class="new-data"></div> -->
                    </div>
                </div>
            </button>
        </h2>

        <div :id="'terbayar-' + id" class="accordion-collapse collapse" :data-bs-parent="'#terbayar-' + id">
            <div class="accordion-body d-flex flex-column rows-gap-16">
                <div class="accordion-divider"></div>

                <div class="d-flex justify-content-between col-gap-20">
                    <div class="w-50">
                        <p class="text-gray-700 fs-5">No. Registrasi</p>

                        <p class="mt-1 text-black fw-semibold fs-5">{{ registrationNo }}</p>
                    </div>

                    <div class="w-50 text-end">
                        <p class="text-gray-700 fs-5">No. RM</p>

                        <p class="mt-1 text-black fw-semibold fs-5">{{ medicalNo }}</p>
                    </div>

                </div>

                <div>
                    <p class="text-gray-700 fs-5">Nama Pasien (No. Rekam Medis)</p>

                    <p class="mt-1 fs-5 fw-semibold text-black">{{ patientName }} ({{ medicalNo }})</p>
                </div>

                <div>
                    <p class="text-gray-700 fs-5">Nama Item</p>

                    <p class="mt-1 fs-5 fw-semibold text-black">{{ itemName }}</p>
                </div>


                <div class="fs-5">
                    <p class="text-gray-700">Qty</p>

                    <p class="mt- 1 fw-semibold">{{ qty }}</p>
                </div>

                <div class="fs-5">
                    <p class="text-gray-700">Nama Penjamin</p>

                    <p class="mt-1 fw-semibold">{{ guarantorName }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
