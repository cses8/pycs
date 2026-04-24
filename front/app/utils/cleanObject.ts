type AnyObject = { [key: string]: unknown };

/**
 * Recursively removes all properties with `null` or `undefined` values from an object.
 *
 * @param {AnyObject} obj - The object to be cleaned.
 * @returns {AnyObject} A new object with all `null` and `undefined` values removed.
 *
 * @example
 * ```typescript
 * const dirtyObject = { a: 1, b: null, c: undefined, d: { e: 2, f: null } };
 * const clean = cleanObject(dirtyObject);
 * console.log(clean); // Output: { a: 1, d: { e: 2 } }
 * ```
 *
 * @remarks
 * This function is useful for sanitizing data before sending it to an API or storing it in a database.
 * It ensures that only meaningful values are retained in the object.
 *
 * @see {@link isObject} for the helper function used to check if a value is an object.
 */
/**
 * 🧹✨ Recursively removes all properties with `null` or `undefined` values from an object.
 * 
 * @param {AnyObject} obj - The object to be cleaned. 🧼
 * @returns {AnyObject} A new object with all `null` and `undefined` values removed. 🚫
 * 
 * @example
 * const dirtyObject = { a: 1, b: null, c: { d: 2, e: undefined } }; 🧽
 * const clean = cleanObject(dirtyObject); 🧴
 * console.log(clean); // Output: { a: 1, c: { d: 2 } } 🎉
 * 
 * @remarks
 * This function is useful for sanitizing data before sending it to an API or storing it in a database. 🗄️
 * It ensures that only meaningful values are retained in the object. ✅
 * 
 * @see {@link isObject} for the helper function used to check if a value is an object. 🔍
 */
const cleanObject = (obj: AnyObject): AnyObject => {
	const result: AnyObject = {};
	Object.keys(obj).forEach(key => {
		if (obj[key] !== null && obj[key] !== undefined) {
			result[key] = isObject(obj[key]) ? cleanObject(obj[key] as AnyObject) : obj[key];
		}
	});
	return result;
};

export default cleanObject;
