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
