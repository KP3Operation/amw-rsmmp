<script setup>
import { useLayoutStore } from "@shared/+store/layout.store.js";
import { toRefs } from "vue";

const props = defineProps({
    text: String,
    className: String
});
const layoutStore = useLayoutStore();
const { text, className } = toRefs(props);
</script>
<template>
    <button type="submit" class="btn" :class="className" v-if="!layoutStore.isLoading">{{ text }}</button>
    <div class=" text-center">
        <div class="spinner-border" role="status" v-if="layoutStore.isLoading">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</template>
