<script setup>
import { computed } from "vue";
import { DEFAULT_PER_PAGE } from "@shared/utils/usePagination.js";

const props = defineProps({
    /** Halaman aktif, dihitung mulai dari 1. */
    modelValue: {
        type: Number,
        required: true,
    },
    /** Jumlah seluruh item, bukan jumlah item di halaman ini. */
    totalItems: {
        type: Number,
        required: true,
    },
    /**
     * Harus sama dengan yang dipakai untuk memotong datanya. Defaultnya
     * diambil dari usePagination supaya keduanya tidak bisa berbeda diam-diam.
     */
    perPage: {
        type: Number,
        default: DEFAULT_PER_PAGE,
    },
    /**
     * Banyaknya tombol angka yang ditampilkan sekaligus. Lebar frame aplikasi
     * hanya 393px dan barisnya masih berisi dua panah serta dua elipsis, jadi
     * tiga tombol angka adalah batas yang masih menyisakan jarak ke tepi kartu.
     */
    maxVisiblePages: {
        type: Number,
        default: 3,
    },
    /** Dibaca pembaca layar, mis. "Navigasi halaman hasil lab". */
    navLabel: {
        type: String,
        default: "Navigasi halaman",
    },
});

const emit = defineEmits(["update:modelValue"]);

const totalPages = computed(() =>
    Math.max(1, Math.ceil(props.totalItems / props.perPage))
);

const currentPage = computed(() =>
    Math.min(Math.max(props.modelValue, 1), totalPages.value)
);

const firstItemOnPage = computed(() =>
    props.totalItems === 0 ? 0 : (currentPage.value - 1) * props.perPage + 1
);

const lastItemOnPage = computed(() =>
    Math.min(currentPage.value * props.perPage, props.totalItems)
);

/**
 * Jendela nomor halaman di sekitar halaman aktif, dijepit supaya selalu
 * menampilkan sebanyak mungkin tombol tanpa keluar dari rentang yang ada.
 */
const visiblePages = computed(() => {
    const total = totalPages.value;
    const size = Math.min(props.maxVisiblePages, total);
    let start = currentPage.value - Math.floor(size / 2);

    start = Math.max(1, Math.min(start, total - size + 1));

    return Array.from({ length: size }, (_, index) => start + index);
});

const hasGapBefore = computed(() => visiblePages.value[0] > 1);
const hasGapAfter = computed(
    () => visiblePages.value[visiblePages.value.length - 1] < totalPages.value
);

const goTo = (page) => {
    const target = Math.min(Math.max(page, 1), totalPages.value);
    if (target !== currentPage.value) {
        emit("update:modelValue", target);
    }
};
</script>

<template>
    <!--
        Harus <div role="navigation">, bukan <nav>: style.css menata SETIAP
        elemen <nav> sebagai bottom navigation bar aplikasi (position: fixed,
        bottom: 0, latar putih, z-index 999), sehingga pager ini akan menempel
        di dasar layar dan menutupi isi halaman.
    -->
    <div
        v-if="totalPages > 1"
        class="app-pagination mt-6"
        role="navigation"
        :aria-label="navLabel"
    >
        <ul class="pagination justify-content-center align-items-center mb-0">
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
                <button
                    type="button"
                    class="page-link app-pagination-arrow"
                    :disabled="currentPage === 1"
                    aria-label="Halaman sebelumnya"
                    @click="goTo(currentPage - 1)"
                >
                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                </button>
            </li>

            <li v-if="hasGapBefore" class="page-item disabled" aria-hidden="true">
                <span class="page-link app-pagination-gap">…</span>
            </li>

            <li
                v-for="page in visiblePages"
                :key="page"
                class="page-item"
                :class="{ active: page === currentPage }"
            >
                <button
                    type="button"
                    class="page-link app-pagination-number"
                    :aria-label="`Halaman ${page}`"
                    :aria-current="page === currentPage ? 'page' : undefined"
                    @click="goTo(page)"
                >
                    {{ page }}
                </button>
            </li>

            <li v-if="hasGapAfter" class="page-item disabled" aria-hidden="true">
                <span class="page-link app-pagination-gap">…</span>
            </li>

            <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                <button
                    type="button"
                    class="page-link app-pagination-arrow"
                    :disabled="currentPage === totalPages"
                    aria-label="Halaman berikutnya"
                    @click="goTo(currentPage + 1)"
                >
                    <i class="bi bi-chevron-right" aria-hidden="true"></i>
                </button>
            </li>
        </ul>

        <p class="app-pagination-summary text-center text-gray-600 mt-2 mb-0">
            {{ firstItemOnPage }}–{{ lastItemOnPage }} dari {{ totalItems }}
        </p>
    </div>
</template>

<style scoped>
.app-pagination {
    width: 100%;
    /* Menyamakan jarak ke tepi dengan kartu riwayat di atasnya, supaya barisnya
       tidak terlihat menempel ke garis tepi frame aplikasi. */
    padding: 0 16px;
}

.app-pagination .pagination {
    flex-wrap: nowrap;
    column-gap: 4px;
}

.app-pagination .page-link {
    min-width: 36px;
    height: 36px;
    padding: 0 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
}

/* Elipsis tidak perlu selebar tombol; dia hanya penanda ada halaman terlewat. */
.app-pagination .app-pagination-gap {
    min-width: 20px;
    padding: 0;
}

.app-pagination .page-item.active .page-link {
    background: #0d6efd;
    border-color: #0d6efd;
}

.app-pagination .app-pagination-gap {
    border: 0;
    background: transparent;
}

.app-pagination-summary {
    font-size: 14px;
}
</style>
