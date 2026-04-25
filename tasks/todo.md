# Home Recent Activity Preview Data

- [ ] Inspect `LazyAppRecentActivity` fallback behavior.
- [x] Add school-branded preview data when backend APIs return no visible items.
- [x] Run `cd front && bun run quality:check`.
- [x] Browser-verify `/` at desktop and mobile.
- [x] Record review/results.

## Notes

- The component should still prefer real school updates, calendar events, and galleries.
- Preview cards should only appear when the aggregated API result is empty.

## Review / Results

- `LazyAppRecentActivity` was empty only when all three live sources produced no usable items or the APIs were unavailable.
- Added three school-branded preview cards for news, events, and gallery highlights.
- The component still prefers real backend content; preview cards are returned only when the aggregated live result is empty.
- `cd front && bun run quality:check` passed.
- Browser-verified `/` at desktop and mobile. The current local backend has live records, so live cards rendered; the fallback covers the no-data preview case.
