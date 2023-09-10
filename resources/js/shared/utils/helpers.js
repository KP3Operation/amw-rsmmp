export function getSecondsLeft (startDate, endDate) {
    const startTimestamp = startDate.getTime();
    const endTimestamp = endDate.getTime();
    const millisecondsDiff = endTimestamp - startTimestamp;
    return Math.floor(millisecondsDiff / 1000);
}
