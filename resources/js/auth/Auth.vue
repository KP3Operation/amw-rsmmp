<script setup>
import { useLayoutStore } from "@shared/+store/layout.store.js";
import { storeToRefs } from "pinia";

const layoutStore = useLayoutStore();
const { showSuccessAlert,
    successAlertMessage,
    showErrorAlert,
    errorAlertMessage } = storeToRefs(layoutStore);
</script>

<template>
    <div>
        <router-view></router-view>
        <div :class="showSuccessAlert ? '' : 'd-none'"
            class="alert alert-success alert-dismissible d-flex col-gap-16 shadow fs-5 fw-semibold mt-6" role="alert">
            <p>{{ successAlertMessage }}</p>

            <button type="button" class="btn-close" @click="layoutStore.toggleSuccessAlert('')" aria-label="Close">
                <i class="bi bi-x icon-red-600 fs-2 fw-bold"></i>
            </button>
        </div>
        <div :class="showErrorAlert ? '' : 'd-none'"
            class="alert alert-danger alert-dismissible d-flex col-gap-16 shadow fs-5 fw-semibold mt-6" role="alert">
            <p>{{ errorAlertMessage }}</p>

            <button type="button" class="btn-close" @click="layoutStore.toggleErrorAlert('')" aria-label="Close">
                <i class="bi bi-x icon-red-600 fs-2 fw-bold"></i>
            </button>
        </div>
    </div>
</template>
