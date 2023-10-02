import { defineStore } from "pinia";
import { ref } from "vue";

export const useFeeByTrxDateStore = defineStore(
    "fee-by-trx-date-store",
    () => {
        let pendings = ref([]);
        let payouts = ref([]);

        function $reset() {
            pendings.value = [];
            pendings.payouts = [];
        }

        return {
            $reset,
            pendings,
            payouts,
        };
    }
);
