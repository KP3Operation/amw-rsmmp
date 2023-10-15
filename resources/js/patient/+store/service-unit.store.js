import { defineStore } from "pinia";
import { ref } from "vue";
import {convertDateToFormField, getCurrentDate} from "@shared/utils/helpers.js";

export const useServiceUnitStore = defineStore(
    "service-unit-store",
    () => {
        let serviceUnits = ref([]);

        /**
         * @param {array<string, string>} units
         */
        function updateServiceUnits (units) {
            serviceUnits.value = units;
        }

        function $reset() {
            serviceUnits.value = [];
        }

        return {
            $reset,
            updateServiceUnits,
            serviceUnits
        };
    }
);
