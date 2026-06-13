# Theme Switcher Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add a manual light/dark theme switcher to the FAYdev Labs landing page with `localStorage` persistence, no flash of wrong theme, and a single Font Awesome toggle in the header nav.

**Architecture:** Single attribute (`data-theme` on `<html>`) holds the state. A new `:root[data-theme="dark"]` CSS block re-declares 12 color/shadow tokens for the cool dark palette. An inline `<head>` script sets the attribute before the stylesheet parses (FOUC prevention). A new `assets/js/theme.js` keeps the toggle button in sync, persists to `localStorage`, and handles click events. The existing `script.js` and all PHP page files are untouched.

**Tech Stack:** PHP 8 (partials), vanilla JavaScript (no framework, no build), CSS custom properties, Font Awesome 6.5, `localStorage` Web API. No new dependencies. No test framework — verification is the 12-point manual checklist from the spec.

**Reference spec:** `docs/superpowers/specs/2026-06-13-theme-switcher-design.md`

**Local dev server:** start with `php -S localhost:8000` from the project root, then open `http://localhost:8000`.

---

## File Structure

| File | Responsibility | Action |
|---|---|---|
| `assets/css/styles.css` | All theme tokens + new `[data-theme="dark"]` overrides + toggle button styles + icon visibility rules | Modify (append) |
| `partials/header.php` | Inline head script (FOUC), toggle button HTML, theme.js script tag | Modify |
| `assets/js/theme.js` | State init from DOM, click handler, `localStorage` persistence, `aria-pressed` + icon sync, early-return safety | Create |

Untouched: `index.php`, `projects.php`, `project.php`, `partials/footer.php`, `assets/js/script.js`, `assets/css/project.css`, `assets/css/projects.css`, `includes/*`, `docs/PRD.md`, `README.md`.

---

### Task 1: Append dark theme tokens + toggle button CSS

**Files:**
- Modify: `assets/css/styles.css` (append to end of file)

- [ ] **Step 1: Verify the file is currently in a known state**

Run: `tail -5 assets/css/styles.css`
Expected: the last 5 lines are some closing CSS (e.g. footer styles, no trailing `}` missing). The file is 1080 lines. If your copy differs, read the last 20 lines with `tail -20 assets/css/styles.css` to confirm there is no `data-theme` block already.

- [ ] **Step 2: Append the dark theme CSS block**

Open `assets/css/styles.css` in your editor. Go to the very end of the file. Add a single blank line, then paste this block exactly:

```css
/* ---------- Theme switcher ---------- */
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
.theme-toggle .theme-icon { font-size: 1rem; line-height: 1; }

:root[data-theme="dark"] .theme-icon--sun { display: inline; }
:root[data-theme="dark"] .theme-icon--moon { display: none; }
:root:not([data-theme="dark"]) .theme-icon--sun { display: none; }
:root:not([data-theme="dark"]) .theme-icon--moon { display: inline; }
```

- [ ] **Step 3: Verify the file saved correctly**

Run: `wc -l assets/css/styles.css && tail -3 assets/css/styles.css`
Expected: line count went up by ~33 (was 1080), last 3 lines are the two icon visibility rules and a trailing blank line. No truncation, no syntax-error markers.

- [ ] **Step 4: Sanity-test the dark block via a quick browser check**

Start the PHP server if not already running: `php -S localhost:8000`
Open in browser: `http://localhost:8000`
Open DevTools → Console, paste:

```js
document.documentElement.setAttribute('data-theme', 'dark');
getComputedStyle(document.body).backgroundColor
```

Expected: a color in the `rgb(15, 23, 42)` family (your dark `--bg`). The page itself stays light because the button isn't wired up yet — the rule is just to confirm the CSS block parses and applies.

Then run: `document.documentElement.removeAttribute('data-theme');` to reset.

- [ ] **Step 5: Commit**

```bash
git add assets/css/styles.css
git commit -m "feat(theme): add dark theme tokens and toggle button styles"
```

---

### Task 2: Add inline head script + toggle button + script tag to header.php

**Files:**
- Modify: `partials/header.php`

- [ ] **Step 1: Read the current header**

Run: `cat partials/header.php`
Expected: 39 lines, starting with `<!DOCTYPE html>`, ending with `</header>`. This is the only file you need to read before editing.

- [ ] **Step 2: Insert the inline head script**

In `partials/header.php`, find the existing line:

```html
  <link rel="stylesheet" href="assets/css/styles.css">
```

(the stylesheet link, line 21 in the reference copy).

Add the inline script **immediately before** that line. The block to insert is:

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

Final ordering inside `<head>` must be: meta tags → favicons → preconnects → Google Fonts → Font Awesome → **inline theme script** → `styles.css`. The inline script must be **before** the stylesheet link so the attribute is set before the browser parses CSS.

- [ ] **Step 3: Add the toggle button inside the nav menu**

Find this block in the same file (around line 32–37):

```html
      <div class="nav-menu" id="nav-menu">
        <a href="#work">Work</a>
        <a href="#build">Build</a>
        <a href="#about">About</a>
        <a href="#public">Journey</a>
      </div>
```

Add the toggle button as a new child of `nav-menu`, **after** the four `<a>` links. The full block becomes:

```html
      <div class="nav-menu" id="nav-menu">
        <a href="#work">Work</a>
        <a href="#build">Build</a>
        <a href="#about">About</a>
        <a href="#public">Journey</a>
        <button type="button" class="theme-toggle" aria-label="Toggle dark mode" aria-pressed="false">
          <i class="fa-solid fa-sun theme-icon theme-icon--sun" aria-hidden="true"></i>
          <i class="fa-solid fa-moon theme-icon theme-icon--moon" aria-hidden="true"></i>
        </button>
      </div>
```

- [ ] **Step 4: Add the theme.js script tag**

Find this line near the bottom of the file (currently nothing — `header.php` ends at `</header>` and the scripts are loaded in `footer.php`). To keep the inline-FOUC pattern simple, load `theme.js` directly from `header.php` so it runs as early as practical.

Add the script tag **immediately after the closing `</header>` tag** (still inside `<body>`):

```html
  <script src="assets/js/theme.js" defer></script>
```

(That file does not exist yet — that's fine, the browser will 404 silently until Task 3 creates it. We're not blocking Task 2's commit on Task 3; they can be merged in either order or as a single commit if you prefer.)

- [ ] **Step 5: Verify the HTML**

Run: `cat partials/header.php`
Expected:
- `<script>` block (inline IIFE) appears once, just before the stylesheet `<link>`.
- The toggle button is the 5th child of `<div class="nav-menu">`, after the four `<a>` tags.
- `<script src="assets/js/theme.js" defer></script>` appears after `</header>`.

No other lines should be changed.

- [ ] **Step 6: Commit**

```bash
git add partials/header.php
git commit -m "feat(theme): add inline FOUC script, toggle button, and theme.js tag"
```

---

### Task 3: Create assets/js/theme.js

**Files:**
- Create: `assets/js/theme.js`

- [ ] **Step 1: Create the file with the full implementation**

Create `assets/js/theme.js` (no `package.json` exists — this is a plain script file loaded via `<script src=...>`). Paste this exact content:

```js
(function () {
  'use strict';

  var STORAGE_KEY = 'faydev-theme';
  var button = document.querySelector('.theme-toggle');
  if (!button) return;

  function readStoredTheme() {
    try {
      var t = localStorage.getItem(STORAGE_KEY);
      return t === 'dark' || t === 'light' ? t : 'light';
    } catch (e) {
      return 'light';
    }
  }

  function writeStoredTheme(value) {
    try {
      localStorage.setItem(STORAGE_KEY, value);
    } catch (e) {
      /* storage disabled — toggle still works for the session */
    }
  }

  function currentTheme() {
    return document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
  }

  function syncButton(theme) {
    var isDark = theme === 'dark';
    button.setAttribute('aria-pressed', String(isDark));
    button.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
  }

  function applyTheme(theme) {
    if (theme === 'dark') {
      document.documentElement.setAttribute('data-theme', 'dark');
    } else {
      document.documentElement.removeAttribute('data-theme');
    }
    syncButton(theme);
  }

  function toggle() {
    var next = currentTheme() === 'dark' ? 'light' : 'dark';
    applyTheme(next);
    writeStoredTheme(next);
  }

  // Reconcile button with whatever state the inline head script produced.
  // DOM wins (covers any future server-side default), then ensure storage is in sync.
  var initial = currentTheme();
  if (readStoredTheme() !== initial) {
    writeStoredTheme(initial);
  }
  syncButton(initial);

  button.addEventListener('click', toggle);
})();
```

- [ ] **Step 2: Sanity-check syntax with Node**

Run: `node --check assets/js/theme.js`
Expected: prints nothing and exits 0. If you don't have Node installed, skip this step and rely on Step 3's browser check.

- [ ] **Step 3: Browser smoke test**

Start the PHP server if not running: `php -S localhost:8000`
Open `http://localhost:8000`.

In DevTools Console, run each of these and confirm the expected output:

```js
document.documentElement.getAttribute('data-theme')
// → null  (light, first visit)
document.querySelector('.theme-toggle').getAttribute('aria-pressed')
// → "false"
```

Click the toggle in the page. Then in Console:

```js
document.documentElement.getAttribute('data-theme')
// → "dark"
document.querySelector('.theme-toggle').getAttribute('aria-pressed')
// → "true"
localStorage.getItem('faydev-theme')
// → "dark"
```

Reload the page. Immediately in Console (no waiting):

```js
document.documentElement.getAttribute('data-theme')
// → "dark"   (proves the inline head script ran before styles.css)
```

Click the toggle again to return to light. Reload. Confirm `data-theme` is `null` and `aria-pressed` is `"false"`.

- [ ] **Step 4: Edge-case checks**

In DevTools Console, run:

```js
localStorage.setItem('faydev-theme', 'banana');
location.reload();
```

After reload:

```js
document.documentElement.getAttribute('data-theme')
// → null   (whitelist rejected garbage → light)
```

Clean up:

```js
localStorage.removeItem('faydev-theme');
```

- [ ] **Step 5: Commit**

```bash
git add assets/js/theme.js
git commit -m "feat(theme): add theme.js with state, persistence, and click handler"
```

---

### Task 4: End-to-end manual verification across pages

**Files:** none modified — verification only.

- [ ] **Step 1: Run the 12-point checklist**

Start the PHP server: `php -S localhost:8000`

For each item, mark it pass/fail. Test in Chrome (desktop + 375px mobile) and at least one other browser (Firefox or Safari).

1. First visit, empty storage → page is light, toggle shows the moon icon. **Pass / Fail**
2. Click toggle → switches to dark, icon → sun, `localStorage['faydev-theme'] === 'dark'`. **Pass / Fail**
3. Reload (F5) → still dark, no flash of light. **Pass / Fail**
4. Hard reload with DevTools "Disable cache" → still no flash. **Pass / Fail**
5. Open in private/incognito window → toggle flips for the session, no console errors. **Pass / Fail**
6. Navigate `index.php` → `projects.php` → `project.php` → theme consistent, button in the same place on all three. **Pass / Fail**
7. Tab key reaches the toggle → visible focus ring (3px accent outline) → Enter/Space toggles. **Pass / Fail**
8. DevTools → `localStorage.setItem('faydev-theme', 'banana')` → reload → page is light. **Pass / Fail**
9. DevTools → disable JavaScript → reload → page is light, toggle button rendered but inert. **Pass / Fail**
10. Lighthouse + axe DevTools → no new a11y/contrast warnings on either theme. **Pass / Fail**
11. Mobile viewport (375px) → button does not overflow the nav, tappable (≥ 44×44 hit target). **Pass / Fail**
12. DevTools → Rendering → "Emulate CSS media feature prefers-reduced-motion: reduce" → no animation on the icon swap. **Pass / Fail**

- [ ] **Step 2: If any item failed, fix and re-verify**

Common issues and where to look:
- **Item 1 fails (toggle missing or both icons shown)**: re-check Task 2 Step 3 — the button is inside `.nav-menu` after the four `<a>` tags. The CSS in Task 1 hides one icon per `:root[data-theme]` state.
- **Item 3 or 4 fails (flash)**: the inline script in Task 2 Step 2 must be **before** the `<link rel="stylesheet">`. Re-check ordering.
- **Item 5 fails (console errors in private mode)**: Task 3 wraps `localStorage` access in `try/catch`. Re-check.
- **Item 6 fails (theme differs across pages)**: all three pages use `partials/header.php` — if one page doesn't, the toggle still works but the state lives in localStorage so it should still match. Check that the page's `<head>` includes the partial or that the partial is included at the top of the page body.
- **Item 7 fails (no focus ring)**: `.theme-toggle:focus-visible` in Task 1 sets the outline. If missing, check that the CSS block was actually appended (not added above an overriding rule).
- **Item 8 fails (garbage not rejected)**: the whitelist check in `readStoredTheme()` only returns `'dark'` or `'light'`. The init code in Task 3 reconciles storage with the DOM on load, so garbage gets overwritten.
- **Item 10 fails (contrast warning)**: dark mode tokens use `#e2e8f0` on `#0f172a` for body text (contrast ratio ~14:1, AAA). If a warning appears, it's likely a third-party asset (Font Awesome icon colors) or a `project.css` / `projects.css` hardcoded color — fix by routing through a token.
- **Item 11 fails (overflow)**: the nav uses flexbox; the button is 40px square. If it overflows on mobile, the issue is the menu (`.nav-menu`) layout, not the button. Confirm Task 1's `.theme-toggle` width/height are 40px and the button sits inside `.nav-menu` so the existing mobile menu CSS handles it.

- [ ] **Step 3: Clean up verification state**

Run in DevTools Console on each page after testing:

```js
localStorage.removeItem('faydev-theme');
location.reload();
```

(Not strictly necessary — leaving `dark` stored is a valid state — but keeps the dev environment tidy.)

- [ ] **Step 4: Final commit (only if Step 2 required fixes)**

If you changed anything during Step 2, commit it:

```bash
git add -A
git commit -m "fix(theme): address issues found in manual verification"
```

If Step 2 passed clean, there's nothing to commit.

- [ ] **Step 5: Confirm the spec is satisfied**

Cross-check each section of `docs/superpowers/specs/2026-06-13-theme-switcher-design.md`:
- **Architecture** — `data-theme` on `<html>`, 12 tokens re-declared, single stylesheet. ✓ Tasks 1, 2, 3.
- **Components** — inline head script (Task 2), toggle button (Task 2), `theme.js` (Task 3), CSS additions (Task 1). ✓
- **Data Flow** — first paint via inline script, JS keeps button in sync, click handler updates everything. ✓ Tasks 2, 3.
- **Error Handling** — `try/catch` around `localStorage` in both script and JS, whitelist on read, early-return when button missing, DOM-wins reconciliation, `color-scheme` set. ✓ Tasks 1, 2, 3.
- **Testing** — 12 manual items. ✓ Task 4.
- **Files Touched** — only the three files in the file-structure table were modified. ✓ Verify with `git diff --stat HEAD~4..HEAD` (four commits: Tasks 1, 2, 3, optional fix).

Done.
