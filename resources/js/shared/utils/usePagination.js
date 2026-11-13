import { computed, ref, watch } from "vue";

export const DEFAULT_PER_PAGE = 5;

/**
 * Memotong sebuah daftar menjadi halaman-halaman di sisi klien.
 *
 * Dipakai bersama <Pagination>: komponen itu hanya mengurus tampilan dan
 * nomor halaman, sedangkan pemotongan datanya di sini supaya tiap tab tidak
 * mengulang logika slice yang sama.
 *
 * @param {import('vue').Ref<Array>|(() => Array)} source daftar lengkap
 * @param {number} perPage jumlah item per halaman
 */
export function usePagination(source, perPage = DEFAULT_PER_PAGE) {
    const page = ref(1);

    const items = computed(() => {
        const value = typeof source === "function" ? source() : source.value;
        return Array.isArray(value) ? value : [];
    });

    const totalItems = computed(() => items.value.length);

    const totalPages = computed(() =>
        Math.max(1, Math.ceil(totalItems.value / perPage))
    );

    const paginatedItems = computed(() => {
        const start = (page.value - 1) * perPage;
        return items.value.slice(start, start + perPage);
    });

    // Daftarnya bisa berganti di luar kendali pagination: pindah tab, ganti
    // anggota keluarga, atau ganti tipe tanda vital. Tanpa reset ini pengguna
    // bisa terdampar di halaman yang pada daftar baru sudah tidak ada isinya.
    watch(items, () => {
        page.value = 1;
    });

    return { page, perPage, items, totalItems, totalPages, paginatedItems };
}
