<script setup>
import {toRefs} from "vue";
import {convertDateTimeToDate} from "@shared/utils/helpers.js";

const props = defineProps({
    registrationNo: String,
    date: String,
    paramedicName: String,
});

const { registrationNo, date, paramedicName } = toRefs(props);
</script>

<template>
    <div class="d-flex flex-column rows-gap-16 rounded-3 p-3 bg-blue-100 border border-blue-200">
        <div class="d-flex justify-between">
            <div class="w-50">
                <p class="fs-6 text-gray-700">No. Registrasi</p>
                <p class="mt-2 fs-5 fw-semibold">{{registrationNo }}</p>
            </div>
            <div class="w-50 text-end">
                <p class="fs-6 text-gray-700">Tanggal</p>
                <p class="mt-2 fs-5 fw-semibold">{{ convertDateTimeToDate(date) }}</p>
            </div>
        </div>
        <div>
            <p class="fs-6 text-gray-700">Nama Dokter</p>
            <p class="mt-2 fs-5 fw-semibold">
            </p><p class="mt-2 fs-5 fw-semibold">{{ paramedicName }}</p>
            <p></p>
        </div>
        <router-link :to="{name: 'EncounterDetailsPage', query: {registrationNo: registrationNo}}"
                     class="btn btn-blue-500-rounded-sm mt-2">Detail</router-link>
    </div>
</template>
