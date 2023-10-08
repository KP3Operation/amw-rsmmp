<script setup>
import { toRefs } from "vue";

const props = defineProps({
    title: String,
    withBackUrl: Boolean,
    pageName: String,
    withActionBtn: Boolean,
    customHeadingClass: String,
    additionalClass: String
});

let { title, withBackUrl, pageName, withActionBtn, customHeadingClass, additionalClass } = toRefs(props);
if (props.pageName === undefined) {
    pageName = 'HomePage';
}

if (props.additionalClass === undefined) {
    additionalClass = '';
}

</script>

<template>
    <div class="header header-default no-margin" :class="additionalClass">
        <router-link :to="{ name: pageName }" v-if="withBackUrl">
            <i class="bi bi-arrow-left"></i>
        </router-link>
        <h1 :class="customHeadingClass">{{ title }}</h1>
        <div v-if="withActionBtn">
            <slot></slot>
        </div>
    </div>
</template>
