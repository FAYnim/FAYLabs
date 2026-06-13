# Theme Switcher — Design

**Date:** 2026-06-13
**Status:** Approved
**Scope:** `index.php`, `projects.php`, `project.php`

## Goal

Add a manual light/dark theme switcher to the FAYdev Labs landing page. Choice persists across reloads. No flash of wrong theme on first paint. Accessible, framework-free, fits the existing single-stylesheet + vanilla-JS architecture.

## Non-Goals (YAGNI)

- No third theme (sepia, high-contrast, etc.).
- No `prefers-color-scheme: dark` auto-detection as a primary mode.
- No per-component theme overrides.
- No CSS-in-JS, no build step, no new dependencies.
- No animated transitions on the toggle (reduced-motion friendly by default).

## Decisions (from brainstorm)

| Question | Decision |
|---|---|
| Scope | Light + Dark, manual toggle only |
| Persistence | `localStorage` |
| Placement | Header nav bar, right of menu links |
| Dark palette | Cool dark (slate/navy) |
| Toggle icon | Single FA button, sun/moon swap |
| FOUC | Inline blocking script in `<head>` |
| Switching approach | `data-theme` attribute on `<html>` |

## Architecture

- **State** lives in a single attribute: `data-theme="dark"` on `<html>`. Absent = light.
- **Persistence** uses `localStorage` key `faydev-theme`, values `"dark"` | `"light"`. Anything else → light.
- **Stylesheet** is still a single `assets/css/styles.css`. `:root` keeps the current light tokens. A new `:root[data-theme="dark"]` block re-declares the 12 color and shadow tokens in the cool dark palette (`--radius` and `--container` stay the same). Every component already reads `var(--token)`, so no other CSS changes.
- **HTML** gets one new `<button class="theme-toggle">` in `partials/header.php`, after the menu links. `<html>` gets `data-theme` set by an inline `<script>` in `<head>`, *before* the stylesheet `<link>`.
- **JS** lives in a new `assets/js/theme.js` (~30 lines). Inline `<head>` snippet (~6 lines) handles first-paint only. Existing `assets/js/script.js` is untouched.

## Components

### 1. Inline head script (`partials/header.php`, before stylesheet `<link>`)

```html
<script>
  (function(){
    try {
      var t = localStorage.getItem('faydev-theme');
      if (t === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
      }
    } catch (e) {}
  })();
</script>
```

Wrapped in `try/catch` so private-mode / disabled-storage visitors degrade to light.

### 2. Toggle button (`partials/header.php`)

```html
<button type="button" class="theme-toggle" aria-label="Toggle dark mode" aria-pressed="false">
  <i class="fa-solid fa-sun theme-icon theme-icon--sun" aria-hidden="true"></i>
  <i class="fa-solid fa-moon theme-icon theme-icon--moon" aria-hidden="true"></i>
</button>
```

CSS shows only the icon matching the current `data-theme`; the other is `display: none`.

### 3. `assets/js/theme.js`

Single `initTheme()` IIFE. Reads the (already-correct) `data-theme` attribute → sets `aria-pressed` + icon visibility on the button → wires the click handler. Handler flips state, writes storage, updates attribute, updates button.

Public surface (for future use / tests): none — internal module. If we ever need a programmatic API, add a tiny `window.FayTheme = { set, get, toggle }` later.

### 4. CSS additions (`assets/css/styles.css`)

Append:

```css
:root { color-scheme: light; }
:root[data-theme="dark"] { color-scheme: dark; }

:root[data-theme="dark"] {
  --bg: #0f172a;
  --surface: #111c33;
  --card: #14213d;
  --border: #1f2a44;
  --text: #e2e8f0;
  --muted: #94a3b8;
  --accent: #93c5fd;
  --accent-strong: #60a5fa;
  --accent-soft: #1e3a5f;
  --cream: #0b1224;
  --shadow: 0 24px 70px rgba(0, 0, 0, 0.4);
  --shadow-soft: 0 12px 36px rgba(0, 0, 0, 0.3);
  /* --radius and --container unchanged */
}

.theme-toggle {
  background: transparent;
  border: 1px solid var(--border);
  color: var(--text);
  width: 40px;
  height: 40px;
  border-radius: 999px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background 0.15s ease, border-color 0.15s ease;
}
.theme-toggle:hover { background: var(--surface); border-color: var(--muted); }
.theme-toggle:focus-visible { outline: 3px solid var(--accent); outline-offset: 2px; }
.theme-toggle .theme-icon { font-size: 1rem; }
:root[data-theme="dark"] .theme-icon--sun { display: inline; }
:root[data-theme="dark"] .theme-icon--moon { display: none; }
:root:not([data-theme="dark"]) .theme-icon--sun { display: none; }
:root:not([data-theme="dark"]) .theme-icon--moon { display: inline; }
```

(`project.css` and `projects.css` use the same custom properties, so they get the dark palette for free — no edits needed unless review finds a hardcoded color.)

## Data Flow

1. **First paint** (no JS yet): inline head script reads `localStorage['faydev-theme']`. If `"dark"`, sets `documentElement.dataset.theme = 'dark'` *before* `styles.css` parses → dark tokens apply on first render. No flash.
2. **`DOMContentLoaded`**: `theme.js` runs. Reads the (already-correct) attribute, sets `aria-pressed`, shows the matching icon.
3. **User clicks toggle**: handler computes new value (`'dark' → 'light'`, vice versa), writes `localStorage`, sets `data-theme`, updates `aria-pressed`, swaps icon.
4. **Subsequent visits**: back to step 1 — inline script is the source of truth for first paint, JS keeps the button in sync.
5. **Storage contract**: key `faydev-theme`, values `"dark"` | `"light"`. Any other value, `null`, or throwing access → fall through to light silently.

## Error Handling

| Failure | Handling |
|---|---|
| `localStorage` throws (private mode / disabled) | Both inline script and `theme.js` wrap access in `try/catch` → fall back to light. Toggle still flips for the session. |
| Stored value is garbage (e.g. `"true"`, `""`, hand-edited) | Whitelist: treat as `"light"` if not exactly `"dark"`. |
| JavaScript disabled | Inline script can't run → user stays on light. Toggle button renders but does nothing. Acceptable. |
| `data-theme` already set by something else (future server-side default) | `theme.js` reads DOM first on init, DOM wins. |
| Toggle button missing from DOM (partial include) | `theme.js` early-returns if `!document.querySelector('.theme-toggle')`. No console noise. |
| A token missing from the `[data-theme="dark"]` block | Inherits from `:root` (light value). UI never breaks; review will catch it. |

## Files Touched

- `partials/header.php` — add inline head script + toggle button + new `<script src="assets/js/theme.js" defer></script>`.
- `assets/css/styles.css` — append `:root[data-theme="dark"]` block + toggle styles + `color-scheme`.
- `assets/js/theme.js` — **new** file, ~30 lines.

Untouched: `index.php`, `projects.php`, `project.php`, `assets/js/script.js`, `partials/footer.php`, `assets/css/project.css`, `assets/css/projects.css` (unless review finds a hardcoded color that doesn't use a token).

## Testing

No test framework in this project. Manual checklist run before merging. Optional follow-up: a tiny Playwright smoke test.

**Manual checklist** (Chrome + Firefox + Safari, desktop + mobile viewport):

1. First visit, empty storage → page is light, toggle shows the moon icon.
2. Click toggle → switches to dark, icon → sun, `localStorage['faydev-theme'] === 'dark'`.
3. Reload (F5) → still dark, no flash of light.
4. Hard reload with DevTools "Disable cache" → still no flash.
5. Private/incognito window → toggle flips for the session, no console errors.
6. Navigate `index.php` → `projects.php` → `project.php` → theme consistent, button in the same place.
7. Tab to toggle → visible focus ring → Enter/Space toggles.
8. DevTools → set `localStorage` value to `"banana"` → reload → page is light.
9. DevTools → disable JavaScript → reload → page is light, button rendered but inert.
10. Lighthouse + axe DevTools → no new a11y/contrast warnings on either theme.
11. Mobile viewport (375px) → button does not overflow, tappable (≥ 44×44).
12. `prefers-reduced-motion: reduce` → no animation on the icon swap.

Done when: all 12 pass on each browser × viewport combo.

## Out of Scope (for follow-up ideas, not this spec)

- Animated transition between themes (`transition: background-color 0.2s` on body). Easy to add later.
- Sync toggle state across tabs via `storage` event.
- Per-section theme (e.g. dark hero, light body).
- Theme picker UI with preview swatches.
