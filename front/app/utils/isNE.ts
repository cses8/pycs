/**
 * Checks if a value is not equal to a specified threshold.
 * @param {number | string} value - The value to check.
 * @param {number | string} threshold - The threshold value to compare against.
 * @returns {boolean} True if the value is not equal to the threshold, otherwise false.
 */
export default (value: number | string, threshold: number | string): boolean => value !== threshold;
