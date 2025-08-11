import "@resources/js/bootstrap";
import "@resources/scss/style.scss";
import "bootstrap";
import router from "@patient/router";
import i18n from "@patient/i18n";
import pinia from "@shared/+store/pinia.init.js";
import Patient from "@patient/Patient.vue";

import.meta.glob(["@resources/static/**"]);

import { createApp } from "vue";
const { t } = i18n.global;
window.t = t;

createApp(Patient).use(pinia).use(router).use(i18n).mount("#app");
