function multiselect(jumlahData) {
    // FUNGSI UNTUK CHECKLIST FILTER
    let checkbox = document.querySelectorAll("#multiselect .form-check-input");
    let checkboxExceptSemua = document.querySelectorAll(
        "#multiselect .form-check-input:not(#semua)"
    );

    let checkedValue = [];
    let dropdownText = document.querySelector("#multiselect button span");
    let filterOption = document.querySelectorAll('input[name="filter"]');
    let checkboxSemua = document.querySelector("#multiselect input#semua");
    checkboxExceptSemua.forEach((element) => {
        checkedValue.push(element.value);
    });

    if (jumlahData === 0) {
        dropdownText.innerText = "Tidak Ada Data";
        document
            .querySelector("#multiselect button")
            .classList.add("disabled", "bg-gray-200", "text-black");
    }

    filterOption.forEach((element) => {
        element.addEventListener("change", () => {
            // JIKA CHECKBOX DI CEK
            if (element.checked) {
                if (checkboxSemua.checked === true) {
                    checkboxExceptSemua.forEach((el) => {
                        if (checkedValue.includes(el.value) === false) {
                            checkedValue.push(el.value);
                            el.checked = true;
                        }
                    });

                    if (checkboxSemua.classList.contains("off")) {
                        checkboxSemua.classList.add("on");
                        checkboxSemua.classList.remove("off");
                    }
                    dropdownText.innerText = `${checkedValue.length} Dipilih`;
                } else {
                    checkedValue.push(element.value);

                    if (checkedValue.length >= 3) {
                        dropdownText.innerText = `${checkedValue.length} Dipilih`;

                        if (
                            checkedValue.length === checkboxExceptSemua.length
                        ) {
                            checkboxSemua.checked = true;
                        }
                    } else {
                        dropdownText.innerText = checkedValue.toString();
                    }
                }
            } else {
                let uncheckedValue = element.value;
                let uncheckedPos = checkedValue.indexOf(uncheckedValue);

                checkedValue.splice(uncheckedPos, 1);
                // JIKA TIDAK ADA YANG DI CHECK
                if (checkedValue.length === 0) {
                    dropdownText.innerText = "Tidak Ada Yang Dipilih";
                    checkboxSemua.checked = false;
                } else {
                    if (
                        checkboxSemua.checked === false &&
                        checkboxSemua.classList.contains("on")
                    ) {
                        checkedValue = [];
                        dropdownText.innerText = "Tidak Ada Yang Dipilih";
                        checkboxSemua.classList.add("off");
                        checkboxSemua.classList.remove("on");
                        checkboxExceptSemua.forEach(
                            (element) => (element.checked = false)
                        );
                    } else {
                        checkboxSemua.checked = false;
                        checkboxSemua.classList.add("off");
                        checkboxSemua.classList.remove("on");

                        if (checkedValue.length >= 3) {
                            dropdownText.innerText = `${checkedValue.length} Dipilih`;
                        } else {
                            dropdownText.innerText = checkedValue.toString();
                        }
                    }
                }
            }
        });
    });
}
