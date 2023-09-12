<script setup>
import Header from "@shared/Components/Header/Header.vue";
</script>

<template>
    <Header :title="$t('appointment.title')" :with-back-url="true" :with-action-btn="true">
        <router-link to="appointment/create" class="add">
            <i class="bi bi-plus-circle-fill"></i>
        </router-link>
    </Header>
</template>
