import { defineStore } from "pinia";
import { ref } from "vue";

export const useFamilyStore = defineStore(
    "family-store",
    () => {
        let families = ref([]);

        function updateFamilies (familiesData) {
            families.value = familiesData;
        }

        function $reset() {
            families.value = [];
        }

        return {
            $reset,
            updateFamilies,
            families,
        };
    }
);
