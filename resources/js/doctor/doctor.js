import "@resources/js/bootstrap";
import "@resources/scss/style.scss";
import "bootstrap";
import router from "@doctor/router";
import i18n from "@doctor/i18n";
import pinia from "@shared/+store/pinia.init.js";
import Doctor from "@doctor/Doctor.vue";

import.meta.glob(["@resources/static/**"]);
const { t } = i18n.global;
window.t = t;

import { createApp } from "vue";


createApp(Doctor).use(pinia).use(router).use(i18n).mount("#app");
