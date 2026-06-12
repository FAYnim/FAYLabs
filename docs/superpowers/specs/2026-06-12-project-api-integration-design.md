# Server-side Project API Integration Design

## Goal

Replace the hardcoded Featured Work project cards in `index.php` with published project data from the Faylabs dashboard public API.

## Source API

Landing page will fetch:

```txt
GET http://localhost/faydev/faylabs-dashboard/api/public/load-projects.php?offset=0
```

Expected response:

```json
{
  "success": true,
  "data": {
    "projects": [
      {
        "id": 1,
        "title": "Visura",
        "slug": "visura",
        "description": "Visura - Cinematic Portfolios",
        "cover_image": "https://...",
        "label": "AI",
        "project_year": "2026",
        "published_at": "2026-06-12 11:17:10"
      }
    ],
    "total": 1,
    "offset": 6,
    "has_more": false
  }
}
```

## Architecture

Use PHP server-side fetching in the landing page app. The landing page remains decoupled from the dashboard database and reads only the public API.

`index.php` will call a small helper responsible for:

- Requesting the dashboard public API.
- Decoding JSON safely.
- Returning a normalized project list and status metadata.
- Failing gracefully when the API is unavailable, invalid, or empty.

## Rendering

The Featured Work section will render up to 6 projects from `data.projects`.

Card mapping:

- title: `title`
- slug: `slug`
- description: `description`
- image: `cover_image`
- tag: `label`
- optional year tag: `project_year`

Each project card keeps the existing `.project`, `.project-shot`, `.project-content`, `.tags`, and `.project-links` structure so current CSS continues to work.

Reverse layout alternates by index:

- even index: `project reverse reveal`
- odd index: `project reveal`

## Links

Project CTA:

```txt
project.php?slug={slug}
```

The `Explore More Projects` button links to:

```txt
projects.php
```

No demo/source links are shown in the index card design.

## Empty and Error States

If the API returns no projects or the request fails, the Featured Work section remains visible and shows an empty state message instead of hardcoded placeholders.

Suggested copy:

```txt
Projects will be available soon.
```

No dashboard/database error details should be exposed to visitors.

## Security

All API-rendered fields must be escaped before output:

- text: `htmlspecialchars`
- image URL: escaped attribute output
- slug link: URL-encoded query value

No secrets or dashboard config files are read by the landing page.

## Testing

Manual checks:

1. API available with published projects renders real cards.
2. API unavailable renders empty state.
3. Empty API response renders empty state.
4. Project links point to `project.php?slug=...`.
5. `Explore More Projects` points to `projects.php`.
6. Existing layout remains responsive.
