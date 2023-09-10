import { defineStore } from "pinia";
import { ref } from "vue";

export const useLayoutStore = defineStore("layout", () => {
    let patientActiveMenu = ref("home");

    function $reset() {
        patientActiveMenu.value = "home";
    }

    return {
        $reset,
        patientActiveMenu,
    };
});
