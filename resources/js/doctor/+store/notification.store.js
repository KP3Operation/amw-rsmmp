import { defineStore } from "pinia";
import { ref } from "vue";

export const useNotificationStore = defineStore(
    "notification-store",
    () => {
        let count = ref(0);
        let notifications = ref([]);


        function updateCount (countValue) {
            count.value = countValue;
        }

        function updateNotifications(data) {
            notifications.value = data;
        }


        function $reset() {
            count.value = 0;
            notifications.value = [];
        }

        return {
            $reset,
            updateCount,
            updateNotifications,
            count,
            notifications
        };
    }
);
