// FUNGSI UNTUK MENAMPILKAN ALERT
function ShowAlert(flag, text, customColor) {
    let alert = document.querySelector(".alert");

    if (flag == "true") {
        alert.classList.add(customColor);
        alert.classList.remove("d-none");
    } else {
        alert.classList.add("alert-red");
        alert.classList.remove("alert-green", "d-none");
    }

    alert.querySelector("p").innerText = text;
    setTimeout(() => {
        alert.classList.add("d-none");
    }, 3000);
}
