<script>
import { useAuthStore } from "@shared/+store/auth.store.js";
import { onMounted, reactive, ref } from "vue";
import Form from "vform";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import {
    convertDateTimeToDate,
    getCurrentDate,
    getThisMonthStartDate,
    toIdrFormat
} from "@shared/utils/helpers.js";
import { storeToRefs } from "pinia";
import {helpers, maxValue, required} from "@vuelidate/validators";
import useVuelidate from "@vuelidate/core";

export default {
    components: {},
    setup() {
        const authStore = useAuthStore();
        const layoutStore = useLayoutStore();
        const { isLoading } = storeToRefs(layoutStore);
        const showFilter = ref(false);

        const filterForm = reactive({
                start_date: getThisMonthStartDate(),
                end_date: getCurrentDate()
        });
        const rules = {
            start_date: {
                required: helpers.withMessage("Pilih tanggal awal", required),
                maxValue: helpers.withMessage("Tanggal tidak valid", maxValue(filterForm.end_date))
            },
            end_date: {
                required: helpers.withMessage("Pilih tanggal akhir", required),
                minValue: helpers.withMessage("Tanggal tidak valid", maxValue(filterForm.start_date))
            },
        }
        const v$ = useVuelidate(rules, filterForm);

        const pending = ref(0);
        const payout = ref(0);
        const period = ref("bulan ini");

        const filterSummaryFee = () => {
            layoutStore.isLoading = true;
            filterForm.get('/api/v1/doctor/summary/fee').then((response) => {
                const data = response.data.data;
                payout.value = toIdrFormat(data.payout);
                pending.value = data.pending;
            }).catch((error) => {
                if (error.response.status === 401) {
                    window.location.href = '/auth/login';
                }
                layoutStore.toggleErrorAlert(`${error.response.data.message}`);
            }).finally(() => {
                layoutStore.isLoading = false;
                if (filterForm.start_date === getThisMonthStartDate() &&
                    filterForm.end_date === getCurrentDate()) {
                    period.value = "Bulan ini";
                } else {
                    period.value = `${convertDateTimeToDate(filterForm.start_date)} - ${convertDateTimeToDate(filterForm.end_date)}`;
                }
            });
        }

        onMounted(() => {
            filterSummaryFee();
        });

        return {
            authStore,
            layoutStore,
            isLoading,
            showFilter,
            filterForm,
            pending,
            payout,
            period,
            v$,
            filterSummaryFee
        };
    }
}

</script>
<template>
    <section class="mt-5">
        <div class="d-flex align-items-center justify-content-between col-gap-20 mb-3">
            <h2 class="fs-3 fw-bold text-black">{{ $t('home.overview_summary_fee') }}</h2>
            <button class="btn btn-filter-fee p-0" :class="showFilter ? 'bg-blue-500' : ''"
                @click="showFilter = !showFilter">
                <i class="bi bi-filter-right fs-2 icon-blue-500" :class="showFilter ? 'text-white' : 'text-blue-500'"></i>
            </button>
        </div>
        <div class="filter-homepage-summary-fee p-0 mb-3" :class="showFilter ? 'expand' : ''">
            <form class="d-flex col-gap-8 align-items-end" @submit.prevent="filterSummaryFee">
                <div :class="{ error: v$.start_date.$errors.length }">
                    <label for="dari" class="fs-6 text-gray-700">{{ $t('home.to') }}</label>
                    <input type="date" name="start_date" v-model="filterForm.start_date" id="dari"
                        class="form-control mt-2" @input="v$.start_date.$touch()">
                    <div
                            class="error mt-2 fs-6 fw-bold text-red-200"
                            v-for="error of v$.start_date.$errors"
                            :key="error.$uid"
                    >
                        {{ error.$message }}
                    </div>
                </div>

                <div :class="{ error: v$.start_date.$errors.length }">
                    <label for="hingga" class="fs-6 text-gray-700">{{ $t('home.from') }}</label>
                    <input type="date" name="end_date" v-model="filterForm.end_date" id="hingga"
                           class="form-control mt-2" @input="v$.end_date.$touch()">
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

            <p class="error-filter-date mt-3 text-red-500 fs-6 fw-semibold"></p>
        </div>
        <p class="periode mb-3">{{ $t('home.priod') }}: <span>{{ period }}</span></p>
        <div class="text-center mt-3" v-if="isLoading">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="summary-homepage pending" v-if="!isLoading">
            <p class="status">{{ $t('home.pending') }}</p>
            <p class="amount">{{ pending }} Item</p>
        </div>
        <div class="summary-homepage terbayar" v-if="!isLoading">
            <p class="status">{{ $t('home.payout') }}</p>
            <p class="amount">{{ payout }}</p>
        </div>
    </section>
</template>
