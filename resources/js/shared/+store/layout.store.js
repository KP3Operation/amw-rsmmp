import { defineStore } from "pinia";
import { ref } from "vue";

export const useLayoutStore = defineStore("layout", () => {
    let patientActiveMenu = ref("home");
    let doctorActiveMenu = ref("home");
    let isFullView = ref(false);
    let showSuccessAlert = ref(false);
    let successAlertMessage = ref("");

    function toggleSuccessAlert(msg) {
        showSuccessAlert.value = true;
        successAlertMessage.value = `${msg}`;
    }

    function $reset() {
        patientActiveMenu.value = "home";
        doctorActiveMenu.value = "home";
        isFullView.value = false;
        showSuccessAlert.value = false;
        successAlertMessage.value = "";
    }

    return {
        toggleSuccessAlert,
        $reset,
        patientActiveMenu,
        doctorActiveMenu,
        isFullView,
        showSuccessAlert,
        successAlertMessage,
    };
});
