import { defineStore } from "pinia";
import { ref } from "vue";

export const useFamilyStore = defineStore(
    "family-store",
    () => {
        let families = ref([]);

        function $reset() {
            families.value = [];
        }

        return {
            $reset,
            families,
        };
    }
);
