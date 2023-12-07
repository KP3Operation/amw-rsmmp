import axios from "axios";
import {useAuthStore} from "@shared/+store/auth.store.js";

const plugins = {
    install(app) {
        app.config.globalProperties.$getUserFirstName = (fullName) => {
            const arrName = fullName.split(" ");
            if (arrName[0] === "dr." || arrName[0] === "drg.") {
                return arrName[1];
            }
            return arrName[0];
        },
            app.config.globalProperties.$convertDateTimeToDate = (datetime) => {
                if (datetime === "-") {
                    return "-";
                }

                const date = new Date(datetime);

                return date.toLocaleDateString("id-ID", {
                    year: "numeric",
                    month: "2-digit",
                    day: "2-digit",
                });
            },
            app.config.globalProperties.$getSecondsLeft = (startDate, endDate) => {
                const startTimestamp = startDate.getTime();
                const endTimestamp = endDate.getTime();
                const millisecondsDiff = endTimestamp - startTimestamp;
                return Math.floor(millisecondsDiff / 1000);
            },
            app.config.globalProperties.$calculateAge = (birthdate) => {
                if (birthdate === '-') {
                    return '-';
                }

                const birthdateObj = new Date(birthdate);
                const today = new Date();

                let age = today.getFullYear() - birthdateObj.getFullYear();
                const monthDiff = today.getMonth() - birthdateObj.getMonth();

                if (
                    monthDiff < 0 ||
                    (monthDiff === 0 && today.getDate() < birthdateObj.getDate())
                ) {
                    age--;
                }

                return age;
            },
            app.config.globalProperties.$convertDateTimeToDateTime = (datetime) => {
            if (datetime === "-") {
                return "-";
            }

            const date = new Date(datetime);

            return date.toLocaleTimeString("id-ID", {
                year: "numeric",
                month: "2-digit",
                day: "2-digit",
                hour: "numeric",
                minute: "numeric",
            });
        }
    }
};

/**
 * @param {KeyboardEvent} event
 */
export function onlyNumberInput(event) {
    const keysAllowed = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    const keyPressed = event.key;

    if (!keysAllowed.includes(keyPressed)) {
        event.preventDefault();
    }
}

/**
 * @param {Date} startDate
 * @param {Date} endDate
 * @returns {number}
 */
export function getSecondsLeft(startDate, endDate) {
    const startTimestamp = startDate.getTime();
    const endTimestamp = endDate.getTime();
    const millisecondsDiff = endTimestamp - startTimestamp;
    return Math.floor(millisecondsDiff / 1000);
}

/**
 * @param {Date|string} birthdate
 * @returns {number|string}
 */
export function calculateAge(birthdate) {

    if (birthdate === '-') {
        return '-';
    }

    const birthdateObj = new Date(birthdate);
    const today = new Date();

    let age = today.getFullYear() - birthdateObj.getFullYear();
    const monthDiff = today.getMonth() - birthdateObj.getMonth();

    if (
        monthDiff < 0 ||
        (monthDiff === 0 && today.getDate() < birthdateObj.getDate())
    ) {
        age--;
    }

    return age;
}

/**
 * @param {String} fullName
 * @returns {String}
 */
export function getUserFirstName(fullName) {
    const arrName = fullName.split(" ");
    if (arrName[0] === "dr." || arrName[0] === "drg.") {
        return arrName[1];
    }
    return arrName[0];
}

export function updateMyProfileStore() {
    const authStore = useAuthStore();
    axios
        .get(`/api/v1/me`)
        .then((response) => {
            authStore.updateUserData({
                userFullName: response.data.user.name,
                userEmail: response.data.user.email,
                userId: response.data.user.id,
                phoneNumber: response.data.user.phone_number
                    .toString()
                    .replace(`${import.meta.env.VITE_CALLING_CODE}`, ""),
                userRole: response.data.role,
            });
            authStore.updateUserPatientData({
                ssn: response.data.patient_data.ssn,
                patientId: response.data.patient_data.patient_id,
                birthDate: response.data.patient_data.birth_date,
                gender: response.data.patient_data.gender,
                medicalNo: response.data.patient_data.medical_no,
            });
        })
        .catch((error) => {
            if (error?.response?.status === 401) {
                authStore.$reset();
                window.location.href = `/auth/login`;
            }
        });
}

/**
 * @param {Date|string} datetime
 * @returns {String}
 */
export function convertDateTimeToDate(datetime) {
    if (datetime === "-") {
        return "-";
    }

    const date = new Date(datetime);

    return date.toLocaleDateString("id-ID", {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
    });
}

/**
 * @param {Date} datetime
 * @returns {String}
 */
export function convertDateToFormField(datetime) {
    const date = new Date(datetime);
    let d = (date.getDate() < 10 ? '0' : '' )+ date.getDate();
    let m = ((date.getMonth() + 1) < 10 ? '0' :'') + (date.getMonth() + 1);
    let y = date.getFullYear();
    return String(y + "-" + m + "-" + d);
}

/**
 * @returns {string}
 */
export function getThisMonthStartDate() {
    const currentDate = new Date();
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const startDate = new Date(year, month, 1);
    return startDate.toISOString().split('T')[0];
}

/**
 * @returns {string}
 */
export function getThisMonthEndDate() {
    const currentDate = new Date();
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const endDate = new Date(year, month + 1, 0);
    return endDate.toISOString().split('T')[0];
}

/**
 * @param {number} value
 * @returns {string}
 */
export function toIdrFormat(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
    }).format(value);
}


/**
 * @param {Date|string} datetime
 * @returns {String}
 */
export function convertDateTimeToDateTime(datetime) {
    if (datetime === "-") {
        return "-";
    }

    const date = new Date(datetime);

    return date.toLocaleTimeString("id-ID", {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "numeric",
        minute: "numeric",
    });
}

/**
 * @returns {string}
 */
export function getCurrentDate() {
    const currentDate = new Date();
    const year = currentDate.getFullYear();
    const month = String(currentDate.getMonth() + 1).padStart(2, '0');
    const day = String(currentDate.getDate()).padStart(2, '0');
    return year + '-' + month + '-' + day;
}


export default plugins;
