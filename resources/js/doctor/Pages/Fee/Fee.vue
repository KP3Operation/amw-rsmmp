<script>

import { useFeeByTrxDateStore } from "@doctor/+store/fee-by-trx-date.store.js";
import FeePaidCard from "@doctor/Components/FeePaidCard/FeePaidCard.vue";
import FeePendingCard from "@doctor/Components/FeePendingCard/FeePendingCard.vue";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import Header from "@shared/Components/Header/Header.vue";
import apiRequest from "@shared/utils/axios.js";
import {
convertDateTimeToDate,
convertDateToFormField, getCurrentDate,
getThisMonthStartDate
} from "@shared/utils/helpers.js";
import useVuelidate from "@vuelidate/core";
import { helpers, required } from "@vuelidate/validators";
import { storeToRefs } from "pinia";
import { onMounted, reactive, ref } from "vue";

export default {
    components: {
        Header,
        FeePaidCard,
        FeePendingCard
    },
    setup (){
        const activeTab = ref('pending');
        const period = ref("bulan ini");
        const layoutStore = useLayoutStore();
        const feeByTrxDateStore = useFeeByTrxDateStore();

        const filterForm = reactive({
            start_date: convertDateToFormField(new Date(getThisMonthStartDate())),
            end_date: getCurrentDate()
        });
        const rules = {
            start_date: {
                required: helpers.withMessage("Pilih tanggal awal", required),
            },
            end_date: {
                required: helpers.withMessage("Pilih tanggal akhir", required),
            },
        }
        const v$ = useVuelidate(rules, filterForm);

        const { pendings, payouts } = storeToRefs(feeByTrxDateStore);

        const filterSummaryFee = () => {
            layoutStore.isLoading = true;
            apiRequest.get(`/api/v1/doctor/fee/bytrxdate?start_date=${filterForm.start_date}&end_date=${filterForm.end_date}`).then((response) => {
                const data = response.data.data;
                feeByTrxDateStore.$patch({
                    pendings: data.pendings,
                    payouts: data.payouts
                });
            }).catch((error) => {
                if (error.response.status === 401) {
                    window.location.href = '/auth/login';
                }
                layoutStore.toggleErrorAlert(`${error.response.data.message}`);
            }).finally(() => {
                layoutStore.isLoading = false;
                period.value = `${convertDateTimeToDate(filterForm.start_date)} - ${convertDateTimeToDate(filterForm.end_date)}`;
            });
        }

        const updateActiveTab = (tab) => {
            activeTab.value = tab;
        }

        onMounted(() => {
            filterSummaryFee();
        });

        return{
            v$,
            filterForm,
            activeTab,
            period,
            layoutStore,
            feeByTrxDateStore,
            pendings,
            payouts,
            filterSummaryFee,
            updateActiveTab
        };
    }
}
</script>

<template>
    <Header :title="$t('fee.title')" :with-back-url="true" custom-heading-class="fs-4"></Header>
    <div class="filter-summary-fee mt-4 pt-8">
        <form class="d-flex col-gap-8 align-items-end" @submit.prevent="filterSummaryFee">
            <div :class="{ error: v$.start_date.$errors.length }">
                <label for="dari" class="fs-6 text-gray-700">{{ $t('fee.from') }}</label>
                <input type="date" name="dari" id="dari"
                       class="form-control mt-2" v-model="filterForm.start_date"
                       @input="v$.start_date.$touch()">
                <div
                    class="error mt-2 fs-6 fw-bold text-red-200"
                    v-for="error of v$.start_date.$errors"
                    :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>
            <div :class="{ error: v$.end_date.$errors.length }">
                <label for="hingga" class="fs-6 text-gray-700">{{ $t('fee.to') }}</label>
                <input type="date" name="hingga" id="hingga"
                       class="form-control mt-2" v-model="filterForm.end_date"
                       @input="v$.end_date.$touch()">
                <div
                    class="error mt-2 fs-6 fw-bold text-red-200"
                    v-for="error of v$.end_date.$errors"
                    :key="error.$uid"
                >
                    {{ error.$message }}
                </div>
            </div>
            <button type="submit" class="btn bg-green-700 d-flex align-items-center justify-content-center">
                <i class="bi bi-search icon-white"></i>
            </button>
        </form>
        <p class="error-filter mt-3 text-red-500 fs-6 fw-semibold"></p>
    </div>
    <section class="tab-periode">
        <p class="periode">{{ $t('fee.period') }} <span>{{ period }}</span></p>
        <div class="tab-summary-fee nav nav-pills nav-justified d-flex col-gap-20 mt-4">
            <button class="nav-link w-50 active" data-bs-toggle="pill" data-bs-target="#pending" role="tab"
                aria-controls="pending" aria-selected="true" @click="updateActiveTab('pending')">
                <p>{{ $t('fee.pending') }}</p>
            </button>

            <button class="nav-link w-50" data-bs-toggle="pill" data-bs-target="#terbayar" role="tab"
                aria-controls="terbayar" aria-selected="true" @click="updateActiveTab('terbayar')">
                <p>{{ $t('fee.payout') }}</p>
            </button>
        </div>
    </section>

    <div v-if="!layoutStore.isLoading" class="tab-content mt-4 px-4" id="tab-content">
        <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending" tabindex="0"
             v-show="activeTab === 'pending'">
            <div class="accordion d-flex flex-column rows-gap-20 mt-3" id="accordion">
                <div v-for="(pendingFee, index) in pendings">
                    <FeePendingCard :id="index.toString()" :registrationNo="pendingFee.registrationNo"
                        :medicalNo="pendingFee.medicalNo" :patientName="pendingFee.patientName"
                        :itemName="pendingFee.itemName" :qty="pendingFee.qty" :guarantorName="pendingFee.guarantorName"
                        :paymentPercentage="pendingFee.paymentPercentage" />
                </div>
            </div>
            <div class="text-center" v-if="pendings.length === 0">
                <img src="@resources/static/images/not-found.png" alt="Ilustrasi Tanpa Data" width="280" height="210" class="d-inline-block mt-3">
                <p class="mt-4 fw-semibold">{{ $t('fee.no_pending_payment') }}</p>
            </div>
        </div>

        <div class="tab-pane fade" id="terbayar" role="tabpanel" aria-labelledby="terbayar" tabindex="0"
             v-show="activeTab === 'terbayar'">
            <div class="accordion d-flex flex-column rows-gap-20 mt-3" id="accordion">
                <div v-for="(payoutFee, index) in payouts">
                    <FeePaidCard :id="index.toString()" :registrationNo="payoutFee.registrationNo"
                        :medicalNo="payoutFee.medicalNo" :patientName="payoutFee.patientName" :itemName="payoutFee.itemName"
                        :qty="payoutFee.qty" :guarantorName="payoutFee.guarantorName" :paidAmount="payoutFee.amount" />
                </div>
            </div>
            <div class="text-center" v-if="payouts.length === 0">
                <img src="@resources/static/images/not-found.png" alt="Ilustrasi Tanpa Data" width="280" height="210" class="d-inline-block mt-3" />
                <p class="mt-4 fw-semibold">{{ $t('fee.no_payout_payment') }}</p>
            </div>
        </div>

    </div>

    <div class="text-center mt-3" v-if="layoutStore.isLoading">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</template>
