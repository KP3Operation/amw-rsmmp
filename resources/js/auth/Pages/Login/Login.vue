<template>
    <form class="mt-6">
        <div>
            <label for="no-hp">No. Handphone</label>
            <div class="input-group flex-nowrap mt-2">
                <span class="input-group-text">+62</span>
                <input type="tel" name="no-hp" id="no-hp" placeholder="8123940183020" class="form-control"
                    onkeyup="ValidasiHp()">
            </div>
            <p class="error mt-2 fs-6 fw-bold text-red-200"></p>
        </div>

        <div class="mt-4 d-flex flex-column">
            <button class="btn btn-blue-700-rounded">Masuk</button>
        </div>
    </form>

    <p class="mt-4 text-center">
        Belum Punya Akun? <a href="#" class="fw-bold text-decoration-none text-white">Daftar</a>
    </p>
</template>
