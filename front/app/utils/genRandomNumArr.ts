/**
 * Generates an array of unique random integers within a specified range.
 * If the requested length exceeds the number of unique integers possible
 * within the range, the array will contain all unique integers in that range.
 *
 * @param {number} length The desired length of the array. Must be a non-negative integer.
 * @param {number} min The minimum possible value for the random numbers (inclusive).
 * @param {number} max The maximum possible value for the random numbers (inclusive).
 * @returns {number[]} An array of unique random integers.
 * @throws {Error} If length is negative or not an integer.
 * @throws {Error} If min is greater than max.
 */
export default (length: number, min: number, max: number): number[] => {
  // Input validation for length
  if (length < 0 || !Number.isInteger(length)) {
    throw new Error('Array length must be a non-negative integer.')
  }

  // Input validation for range
  if (min > max) {
    throw new Error('Minimum value cannot be greater than maximum value.')
  }

  // Ensure min and max are treated as integers
  const minInt = Math.ceil(min)
  const maxInt = Math.floor(max)

  // Calculate the total number of unique integers possible in the range
  const rangeSize = maxInt - minInt + 1

  // If the range is invalid (e.g., min=0.1, max=0.9 -> minInt=1, maxInt=0)
  // or if length is 0, return an empty array.
  if (rangeSize <= 0 || length === 0) {
    // Check if length > 0 despite impossible range for a clearer error
    if (length > 0 && rangeSize <= 0) {
      console.warn(
        `Cannot generate ${length} numbers: The integer range from ${minInt} to ${maxInt} is empty or invalid.`
      )
    }
    return []
  }

  // Determine the actual number of unique elements to generate.
  // Cap the length at the maximum number of unique integers available in the range.
  const actualLength = Math.min(length, rangeSize)

  // Use a Set to store unique numbers efficiently
  const uniqueNumbers = new Set<number>()

  // Generate unique random numbers until the set reaches the actualLength
  while (uniqueNumbers.size < actualLength) {
    // Generate a random integer between minInt and maxInt (inclusive)
    const randomNumber = Math.floor(Math.random() * rangeSize) + minInt
    // Add to the set (duplicates are automatically ignored)
    uniqueNumbers.add(randomNumber)
  }

  // Convert the Set back to an array
  return Array.from(uniqueNumbers)
}
