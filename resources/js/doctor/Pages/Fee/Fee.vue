<script setup>
import Header from "@shared/Components/Header/Header.vue";
import FeePaidCard from "@doctor/Components/FeePaidCard/FeePaidCard.vue";
import FeePendingCard from "@doctor/Components/FeePendingCard/FeePendingCard.vue";
import {onMounted, reactive, ref} from "vue";
import Form from "vform";
import {
    convertDateTimeToDate,
    convertDateToFormField,
    getThisMonthEndDate,
    getThisMonthStartDate,
    toIdrFormat
} from "@shared/utils/helpers.js";
import {useLayoutStore} from "@shared/+store/layout.store.js";
import {useFeeByTrxDateStore} from "@doctor/+store/fee-by-trx-date.store.js";
import {storeToRefs} from "pinia";

const period = ref("bulan ini");
const layoutStore = useLayoutStore();
const feeByTrxDateStore = useFeeByTrxDateStore();
const filterForm = reactive(
    new Form({
        start_date: convertDateToFormField(new Date(getThisMonthStartDate())),
        end_date: convertDateToFormField(new Date(getThisMonthEndDate()))
    })
);

const { pendings, payouts } = storeToRefs(feeByTrxDateStore);

const filterSummaryFee = () => {
    layoutStore.isLoading = true;
    filterForm.get('/api/v1/doctor/fee/bytrxdate').then((response) => {
        period.value = `${convertDateTimeToDate(filterForm.start_date)} - ${convertDateTimeToDate(filterForm.start_date)}`;
        const data = response.data.data;
        feeByTrxDateStore.$patch({
            pendings: data.pendings,
            payouts: data.payouts
        });
    }).catch((error) => {
        layoutStore.toggleErrorAlert(`${error.response.data.message}`);
    }).finally(() => {
        layoutStore.isLoading = false;
    });
}

onMounted(() => {
    filterSummaryFee();
});
</script>

<template>
    <div>
        <Header :title="$t('fee.title')" :with-back-url="true" custom-heading-class="fs-4"></Header>

        <div class="filter-summary-fee mt-4 pt-8">
            <form class="d-flex col-gap-8 align-items-end" @submit.prevent="filterSummaryFee" @keydown="filterForm.onKeydown($event)">
                <div>
                    <label for="dari" class="fs-6 text-gray-700">Dari</label>
                    <input type="date" name="dari" id="dari" class="form-control mt-2" v-model="filterForm.start_date">
                    <small class="error mt-2 fs-6 fw-bold text-red-200" v-if="filterForm.errors.has('start_date')"
                           v-html="filterForm.errors.get('start_date')"></small>
                </div>

                <div>
                    <label for="hingga" class="fs-6 text-gray-700">Hingga</label>
                    <input type="date" name="hingga" id="hingga" class="form-control mt-2" v-model="filterForm.end_date">
                    <small class="error mt-2 fs-6 fw-bold text-red-200" v-if="filterForm.errors.has('start_date')"
                           v-html="filterForm.errors.get('start_date')"></small>
                </div>

                <button type="submit" class="btn bg-green-700 d-flex align-items-center justify-content-center">
                    <i class="bi bi-search icon-white"></i>
                </button>
            </form>

            <p class="error-filter mt-3 text-red-500 fs-6 fw-semibold"></p>
        </div>
        <section class="tab-periode mt-4">
            <p class="periode">Periode: <span>{{ period }}</span></p>

            <div class="tab-summary-fee nav nav-pills nav-justified d-flex col-gap-20 mt-4">
                <button class="nav-link w-50 active" data-bs-toggle="pill" data-bs-target="#pending" role="tab"
                        aria-controls="pending" aria-selected="true">
                    <p>Pending</p>
                </button>

                <button class="nav-link w-50" data-bs-toggle="pill" data-bs-target="#terbayar" role="tab"
                        aria-controls="terbayar" aria-selected="true">

                    <p>Terbayar</p>
                </button>
            </div>
        </section>

        <div v-if="!layoutStore.isLoading" class="tab-content mt-4 px-4" id="tab-content">
            <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending" tabindex="0">
                <div class="accordion d-flex flex-column rows-gap-20 mt-3" id="accordion">

                    <div v-for="(pendingFee, index) in pendings">
                        <FeePendingCard
                            :id="index.toString()"
                            :registrationNo="pendingFee.RegistrationNo"
                            :medicalNo="pendingFee.MedicalNo"
                            :patientName="pendingFee.PatientName"
                            :itemName="pendingFee.ItemName"
                            :qty="pendingFee.Qty"
                            :guarantorName="pendingFee.GuarantorName"
                            :paymentPercentage="pendingFee.PaymentPercentage" />
                    </div>

                </div>
            </div>

            <div class="tab-pane fade" id="terbayar" role="tabpanel" aria-labelledby="terbayar" tabindex="0">
                <div class="accordion d-flex flex-column rows-gap-20 mt-3" id="accordion">

                    <div v-for="(payoutFee, index) in payouts">
                        <!--  FIXME: need to use paid amount-->
                        <FeePaidCard
                            :id="index.toString()"
                            :registrationNo="payoutFee.RegistrationNo"
                            :medicalNo="payoutFee.MedicalNo"
                            :patientName="payoutFee.PatientName"
                            :itemName="payoutFee.ItemName"
                            :qty="payoutFee.Qty"
                            :guarantorName="payoutFee.GuarantorName"
                            :paidAmount="payoutFee.PaymentPercentage" />
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-3" v-if="layoutStore.isLoading">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</template>
