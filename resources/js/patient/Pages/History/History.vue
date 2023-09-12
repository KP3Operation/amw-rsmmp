<script setup>
import Header from "@shared/Components/Header/Header.vue";
</script>

<template>
    <Header :title="$t('history.title')" :with-back-url="true" :with-action-btn="true">
        <button id="btn-akan-datang" class="btn d-flex col-gap-8 align-items-center fw-bold text-blue-500 p-0">
            <i class="bi bi-filter-left fs-2"></i>
        </button>
    </Header>
</template>
