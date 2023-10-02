import { defineStore } from "pinia";
import { ref } from "vue";

export const useNotificationStore = defineStore(
    "notification-store",
    () => {
        let count = ref(0);

        function $reset() {
            count.value = 0
        }

        return {
            $reset,
            count,
        };
    }
);
