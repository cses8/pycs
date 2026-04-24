/**
 * Generates a random integer between a specified minimum and maximum value (inclusive).
 *
 * @param {number} min - The minimum possible integer value (inclusive).
 * @param {number} max - The maximum possible integer value (inclusive).
 * @returns {number | null} A random integer within the range, or null if inputs are invalid.
 */
export default (min: number, max: number): number => {
  // Ensure inputs are valid numbers (TypeScript helps, but runtime check is still good)
  if (
    typeof min !== 'number' ||
    typeof max !== 'number' ||
    !Number.isFinite(min) ||
    !Number.isFinite(max)
  ) {
    console.error('Both min and max must be finite numbers.')
    return 0
  }

  // Ensure min is less than or equal to max
  if (min > max) {
    console.error('Minimum value cannot be greater than maximum value.')
    return 0
  }

  // Floor the inputs to handle potential decimals, although integers are expected.
  // Use ceil for min and floor for max to ensure inclusivity bounds.
  const lowerBound = Math.ceil(min)
  const upperBound = Math.floor(max)

  // Calculate the random integer
  // Math.random() returns a float between 0 (inclusive) and 1 (exclusive)
  // (upperBound - lowerBound + 1) gives the total number of integers in the range
  // Math.floor() rounds down to the nearest whole number
  const randomInt =
    Math.floor(Math.random() * (upperBound - lowerBound + 1)) + lowerBound

  return randomInt
}
