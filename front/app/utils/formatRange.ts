/**
 * Creates a formatted date range string from two input date strings using Day.js.
 *
 * @param dateStr1 - The start date string in "MM-DD-YYYY" format.
 * @param dateStr2 - The end date string in "MM-DD-YYYY" format.
 * @returns The formatted date range string, or an error message string if issues occur.
 */
export default (dateStr1: string, dateStr2: string): string => {
  const dayjs = useDayjs()

  const inputFormat = 'MM-DD-YYYY'

  // Parse the input date strings into Day.js objects
  // The 'true' argument enables strict parsing for the custom format
  const d1 = dayjs(dateStr1, inputFormat, true)
  const d2 = dayjs(dateStr2, inputFormat, true)

  // Validate if the dates were parsed correctly
  if (!d1.isValid() || !d2.isValid()) {
    const invalidDates: string[] = []
    if (!d1.isValid()) invalidDates.push(`"${dateStr1}"`)
    if (!d2.isValid()) invalidDates.push(`"${dateStr2}"`)
    const errorMessage = `Invalid date string(s) provided: ${invalidDates.join(
      ', '
    )}. Expected format "MM-DD-YYYY".`
    console.error(errorMessage)
    return `Error: ${errorMessage}`
  }

  // Extract year, month index, day, and month name for both dates
  const year1: number = d1.year()
  const month1_idx: number = d1.month() // 0-indexed (0 for Jan)
  const day1: number = d1.date()
  const monthName1: string = d1.format('MMM') // Abbreviated month name (e.g., "Jan")

  const year2: number = d2.year()
  const month2_idx: number = d2.month()
  const day2_val: number = d2.date()
  const monthName2: string = d2.format('MMM')
  const day2Formatted: string = d2.format('DD') // Day with leading zero (e.g., "01")

  // Case 4: Different years
  // Example: Dec 31 2025 - Jan 01 2026
  if (year1 !== year2) {
    return `${monthName1} ${day1} ${year1} - ${monthName2} ${day2Formatted} ${year2}`
  }
  // From this point, year1 is the same as year2. We'll just use year1.

  // Case 3: Different months, same year
  // Example: Jan 31 - Feb 01, 2025
  if (month1_idx !== month2_idx) {
    return `${monthName1} ${day1} - ${monthName2} ${day2Formatted}, ${year1}`
  }
  // From this point, year1 === year2 AND month1_idx === month2_idx. We'll use monthName1 and year1.

  // Case 2: Same day, same month, same year
  // Example: Jan 31, 2025
  if (day1 === day2_val) {
    return `${monthName1} ${day1}, ${year1}`
  }

  // Case 1: Same month, same year, different days
  // Example: Jan 1-2, 2025
  // No leading zeros for days in this range format as per example.
  return `${monthName1} ${day1}-${day2_val}, ${year1}`
}
