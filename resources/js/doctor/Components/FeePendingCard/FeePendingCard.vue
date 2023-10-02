<script setup>
import {toRefs} from "vue";

const props = defineProps({
    id: String,
    registrationNo: String,
    medicalNo: String,
    patientName: String,
    itemName: String,
    qty: String,
    guarantorName: String,
    paymentPercentage: String
});

let { id, registrationNo, medicalNo, patientName, itemName, qty, guarantorName, paymentPercentage } = toRefs(props);

</script>

<template>
    <div :id="'item-pending-' + id" class="item accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed col-gap-20" type="button"
                    data-bs-toggle="collapse" :data-bs-target="'#pending-' + id" aria-expanded="false"
                    :aria-controls="'pending-' + id">
                <div class="w-100 d-flex align-items-center justify-content-between col-gap-8">
                    <div class="flex-fill">
                        <p class="fs-6 text-gray-700 fw-normal">No. Registrasi</p>

                        <p class="mt-1">{{ registrationNo }}</p>
                    </div>

                    <div>
                        <!-- APABILA DATANYA BARU DAN BELUM DI KLIK MAKA KASIH TITIK-->
                        <!-- TODO: Need to add conditions -->
                        <!--  <div class="new-data"></div>-->
                    </div>
                </div>
            </button>
        </h2>

        <div :id="'pending-' + id" class="accordion-collapse collapse" :data-bs-parent="'#pending-' + id">
            <div class="accordion-body d-flex flex-column rows-gap-8">
                <div class="accordion-divider"></div>

                <div class="d-flex justify-content-between col-gap-20">
                    <div class="w-50 fs-5">
                        <p class="text-gray-700">Nama Pasien</p>

                        <p class="mt-1 fw-semibold">{{ patientName }}</p>
                    </div>

                    <div class="w-50 fs-5 text-end">
                        <p class="text-gray-700">No. RM</p>

                        <p class="mt-1 fw-semibold">{{ medicalNo }}</p>
                    </div>

                </div>


                <div>
                    <p class="text-gray-700 fs-5">Nama Item</p>

                    <p class="mt-1 fs-5 fw-semibold text-black">{{ itemName }}</p>
                </div>

                <div class="fs-5">
                    <p class="text-gray-700">Qty</p>

                    <p class="mt-1 fw-semibold">{{ qty }}</p>
                </div>

                <div class="fs-5">
                    <p class="text-gray-700">Nama Penjamin</p>

                    <p class="mt-1 fw-semibold">{{ guarantorName }}</p>
                </div>

                <div class="fs-5">
                    <p class="text-gray-700">Payment Percentage</p>

                    <p class="mt-1 fw-semibold">{{ paymentPercentage }}%</p>
                </div>
            </div>
        </div>
    </div>
</template>
