<script setup>
import { useLayoutStore } from "@shared/+store/layout.store.js";

const layoutStore = useLayoutStore();
</script>

<template>
    <div>
        <router-view></router-view>
        <div :class="layoutStore.showSuccessAlert ? '' : 'd-none'"
            class="alert alert-success alert-dismissible d-flex col-gap-16 shadow fs-5 fw-semibold mt-6" role="alert">
            <p>{{ layoutStore.successAlertMessage }}</p>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="bi bi-x icon-red-600 fs-2 fw-bold"></i>
            </button>
        </div>
        <div :class="layoutStore.showErrorAlert ? '' : 'd-none'"
            class="alert alert-danger alert-dismissible d-flex col-gap-16 shadow fs-5 fw-semibold mt-6" role="alert">
            <p>{{ layoutStore.errorAlertMessage }}</p>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <i class="bi bi-x icon-red-600 fs-2 fw-bold"></i>
            </button>
        </div>
    </div>
</template>
