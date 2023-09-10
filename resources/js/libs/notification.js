function notifikasi(notifikasiName) {
    // SET LOCALSTORAGE UNTUK NOTIF

    let getNotifikasi = JSON.parse(localStorage.getItem(notifikasiName));
    let listClickId = [];

    if (getNotifikasi !== null) {
        if (getNotifikasi.length > 0) {
            getNotifikasi.forEach((getId) => {
                let card = document.getElementById(getId);
                let element = card.querySelector("new-data");

                if (card.classList.contains("new-data-bg")) {
                    card.classList.remove("new-data-bg");
                    card.classList.add("bg-blue-100", "border-blue-200");
                } else if (element !== null) {
                    document
                        .querySelector(`#${getId} .new-data`)
                        .classList.add("invisible");
                }
            });
        }
    }

    // HAPUS TANDA DOT JIKA DATA BARU DIKLIK
    let item = document.querySelectorAll(".item");
    item.forEach((element) => {
        element.addEventListener("click", () => {
            if (
                element.querySelector(".new-data") !== null ||
                element.classList.contains("new-data-bg")
            ) {
                let id = element.getAttribute("id");

                if (getNotifikasi === null) {
                    if (listClickId.includes(id) === false) {
                        listClickId.push(id);
                        localStorage.setItem(
                            notifikasiName,
                            JSON.stringify(listClickId)
                        );
                    }
                } else {
                    if (getNotifikasi.includes(id) === false) {
                        getNotifikasi.push(id);
                        localStorage.setItem(
                            notifikasiName,
                            JSON.stringify(getNotifikasi)
                        );
                    }
                }

                if (element.classList.contains("new-data-bg")) {
                    element.classList.remove("new-data-bg");
                    element.classList.add("bg-blue-100", "border-blue-200");
                } else {
                    element
                        .querySelector(".new-data")
                        .classList.add("invisible");
                }
            }
        });
    });
}
