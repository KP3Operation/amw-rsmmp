import "@resources/js/bootstrap";
import "@resources/scss/style.scss";
import router from "@auth/router";
import i18n from "@auth/i18n";

import.meta.glob(["@resources/static/**"]);

import { createApp } from "vue";

import Auth from "@auth/Auth.vue";

createApp(Auth).use(router).use(i18n).mount("#app");
