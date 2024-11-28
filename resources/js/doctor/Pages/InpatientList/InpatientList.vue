<script>
import { useInpatientStore } from "@doctor/+store/inpatient.store.js";
import InpatientListCard from "@doctor/Components/InpatientListCard/InpatientListCard.vue";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import Header from "@shared/Components/Header/Header.vue";
import apiRequest from "@shared/utils/axios.js";
import { storeToRefs } from "pinia";
import { onMounted, ref, watch } from "vue";

export default {
    components: {
        Header,
        InpatientListCard
    },
    setup() {
        const inpatientStore = useInpatientStore();
        const { patients, patientCount, selectedRegistrationNo,inpatientRooms } = storeToRefs(inpatientStore);
        const prevData = ref([]);

        const layoutStore = useLayoutStore();
        const { isLoading } = storeToRefs(layoutStore);
        const selectedRoomName = ref("");
        const selectedRoomID = ref("");
        

        const getInpatientRooms = () => {
            layoutStore.isLoading = true;
            apiRequest.get(`/api/v1/doctor/inpatient/rooms`).then((response) => {
                const data = response.data;
                data.inpatientRooms.map((inpatientRoom) => {
                    if (!prevData.value.includes(inpatientRoom.RoomID)) {
                        inpatientRooms.value.push(inpatientRoom);
                        patientCount.value += 1;
                    }
                });
                inpatientRooms.value.map((inpatientRoom) => {
                    prevData.value.push(
                        inpatientRoom.RoomID
                    );
                });
            }).catch((error) => {
                if (error.response.status === 401) {
                    window.location.href = '/auth/login';
                }
                layoutStore.toggleErrorAlert(`${error.response.data.message}`);
            }).finally(() => {
                layoutStore.isLoading = false;
            })
        }

        const filterInpatientList = () => {
            layoutStore.isLoading = true;
            apiRequest.get(`/api/v1/doctor/inpatient?room_id=${selectedRoomID.value}&prev_data=${prevData.value}`).then((response) => {
                patients.value = [];
                const data = response.data;
                data.patients.map((patient) => {
                    // if (!prevData.value.includes(patient.medicalNo)) {
                        patients.value.push(patient);
                        patientCount.value += 1;
                    // }
                });
                patients.value.map((patient) => {
                    prevData.value.push(patient.medicalNo);
                });
            }).catch((error) => {
                if (error.response.status === 401) {
                    window.location.href = '/auth/login';
                }
                layoutStore.toggleErrorAlert(`${error.response.data.message}`);
            }).finally(() => {
                layoutStore.isLoading = false;
            })
        }

        const loadMore = () => {
            filterInpatientList();
        }

        watch(selectedRoomID, (newValue, oldValue) => {
            // filterForm.fill({
            //     room_id: newValue
            // });
            prevData.value = null;
            filterInpatientList();
        });

        const setSelectedPatient = (patient) => {
            selectedRegistrationNo.value = patient.registrationNo;
            inpatientStore.setSelectedPatient(patient);
        }

        onMounted(() => {
            inpatientStore.$reset();
            getInpatientRooms();
            filterInpatientList();
        });

        return {
            inpatientStore,
            patients,
            patientCount,
            selectedRegistrationNo,
            prevData,
            layoutStore,
            isLoading,
            selectedRoomName,
            selectedRoomID,
            filterInpatientList,
            loadMore,
            setSelectedPatient,
            inpatientRooms
        };
    }
}
</script>

<template>
    <Header :title="$t('inpatient.title')" :with-back-url="true"></Header>
    <section
        class="filter-inpatient filter-sticky-2 d-flex align-items-center justify-content-between col-gap-20 p-4 mt-6 bg-white position-sticky">
        <p class="w-50"><span v-if="patientCount !== 0">{{ patientCount }} {{ $t('inpatient.patient_data') }}</span></p>
        <div id="multiselect" class="w-100 dropdown filter-sticky d-flex col-gap-20 align-items-center p-0">
            <select class="form-select" aria-label="Tipe" v-model="selectedRoomID">
                <option value="" selected>{{ $t('inpatient.all_room') }}</option>
                <option v-for="(ir,index) in inpatientRooms" :value="ir.RoomID">{{ ir.RoomName }}</option>
            </select>
        </div>
    </section>
    <div class="text-center mt-5" v-if="isLoading">
        <br><br>
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>  
    </div>
    <div class="d-flex flex-column rows-gap-16 mt-6 px-4" v-if="!isLoading">
        <div v-for="(patient, index) in patients">
            <InpatientListCard :id="index.toString()" :registrationNo="patient.registrationNo"
                :medicalNo="patient.medicalNo" :patientName="patient.patientName" :roomName="patient.roomName"
                @click="setSelectedPatient(patient)" />
        </div>
        <div class="text-center mt-3" v-if="patientCount === 0">
            <img src="@resources/static/images/not-found.png" alt="Ilustrasi Tidak Ditemukan" width="280" height="210">

            <p class="mt-4 fs-3 fw-bold">
                {{ $t('inpatient.no_inpatient_list') }}
            </p>
        </div>
    </div>

    <div class="d-flex flex-column rows-gap-16 mt-6 px-4" v-if="!isLoading && patients.length >= 10" @click="loadMore">
        <button type="button" class="btn btn-default">{{ $t('inpatient.load_more') }}</button>
    </div>
</template>
