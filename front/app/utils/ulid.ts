/**
 * Generates a ULID (Universally Unique Lexicographically Sortable Identifier).
 * @returns {string} The generated ULID.
 */

export default (): string => {
  const time = new Date().getTime().toString(36).toUpperCase().padStart(10, '0')
  const random = new Uint8Array(16)

  if (globalThis.crypto?.getRandomValues) {
    globalThis.crypto.getRandomValues(random)
  }
  else {
    for (let i = 0; i < random.length; i++) {
      random[i] = Math.floor(Math.random() * 256)
    }
  }

  const chars = '0123456789ABCDEFGHJKMNPQRSTVWXYZ' // Crockford's Base32 alphabet
  let randomPart = ''

  for (let i = 0; i < 16; i++) {
    const randomVal = random[i] ?? 0
    randomPart += chars[randomVal % 32]
  }

  return time + randomPart
}
