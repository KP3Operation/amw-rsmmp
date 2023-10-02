<script setup>
    import { toRefs } from "vue";
    import { convertDateTimeToDate, calculateAge } from "@shared/utils/helpers";

    const props = defineProps({
        name: String,
        medicalNo: String,
        gender: String,
        birthDate: String
    });

    const { name, medicalNo, gender, birthDate } = toRefs(props);

</script>
<template>
    <div class="bg-blue-100 rounded p-3">
        <div class="d-flex col-gap-20">
            <div class="w-50">
                <p class="fs-6 text-gray-700">{{ $t('history.name') }}</p>
                <p class="fs-6 fw-semibold">{{ name }}</p>
            </div>

            <div class="w-50 text-end">
                <p class="fs-6 text-gray-700">{{ $t('history.medical_no') }}</p>
                <p class="fs-6 fw-semibold">{{ medicalNo }}</p>
            </div>
        </div>

        <div class="d-flex col-gap-20 mt-2">
            <div class="w-50">
                <p class="fs-6 text-gray-700">{{ $t('history.gender') }}</p>
                <p class="fs-6 fw-semibold mt-1">{{ gender }}</p>
            </div>

            <div class="w-50 text-end">
                <p class="fs-6 text-gray-700">{{ $t('history.birth_date') }}</p>
                <p class="fs-6 fw-semibold mt-1"><span
                   v-text="convertDateTimeToDate(birthDate)"></span>&nbsp;
               (<span v-text="calculateAge(birthDate)"></span> {{
               $t('history.year') }})
                </p>
            </div>
        </div>
    </div>
</template>
