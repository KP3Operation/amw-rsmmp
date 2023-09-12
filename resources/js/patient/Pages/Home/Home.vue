<script setup>
import LogoWhite from "@resources/static/images/logo-white.svg";
import DoctorPatient from "@resources/static/images/doctor-2-pasien.png";
import DoctorWhite from "@resources/static/icons/doctor-white.svg";
import UserFillWhite from "@resources/static/icons/users-fill-white.svg";
import { useAuthStore } from "@shared/+store/auth.store.js";
import { getUserFirstName } from "@shared/utils/helpers.js";
import ConsulCard from "@patient/Components/ConsulCard/ConsulCard.vue";

const authStore = useAuthStore();
</script>

<template>
    <div>
        <div class="header header-pasien">
            <router-link to="/home">
                <img :src="LogoWhite" alt="Logo Aviat" width="33" height="33">
            </router-link>
            <p>{{ $t('header.greeting') }}, <span v-text="getUserFirstName(authStore.userFullName)"></span></p>
        </div>

        <div class="mt-4 px-4 pt-8">
            <section class="rounded-3 d-flex col-gap-20 bg-blue-500 p-4 text-white">
                <img :src="DoctorPatient" alt="Ilustrasi" width="113" height="82">
                <div>
                    <p class="fw-bold">{{ $t('home.welcome') }}</p>
                    <p class="mt-2 fs-6">{{ $t('home.greeting') }}</p>
                </div>
            </section>

            <section class="mt-4">
                <h2 class="fs-3 fw-bold text-black">{{ $t('home.what_your_need') }}</h2>
                <div class="list-menu-homepage pasien mt-3">
                    <router-link to="/history" class="item riwayat-medis bg-blue-100">
                        <div class="icon icon-pasien bg-blue-500">
                            <i class="bi bi-clipboard-heart-fill"></i>
                        </div>

                        <p class="fw-bold mt-3 text-black">{{ $t('home.history') }}</p>
                    </router-link>
                    <router-link to="#" class="item doctor-schedule bg-green-100">
                        <div class="icon icon-pasien bg-green-500">
                            <img :src="DoctorWhite" alt="Icon" width="25" height="25">
                        </div>

                        <p class="fw-bold mt-3 text-black">{{ $t('home.doctor_schedule') }}</p>
                    </router-link>
                    <router-link to="#" class="item family-member">
                        <div class="icon icon-pasien">
                            <img :src="UserFillWhite" alt="Icon" width="25" height="25">
                        </div>
                        <p class="fw-bold mt-3 text-black">{{ $t('home.family_member') }}</p>
                    </router-link>
                    <router-link to="/appointment" class="item appointment">
                        <div class="icon icon-pasien">
                            <i class="bi bi-calendar2-date-fill"></i>
                        </div>
                        <p class="fw-bold mt-3 text-black">{{ $t('home.appointment') }}</p>
                    </router-link>
                </div>
            </section>
            <section class="mt-6">
                <h2 class="fs-3 fw-bold text-black">{{ $t('home.next_consult_schedule') }}</h2>
                <div id="jadwal-konsultasi" class="carousel slide mt-2" data-bs-interval="5000" data-bs-touch="true" data-bs-ride="carousel">
                    <div class="carousel-inner d-flex flex-nowrap col-gap-20">
                        <ConsulCard
                            id="s1"
                            doctor="dr. Surya Wijaya, Sp.THT-KL"
                            date="10/02/2023"
                            time="11:20"
                            unit="THT (Telinga, Hidung, Tenggorokan)" />
                        <ConsulCard
                            id="s2"
                            doctor="Dr. Ahmad Yusuf, Sp.JP"
                            date="08/02/2023"
                            time="08:00"
                            unit="Kardiologi" />
                    </div>
                    <div class="carousel-indicators position-static mt-2 mb-0">
                        <button type="button" data-bs-target="#jadwal-konsultasi" data-bs-slide-to="0" class="active"
                                aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#jadwal-konsultasi" data-bs-slide-to="1"
                                aria-label="Slide 2"></button>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>
