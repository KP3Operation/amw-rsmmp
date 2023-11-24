<script>
import {useNotificationStore} from "@doctor/+store/notification.store.js";
import {mapActions, mapState, mapStores, storeToRefs} from "pinia";
import apiRequest from "@shared/utils/axios.js";
import {useAuthStore} from "@shared/+store/auth.store.js";

export default {
    name: 'DoctorHeader',
    computed: {
      ...mapState(useNotificationStore, {
          count: 'count'
      }),
      ...mapState(useAuthStore, {
          userDoctorData: 'userDoctorData'
        }),
    },
    data() {},
    methods: {
        ...mapActions(useNotificationStore, {
            updateCount: 'updateCount',
            updateNotifications: 'updateNotifications',
        }),
        getNotification() {
            apiRequest.get('/api/v1/doctor/notifications', {
                params: {doctor_id: this.userDoctorData.doctorId}
            }).then((response) => {
                const data = response.data;
                this.updateCount(data.count);
                this.updateNotifications(data.notifications);
            }).catch(() => {}).finally(() => {});
        }
    },
    mounted() {
        this.getNotification();
    }
}

</script>

<template>
    <div class="header header-dokter">
        <router-link to="/home">
            <img id="logo" alt="Logo Aviat">
        </router-link>
        <router-link to="/notification" class="notifikasi">
            <i class="bi bi-bell-fill fs-3"></i>

            <div class="info" v-if="count > 0">{{ count }}+</div>
        </router-link>
    </div>
</template>
