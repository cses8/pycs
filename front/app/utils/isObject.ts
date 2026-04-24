/**
 * 🧐 Checks if the given value is an object.
 *
 * @param obj - The value to check. This can be of any type.
 * @returns `true` if the value is an object and not `null` or an array, otherwise `false`.
 * 
 * This function performs the following checks:
 * 1. ✅ It checks if the type of the value is 'object'.
 * 2. 🚫 It ensures that the value is not `null`.
 * 3. 🚫 It ensures that the value is not an array.
 * 
 * If all these conditions are met, the function returns `true`, indicating that the value is a plain object.
 * Otherwise, it returns `false`.
 */
export default (obj: unknown): boolean => {
	return typeof obj === 'object' && 
		obj !== null && 
		!Array.isArray(obj);
}