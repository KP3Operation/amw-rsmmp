<script setup>
import HomeHeader from "@doctor/Components/HomeHeader/HomeHeader.vue";
import Header from "@shared/Components/Header/Header.vue";
</script>

<template>
    <div>
        <Header :title="$t('notification.title')" :with-back-url="true"></Header>
    </div>
</template>
