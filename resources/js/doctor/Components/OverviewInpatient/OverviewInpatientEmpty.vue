<script setup>
import NotFound from "@resources/static/images/not-found.png";
</script>
<template>
    <div class="mt-3 bg-blue-100 rounded-3 px-4 py-3 text-center">
        <img :src="NotFound" alt="Ilustrasi Tidak Ada Data" width="181" height="135" class="d-inline-block">
        <p class="mt-3 fw-bold">Anda Belum Memiliki Pasien Rawat Inap</p>
        <p class="mt-3 text-gray-700">Cek Secara Berkala Untuk <br />
            Melihat Pasien Rawat Inap Anda</p>
    </div>
</template>