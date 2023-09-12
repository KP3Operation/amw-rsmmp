<script setup>
import { useAuthStore } from "@shared/+store/auth.store.js";
import { useLayoutStore } from "@shared/+store/layout.store.js";
import Form from "vform";
import { reactive } from 'vue';
import router from "@patient/router";
import SubmitButton from "@shared/Components/SubmitButton/SubmitButton.vue";
import Header from "@shared/Components/Header/Header.vue";

const layoutStore = useLayoutStore();
const authStore = useAuthStore();
const form = reactive(
    new Form({
        name: authStore.userFullName,
        gender: authStore.gender,
        birth_date: authStore.birthDate,
        email: authStore.userEmail
    })
);

const updateProfile = () => {
    layoutStore.isLoading = true;
    form.put(`/api/v1/me/${authStore.userId}`).then(() => {
        authStore.$patch({
            userFullName: form.name,
            gender: form.gender,
            birthDate: form.birth_date,
            userEmail: form.email,
        });
        layoutStore.toggleSuccessAlert(t('profile.edit.success'));
        router.push({ path: '/profile' });
    }).catch(() => {
        layoutStore.toggleSuccessAlert(t('profile.edit.failed'));
    }).finally(() => {
        layoutStore.isLoading = false;
    })
}
</script>
<template>
    <div>
        <Header :title="$t('profile.edit.title')" :with-back-url="true" page-name="ProfilePage"></Header>

        <div class="px-4 pt-7">
            <form @submit.prevent="updateProfile" @keydown="form.onKeydown($event)" class="d-flex flex-column rows-gap-16">
                <div>
                    <label for="name">{{ $t('profile.edit.full_name') }}</label>
                    <input type="text" name="name" id="name" placeholder="Muhammad John Doe" class="form-control mt-2"
                        v-model="form.name">
                    <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('name')"
                        v-html="form.errors.get('name')" />
                </div>

                <div>
                    <label for="gender">{{ $t('profile.edit.gender') }}<span
                            class="text-red-200 fw-semibold">*</span></label>
                    <div class="d-flex col-gap-20 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" :id="$t('profile.edit.male')"
                                :value="$t('profile.edit.male')" v-model="form.gender">
                            <label class="form-check-label" :for="$t('profile.edit.male')">
                                {{ $t('profile.edit.male') }}
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" :id="$t('profile.edit.female')"
                                :value="$t('profile.edit.female')" v-model="form.gender">
                            <label class="form-check-label" :for="$t('profile.edit.female')">
                                {{ $t('profile.edit.female') }}
                            </label>
                        </div>
                    </div>
                    <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('gender')"
                        v-html="form.errors.get('gender')" />
                </div>

                <div>
                    <label for="birth_date">{{ $t('profile.edit.birth_date') }} <span
                            class="text-red-200 fw-semibold">*</span></label>
                    <input type="date" name="birth_date" id="birth_date" class="form-control mt-2"
                        v-model="form.birth_date">
                    <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('birth_date')"
                        v-html="form.errors.get('birth_date')" />
                </div>

                <div>
                    <label for="email">{{ $t('profile.edit.email') }}</label>
                    <input type="email" name="email" id="email" placeholder="johndoe@example.com" class="form-control mt-2"
                        v-model="form.email">
                    <div class="error mt-2 fs-6 fw-bold text-red-200" v-if="form.errors.has('email')"
                        v-html="form.errors.get('email')" />
                </div>

                <SubmitButton :text="$t('profile.edit.update')" className="btn-blue-500-rounded" />

                <router-link to="/profile" class="text-blue-500 text-center text-decoration-none fw-semibold">{{
                    $t('profile.edit.cancel') }}</router-link>
            </form>
        </div>
    </div>
</template>
