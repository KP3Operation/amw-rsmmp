import { defineStore } from "pinia";
import { ref } from "vue";

export const useGuarantorStore = defineStore("guarantor-store", () => {
    let guarantors = ref([]);

    function updateGuarantors(guarantorsData) {
        guarantors.value = guarantorsData;
        // console.log(guarantors.value);
    }

    function $reset() {
        guarantors.value = [];
    }

    return {
        $reset,
        updateGuarantors,
        guarantors,
    };
});
