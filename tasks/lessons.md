# Lessons

- Do not store long-lived project documentation in `tasks/todo.md`; it is a disposable checklist file that may be cleared.
- Store the project blueprint in `tasks/project-blueprint.md`.
- Always route shell reads/searches/status checks through `lean-ctx`; if quoting or compression gets in the way, adjust the `lean-ctx` command instead of dropping to plain shell.
- Do not bypass `lean-ctx` for ad hoc Node/PowerShell verification scripts; create a small checked task script if needed, then execute it through `lean-ctx`.
- Do not use the rich text `Editor` for announcement descriptions unless explicitly requested; use a clean text area so the form stays simple and readable.
- For modal data tables, cap the dialog with viewport-relative height and make the table body scroll internally so the requested row count and paginator stay visible.
- When rich text is requested, use PrimeVue `Editor` with a controlled custom toolbar and shared styling; avoid raw default toolbar rendering and avoid hand-rolled `contenteditable` editors.
- In Nuxt/Vite, PrimeVue `Editor` must be browser-verified as an initialized Quill editor, not just a rendered toolbar shell; prebundle `quill` and `quill-delta` when Vite reports missing default exports.
- When replacing a textarea with a rich editor, update required-field validation to strip HTML so empty editor markup is not treated as real content.
- For tall form dialogs, make the dialog shell viewport-bounded and the body the scroll container; do not rely on the browser viewport to recover clipped actions.
- Browser-test rich editors with oversized pasted images, not only normal text, because image intrinsic width can expose horizontal overflow missed by static checks.
- For frontend bug fixes, always verify the exact reported route in a browser after patching, including the viewport shown in the user screenshot.
- Even for cleanup and process-inspection commands, route shell through `lean-ctx -c`; do not drop to plain PowerShell after implementation.
- When fixing a missing homepage section, browser-check that each user-named label is visibly rendered, not just that the page loads.
- For top navigation, avoid boxing every menu item; use spacing, subtle separators, and understated hover/active states so the header stays calm.
- When restoring old homepage content, preserve the old descriptive text in the visible component, not only in the data model.
- Always use `lean-ctx` for shell reads, searches, and verification; if compressed output hides needed context, use a LeanCTX-compatible narrower read instead of raw PowerShell.
