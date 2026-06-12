# Project API Integration Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Render Featured Work projects in `index.php` from the Faylabs dashboard public API using server-side PHP.

**Architecture:** Add one focused PHP helper that fetches and normalizes projects from the dashboard API. Keep `index.php` responsible for page markup only, replacing hardcoded cards with escaped dynamic output and an empty state fallback.

**Tech Stack:** PHP, existing landing page HTML/CSS, Faylabs dashboard public JSON API.

---

## File Structure

- Create: `includes/projects.php`
  - Owns API URL config, safe JSON fetching, project normalization, escaping helpers, and URL generation.
- Modify: `index.php`
  - Requires the helper, fetches the first 6 projects, renders dynamic cards, empty state, and `projects.php` CTA.
- No CSS changes expected.
- No dashboard DB/config files are used.

---

### Task 1: Add project API helper

**Files:**
- Create: `includes/projects.php`

- [ ] **Step 1: Create helper directory if missing**

Run:

```bash
mkdir -p includes
```

Expected: `includes/` exists.

- [ ] **Step 2: Create `includes/projects.php`**

Write this complete file:

```php
<?php

const PROJECTS_API_URL = 'http://localhost/faydev/faylabs-dashboard/api/public/load-projects.php?offset=0';

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function fetchPublishedProjects(string $url = PROJECTS_API_URL): array
{
    $json = fetchJson($url);
    if ($json === null) {
        return [];
    }

    $payload = json_decode($json, true);
    if (!is_array($payload) || ($payload['success'] ?? false) !== true) {
        return [];
    }

    $projects = $payload['data']['projects'] ?? [];
    if (!is_array($projects)) {
        return [];
    }

    return array_values(array_filter(array_map('normalizeProject', $projects)));
}

function fetchJson(string $url): ?string
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => true,
        ]);

        $body = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if (is_string($body) && $status >= 200 && $status < 300) {
            return $body;
        }

        return null;
    }

    $context = stream_context_create([
        'http' => [
            'timeout' => 5,
            'ignore_errors' => true,
        ],
    ]);

    $body = @file_get_contents($url, false, $context);
    return is_string($body) ? $body : null;
}

function normalizeProject(array $project): ?array
{
    $title = trim((string) ($project['title'] ?? ''));
    $slug = trim((string) ($project['slug'] ?? ''));

    if ($title === '' || $slug === '') {
        return null;
    }

    return [
        'title' => $title,
        'slug' => $slug,
        'description' => trim((string) ($project['description'] ?? '')),
        'cover_image' => trim((string) ($project['cover_image'] ?? '')),
        'label' => trim((string) ($project['label'] ?? '')),
        'project_year' => trim((string) ($project['project_year'] ?? '')),
    ];
}

function projectDetailUrl(array $project): string
{
    return 'project.php?slug=' . rawurlencode($project['slug']);
}
```

- [ ] **Step 3: PHP syntax check**

Run:

```bash
php -l includes/projects.php
```

Expected:

```txt
No syntax errors detected in includes/projects.php
```

---

### Task 2: Wire helper into `index.php`

**Files:**
- Modify: `index.php:1`

- [ ] **Step 1: Replace first line require with helper + header requires**

Replace:

```php
<?php require __DIR__ . '/partials/header.php'; ?>
```

With:

```php
<?php
require __DIR__ . '/includes/projects.php';

$projects = fetchPublishedProjects();

require __DIR__ . '/partials/header.php';
?>
```

- [ ] **Step 2: PHP syntax check**

Run:

```bash
php -l index.php
```

Expected:

```txt
No syntax errors detected in index.php
```

---

### Task 3: Replace hardcoded Featured Work cards

**Files:**
- Modify: `index.php:40-99`

- [ ] **Step 1: Replace current `.showcase` hardcoded articles and CTA**

Replace the full block from:

```html
      <div class="showcase">
```

through:

```html
      <div class="showcase-cta">
        <a class="button secondary" href="#">Explore More Projects</a>
      </div>
```

With:

```php
      <div class="showcase">
        <?php if (!empty($projects)): ?>
          <?php foreach ($projects as $index => $project): ?>
            <?php $projectClass = $index % 2 === 0 ? 'project reverse reveal' : 'project reveal'; ?>
            <article class="<?= e($projectClass) ?>">
              <?php if ($project['cover_image'] !== ''): ?>
                <img class="project-shot" src="<?= e($project['cover_image']) ?>" alt="<?= e($project['title']) ?> project screenshot">
              <?php endif; ?>
              <div class="project-content">
                <h3><?= e($project['title']) ?></h3>
                <?php if ($project['description'] !== ''): ?>
                  <p><?= e($project['description']) ?></p>
                <?php endif; ?>
                <ul class="tags">
                  <?php if ($project['label'] !== ''): ?><li><?= e($project['label']) ?></li><?php endif; ?>
                  <?php if ($project['project_year'] !== ''): ?><li><?= e($project['project_year']) ?></li><?php endif; ?>
                </ul>
                <div class="project-links"><a href="<?= e(projectDetailUrl($project)) ?>">View Project</a></div>
              </div>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <article class="project-empty reveal">
            <div class="project-content">
              <h3>Projects will be available soon.</h3>
              <p>Published projects from the dashboard will appear here once they are ready.</p>
            </div>
          </article>
        <?php endif; ?>
      </div>
      <div class="showcase-cta">
        <a class="button secondary" href="projects.php">Explore More Projects</a>
      </div>
```

- [ ] **Step 2: PHP syntax check**

Run:

```bash
php -l index.php
```

Expected:

```txt
No syntax errors detected in index.php
```

---

### Task 4: Manual browser verification

**Files:**
- Verify: `index.php`

- [ ] **Step 1: Verify API is reachable**

Open:

```txt
http://localhost/faydev/faylabs-dashboard/api/public/load-projects.php?offset=0
```

Expected: JSON with `success: true` and `data.projects`.

- [ ] **Step 2: Verify landing page renders project cards**

Open:

```txt
http://localhost/faydev/faylabs-landing-page/
```

Expected:

- Featured Work shows dashboard project title, description, image, label, and year.
- `View Project` link points to `project.php?slug=visura` for the current sample project.
- `Explore More Projects` points to `projects.php`.

- [ ] **Step 3: Verify empty state behavior**

Temporarily change in `includes/projects.php`:

```php
const PROJECTS_API_URL = 'http://localhost/faydev/faylabs-dashboard/api/public/missing.php?offset=0';
```

Refresh landing page.

Expected:

- Featured Work shows `Projects will be available soon.`
- No PHP warning or API error details shown.

Restore:

```php
const PROJECTS_API_URL = 'http://localhost/faydev/faylabs-dashboard/api/public/load-projects.php?offset=0';
```

- [ ] **Step 4: Final syntax checks**

Run:

```bash
php -l includes/projects.php
php -l index.php
```

Expected both files report no syntax errors.

---

## Self-Review

Spec coverage:

- Server-side PHP fetch: Task 1 and Task 2.
- Dashboard public API only: Task 1.
- Exact API fields: Task 1 normalization and Task 3 rendering.
- Empty state fallback: Task 3 and Task 4.
- Detail link: Task 1 `projectDetailUrl()` and Task 3.
- Explore More link: Task 3.
- Escaping/security: Task 1 `e()` and Task 3 output.
- Verification: Task 4.

No placeholders remain. Function names are consistent across tasks.
