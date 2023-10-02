<script setup>

import {toRefs} from "vue";

const props = defineProps({
    title: String,
    message: String,
    yesText: String,
    noText: String
});

const { title, message, yesText, noText } = toRefs(props);

</script>

<template>
    <div class="modal" id="modal-remove" aria-labelledby="Remove Modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div class="d-flex align-items-center col-gap-8">
                        <i class="bi bi-info-circle-fill icon-blue-500 fs-3"></i>
                        <h5 class="modal-title">{{ title }}</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x fs-2 icon-black"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ message }}</p>
                </div>
                <div class="modal-footer flex-nowrap">
                    <button type="button" class="w-50 btn btn-link" data-bs-dismiss="modal">{{ yesText }}</button>

                    <button type="button" class="w-50 btn-hapus-data btn btn-red-500-rounded"
                            data-bs-dismiss="modal">{{ noText }}</button>
                </div>
            </div>
        </div>
    </div>
</template>
