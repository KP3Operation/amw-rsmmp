import axios from "axios";
import {useAuthStore} from "@shared/+store/auth.store.js";

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
 * @param {Date} birthdate
 * @returns {number}
 */
export function calculateAge(birthdate) {
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
            authStore.$patch({
                userFullName: response.data.user.name,
                userEmail: response.data.user.email,
                userId: response.data.user.id,
                phoneNumber: response.data.user.phone_number.replace(
                    `${import.meta.env.VITE_CALLING_CODE}`,
                    ""
                ),
                userRole: response.data.role,

                // patient data
                ssn: response.data.patient_data.ssn,
                patientId: response.data.patient_data.patient_id,
                birthDate: response.data.patient_data.birth_date,
                gender: response.data.patient_data.gender,
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
 * @param {Date} datetime
 * @returns {String}
 */
export function convertDateTimeToDate(datetime) {
    const date = new Date(datetime);
    const formattedDate = date.toLocaleDateString("id-ID", {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
    });
    return formattedDate;
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
    let x = String(y+"-"+m+"-"+d);
    return x;
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
