/**
 * @param {Date} startDate
 * @param {Date} endDate
 * @returns {number}
 */
export function getSecondsLeft (startDate, endDate) {
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

    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdateObj.getDate())) {
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
    return arrName[0];
}
