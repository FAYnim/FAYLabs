# Contact Form Section — Design

**Date:** 2026-06-20
**Status:** Approved
**Scope:** `index.php`, `partials/header.php`, `assets/css/styles.css`, `assets/js/script.js`

## Goal

Add a calm, modern contact form section after the Building in Public section. The form helps visitors express interest in collaboration, project inquiries, hiring, or general networking while staying lightweight and framework-free.

## Non-Goals

- No backend submission.
- No email sending.
- No database/storage.
- No analytics/tracking.
- No new dependencies or build step.

## Decisions

| Topic | Decision |
|---|---|
| Placement | After `#public` section on the landing page |
| Layout | Compact contact card, two-column desktop layout |
| Purpose | All contact reasons via dropdown |
| Fields | Name, email, reason, message |
| Behavior | UI-only submit with local success message |
| Tone | Existing calm, modern, product-builder vibe |

## Architecture

The feature adds one new `#contact` section to `index.php`. It follows the existing section conventions: `.section`, `.container`, `.section-heading`, `.reveal`, CSS variables, responsive behavior, and dark-mode token usage.

The navigation can add a `Contact` anchor linking to `#contact`, placed after `Journey` before the theme toggle. The footer remains unchanged unless implementation finds a strong consistency reason to add the link there.

Styling belongs in `assets/css/styles.css`, near existing section/card styles. Behavior belongs in `assets/js/script.js`, alongside existing vanilla JavaScript interactions.

## Components

### Contact Section

- Eyebrow: `CONTACT`
- Heading: `Let’s build something useful.`
- Supporting copy: short invitation for collaborations, project inquiries, hiring, and networking.
- Layout: left intro panel and right form card on desktop; stacked layout on mobile.

### Contact Form

Fields:

1. `name` — text input, required.
2. `email` — email input, required.
3. `reason` — select, required.
   - Collaboration
   - Project Inquiry
   - Hiring
   - General Networking
4. `message` — textarea, required.

CTA: `Send Message`.

The form should use semantic labels, clear focus states, accessible required fields, and sufficient spacing for mobile touch targets.

## Data Flow

1. User fills the form.
2. Browser validates required fields and email format.
3. JavaScript intercepts submit.
4. If valid, a local success message appears.
5. Form resets.
6. No data leaves the browser.

## Error Handling

Use native HTML5 validation for empty fields and invalid email. JavaScript should call the browser validation flow instead of duplicating error UI. If JavaScript fails, the form should not send data because it has no real backend target.

Success message should be visible near the form and understandable by screen readers, using an appropriate live region.

## Visual Design

The section should feel like a natural continuation after the social cards: calm, spacious, rounded, and premium. Use existing tokens for surface, border, text, muted text, accent, shadows, and radius. Avoid heavy gradients or visual noise.

Desktop layout: two columns. Left side explains the invitation and expected contact reasons. Right side contains the form in a card.

Mobile layout: stacked, full-width form, comfortable input spacing.

## Accessibility

- Every input has a visible label.
- Required fields use native `required` attributes.
- Email uses `type="email"`.
- Submit feedback uses `aria-live="polite"`.
- Keyboard focus follows existing `:focus-visible` styling.
- Color contrast must work in light and dark themes.

## Testing

Manual checks:

- Desktop layout after `#public`.
- Mobile stacked layout.
- Light and dark theme styling.
- Header `Contact` anchor scrolls correctly.
- Keyboard tab order and focus states.
- Empty required fields trigger browser validation.
- Invalid email triggers browser validation.
- Valid submit shows success message and resets fields.
- No console errors.

If available, run `php -l index.php` and lint edited PHP partials.

## Implementation Boundaries

Keep the feature isolated to the landing page and existing frontend assets. Do not introduce backend handlers, storage, external services, or new libraries in this iteration.
