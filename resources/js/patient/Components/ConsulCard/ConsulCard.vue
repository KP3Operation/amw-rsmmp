<script setup>
import { toRefs } from "vue";

const props = defineProps({
    doctor: String,
    unit: String,
    date: String,
    time: String,
    id: String
});

let { doctor, unit, date, time } = toRefs(props);
</script>

<template>
    <div class="carousel-item active rounded-3 bg-blue-500 text-white p-4">
        <p class="fw-bold">{{ doctor }}</p>
        <div class="mt-2 d-flex col-gap-20 fs-5">
            <div class="d-flex col-gap-8 align-items-center">
                <i class="bi bi-calendar-event"></i>
                <p>{{ date }}</p>
            </div>
            <div class="d-flex col-gap-8 align-items-center">
                <i class="bi bi-clock"></i>
                <p>{{ time }}</p>
            </div>
        </div>
        <p class="mt-2 fs-5">{{ unit }}</p>
    </div>
</template>
