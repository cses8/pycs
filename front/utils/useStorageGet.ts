/* eslint-disable @typescript-eslint/no-explicit-any */

/**
 * Retrieves and parses a value from the browser's local storage. ⚙️
 *
 * If the stored value is a valid JSON string, it will be converted into its JavaScript object form.
 * Otherwise, the original string is returned, or a default value if no data exists.
 *
 * @param key - The key under which the data is stored.
 * @param defaultValue - A fallback value if the stored data is unavailable or not valid JSON. 🏷
 * 
 * @returns The parsed data or the fallback value, depending on validity. 🚀
 * 
 * @example
 * ```ts
 * // Example usage:
 * const settings = useStorageGet({ key: 'settings', defaultValue: { theme: 'light' } });
 * console.log(settings.theme); // 'light' if no valid JSON stored
 * ```
 */
export default ({ key = '', defaultValue = '' }: { key: string; defaultValue?: any }): any => {
	const entry = localStorage.getItem(key);
	return entry && isValidJSON(entry) ? JSON.parse(entry) : entry ?? defaultValue;
};