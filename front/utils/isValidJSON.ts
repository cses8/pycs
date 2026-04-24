/**
 * Checks if a string is a valid JSON.
 * @param jsonString The string to check.
 * @returns True if the string is valid JSON, otherwise false.
 */
export default (jsonString: any): boolean => {
  try {
    JSON.parse(jsonString);
    return true;
  } catch (error) {
    return false;
  }
}
