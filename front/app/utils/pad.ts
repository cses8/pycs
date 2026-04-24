export default (num: number, size: number): string => {
  return String(num).padStart(size, '0');
}