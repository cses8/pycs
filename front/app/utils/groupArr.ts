/**
 * Generates a random integer between min (inclusive) and max (inclusive).
 * @param {number} min The minimum possible value.
 * @param {number} max The maximum possible value.
 * @returns {number} A random integer within the specified range.
 */
const getRandomInt = (min: number, max: number): number => {
  // Ensure inputs are integers for safety, although validated earlier
  min = Math.ceil(min)
  max = Math.floor(max)
  // Formula for random integer in [min, max]
  return Math.floor(Math.random() * (max - min + 1)) + min
}

/**
 * Groups the elements of an array into subarrays.
 * If groupSizeRange is provided, subarrays will have sizes randomly chosen
 * between min and max (inclusive) specified in the range.
 * Otherwise, subarrays will have a fixed size specified by groupSize.
 *
 * @template T The type of elements in the array.
 * @param {T[]} array The input array to group.
 * @param {number} groupSize The desired fixed size of each group (subarray)
 * if groupSizeRange is not provided. Must be a positive integer in that case.
 * @param {[number, number]} [groupSizeRange] Optional. An array [min, max]
 * specifying the minimum and maximum size for randomly generated groups.
 * If provided, this overrides the groupSize parameter for determining chunk sizes.
 * Both min and max must be positive integers, and min <= max.
 * @returns {T[][]} A new array containing subarrays.
 * The last subarray may contain fewer elements if the remaining elements
 * are fewer than the determined chunk size.
 * @throws {Error} If groupSize (when used) is not a positive integer.
 * @throws {Error} If groupSizeRange is provided but invalid (not [min, max],
 * not positive integers, or min > max).
 */
export default <T>(
  array: T[],
  groupSize: number, // Kept for backward compatibility/fixed size usage
  groupSizeRange?: [number, number] // Optional range parameter
): T[][] => {
  // Input validation
  if (groupSizeRange) {
    // Validate groupSizeRange if provided
    if (
      !Array.isArray(groupSizeRange) ||
      groupSizeRange.length !== 2 ||
      !groupSizeRange.every(Number.isInteger)
    ) {
      throw new Error(
        'groupSizeRange must be an array of two integers: [min, max].'
      )
    }
    const [min, max] = groupSizeRange
    if (min <= 0 || max <= 0) {
      throw new Error(
        'Both min and max in groupSizeRange must be positive integers.'
      )
    }
    if (min > max) {
      throw new Error(
        'In groupSizeRange [min, max], min cannot be greater than max.'
      )
    }
  } else {
    // Validate groupSize only if groupSizeRange is NOT provided
    if (!Number.isInteger(groupSize) || groupSize <= 0) {
      throw new Error(
        'groupSize must be a positive integer when groupSizeRange is not provided.'
      )
    }
  }

  // Initialize the array to store the results
  const result: T[][] = []
  let currentIndex = 0

  // Use a while loop since the step size (chunkSize) can vary
  while (currentIndex < array.length) {
    let currentChunkSize: number

    if (groupSizeRange) {
      // Use random size between min and max from the range
      const [min, max] = groupSizeRange
      currentChunkSize = getRandomInt(min, max)
    } else {
      // Use the fixed groupSize
      currentChunkSize = groupSize
    }

    // Slice the array from the current index up to index + currentChunkSize
    // The slice method correctly handles cases where the end index goes beyond
    // the array length, taking only the available elements.
    const chunk = array.slice(currentIndex, currentIndex + currentChunkSize)

    // Only push if the chunk is not empty (handles initial empty array case too)
    // Although slice(n, n + positive) on an empty array or past the end returns [],
    // this check doesn't hurt.
    if (chunk.length > 0) {
      result.push(chunk)
    }

    // Advance the index by the size of the chunk we *intended* to take
    currentIndex += currentChunkSize
  }

  // Return the array of grouped subarrays
  return result
}
