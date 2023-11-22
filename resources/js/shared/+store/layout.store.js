import { defineStore } from "pinia";
import { ref } from "vue";

export const useLayoutStore = defineStore("layout", () => {
    let patientActiveMenu = ref("home");
    let doctorActiveMenu = ref("home");
    let isFullView = ref(false);
    let showSuccessAlert = ref(false);
    let successAlertMessage = ref("");
    let showErrorAlert = ref(false);
    let errorAlertMessage = ref("");
    let isLoading = ref(false);
    let showInfoAlert = ref(false);
    let infoAlertMessage = ref("");

    function toggleSuccessAlert(msg = '') {
        if (msg !== '') {
            showSuccessAlert.value = !showSuccessAlert.value;
            successAlertMessage.value = `${msg}`;
            if (showSuccessAlert.value) {
                setTimeout(() => {
                    toggleSuccessAlert();
                }, 3000);
            }
        } else {
            showSuccessAlert.value = false;
        }
    }

    function toggleErrorAlert(msg = '') {
        if (msg !== '') {
            showErrorAlert.value = !showErrorAlert.value;
            errorAlertMessage.value = `${msg}`;
            if (showErrorAlert.value) {
                setTimeout(() => {
                    toggleErrorAlert();
                }, 3000);
            }
        } else {
            showErrorAlert.value = false;
        }
    }

    function toggleInfoAlert(msg = '') {
        if (msg !== '') {
            showInfoAlert.value = !showInfoAlert.value;
            infoAlertMessage.value = `${msg}`;
            if (showInfoAlert.value) {
                setTimeout(() => {
                    toggleInfoAlert();
                }, 3000);
            }
        } else {
            showInfoAlert.value = false;
        }
    }

    /**
     * @param {boolean} loading
     */
    function updateLoadingState (loading) {
       isLoading.value = loading;
    }

    function $reset() {
        patientActiveMenu.value = "home";
        doctorActiveMenu.value = "home";
        isFullView.value = false;
        showSuccessAlert.value = false;
        successAlertMessage.value = "";
        showErrorAlert.value = false;
        errorAlertMessage.value = "";
        isLoading = false;
        showInfoAlert.value = false;
        infoAlertMessage.value = "";
    }

    return {
        toggleSuccessAlert,
        toggleErrorAlert,
        toggleInfoAlert,
        $reset,
        updateLoadingState,
        patientActiveMenu,
        doctorActiveMenu,
        isFullView,
        showSuccessAlert,
        successAlertMessage,
        showErrorAlert,
        errorAlertMessage,
        isLoading,
        showInfoAlert,
        infoAlertMessage,
    };
});
