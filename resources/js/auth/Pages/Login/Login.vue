<script setup>
const callingCode = import.meta.env.VITE_APP_CALLING_CODE;

</script>

<template>
    <div>
        <h2 class="mt-6 fs-3 fw-bold">{{ $t('login_to_account') }}</h2>
        <form class="mt-6">
            <div>
                <label for="no-hp">{{ $t('phone_number') }}</label>
                <div class="input-group flex-nowrap mt-2">
                    <span class="input-group-text">{{ callingCode }}</span>
                    <input type="tel" name="phone_number" id="no-hp" placeholder="8123940183020" class="form-control">
                </div>
                <p class="error mt-2 fs-6 fw-bold text-red-200"></p>
            </div>

            <div class="mt-4 d-flex flex-column">
                <button class="btn btn-blue-700-rounded" type="button">{{ $t('login') }}</button>
            </div>
        </form>

        <p class="mt-4 text-center">
            {{ $t('does_not_have_account') }} <a href="#" class="fw-bold text-decoration-none text-white">{{ $t('register')
            }}</a>
        </p>
    </div>
</template>
