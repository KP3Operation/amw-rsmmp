// TODO: Need to add additional validation in the frontend side

export function validatePhonrNumber(value) {
    let regExp = /^\d*[.]?\d*$/;
    if (regExp.test(value) === false) {
       return "No. Handphone Hanya Boleh Diisi Angka";
    } else if (value.length > 1 && value.length < 10) {
       return "No. Handphone Kurang Dari 10 Digit";
    } else if (value.length > 13) {
       return "No. Handphone Lebih Dari 13 Digit";
    } else if (value.length == 0) {
       return " No. Handphone Wajib Diisi";
    }

    return true;
}

export function validateSSN(value) {
    if (value.length !== 16) {
        return "Panjang NIK Harus 16 Digit";
    } else if (value.length === 0) {
        return "NIK Wajib Diisi";
    }

    return true;
}
