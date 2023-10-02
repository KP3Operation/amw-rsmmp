<script setup>

import {toRefs} from "vue";
import {convertDateTimeToDate} from "@shared/utils/helpers.js";

const props = defineProps({
    name: String,
    familyId: String,
    ssn: String,
    birthDate: String,
    phoneNumber: String
});

const emit = defineEmits(['delete-family-member']);

const { name, familyId, ssn, birthDate, phoneNumber } = toRefs(props);

const deleteFamilyEmitter = () => {
    emit('delete-family-member', {familyId: familyId, familyName: name});
}

</script>

<template>
    <div class="rounded-3 bg-blue-100 border border-blue-200 p-3">
        <div class="d-flex justify-content-between">
            <div class="w-50">
                <p class="fs-6 text-gray-700">{{ $t('family.family_card.name') }}</p>
                <p class="mt-2 fw-bold fs-5">{{ name }}</p>
            </div>

            <div class="w-50 text-end">
                <p class="fs-6 text-gray-700">{{ $t('family.family_card.phone_number') }}</p>
                <p class="mt-2 fw-bold fs-5">{{ phoneNumber }}</p>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <div class="w-50">
                <p class="fs-6 text-gray-700">{{ $t('family.family_card.ssn') }}</p>
                <p class="mt-2 fw-bold fs-5">{{ ssn }}</p>
            </div>

            <div class="w-50 text-end">
                <p class="fs-6 text-gray-700">{{ $t('family.family_card.birth_date') }}</p>
                <p class="mt-2 fw-bold fs-5" v-text="convertDateTimeToDate(birthDate)"></p>
            </div>
        </div>

        <div class="d-flex col-gap-20 mt-4 justify-content-between">
            <router-link :to="'/family/edit/' + familyId"
               class="btn w-50 btn-card btn-blue-500-rounded-sm flex-fill">{{ $t('family.family_card.edit') }}</router-link>

            <button class="btn w-50 btn-card btn-outline-red-rounded-sm flex-fill" type="button" @click="deleteFamilyEmitter">{{ $t('family.family_card.delete') }}</button>
        </div>
    </div>
</template>
