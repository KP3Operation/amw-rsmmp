<script setup>
    import { toRefs } from 'vue';
    import { convertDateTimeToDate } from "@shared/utils/helpers";

    const props = defineProps({
        dateCreated: String,
        timeCreated: String,
        registrationNo: String,
        vitalSignUnit: String,
        vitalSignName: String,
        questionAnswerNum: String
    });

    const { dateCreated, timeCreated, registrationNo, vitalSignName, vitalSignUnit, questionAnswerNum } = toRefs(props);
</script>

<template>
    <div class="d-flex flex-column rows-gap-16 rounded-3 p-3 bg-blue-100 border border-blue-200">
        <div class="d-flex justify-between">
            <div class="w-50">
                <p class="fs-6 text-gray-700">{{ $t('history.vital_sign_history.date_created') }}</p>
                <p class="mt-2 fs-5 fw-semibold"
                   v-text="convertDateTimeToDate(dateCreated)"></p>
            </div>

            <div class="w-50 text-end">
                <p class="fs-6 text-gray-700">{{ $t('history.vital_sign_history.time_created') }}</p>
                <p class="mt-2 fs-5 fw-semibold">{{ timeCreated }}</p>
            </div>
        </div>

        <div class="d-flex justify-between">
            <div class="w-50">
                <p class="fs-6 text-gray-700">{{ $t('history.vital_sign_history.registration_no') }}</p>
                <p class="mt-2 fs-5 fw-semibold">{{ registrationNo }}</p>
            </div>

            <div class="w-50 text-end">
                <p class="fs-6 text-gray-700">{{ $t('history.vital_sign_history.unit_vital_sign') }}</p>
                <p class="mt-2 fs-5 fw-semibold">{{ vitalSignName }}</p>
            </div>
        </div>

        <div>
            <p class="fs-6 text-gray-700">{{ $t('history.vital_sign_history.result') }}</p>
            <p class="mt-2 fs-5 fw-semibold">{{ questionAnswerNum }} {{ vitalSignUnit }}</p>
        </div>
    </div>
</template>
