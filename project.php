<?php
require __DIR__ . '/includes/projects.php';
require __DIR__ . '/includes/Markdown.php';

$slug    = trim((string) ($_GET['slug'] ?? ''));
$project = $slug !== '' ? fetchProjectBySlug($slug) : null;

if ($project === null) {
    http_response_code(404);
}

$pageTitle       = $project !== null
    ? (($project['seo_title'] !== '' ? $project['seo_title'] : $project['title']) . ' | FAY — Product Builder & Full-Stack Developer')
    : 'Project not found | FAY — Product Builder & Full-Stack Developer';

$pageDescription = $project !== null
    ? ($project['seo_description'] !== '' ? $project['seo_description'] : $project['description'])
    : 'The project you are looking for could not be found.';

$ogImage = $project['cover_image'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= e($pageDescription) ?>">
  <meta name="keywords" content="project, <?= e($project['title'] ?? '') ?>, product builder, full stack developer, portfolio">
  <meta property="og:title" content="<?= e($pageTitle) ?>">
  <meta property="og:description" content="<?= e($pageDescription) ?>">
  <meta property="og:type" content="article">
  <?php if ($ogImage !== ''): ?>
    <meta property="og:image" content="<?= e($ogImage) ?>">
    <meta name="twitter:image" content="<?= e($ogImage) ?>">
  <?php endif; ?>
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= e($pageTitle) ?>">
  <meta name="twitter:description" content="<?= e($pageDescription) ?>">
  <title><?= e($pageTitle) ?></title>
  <link rel="canonical" href="project.php<?= $project !== null ? '?slug=' . e(rawurlencode($project['slug'])) : '' ?>">
  <link rel="icon" href="assets/favicon/favicon.ico" sizes="any">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
  <link rel="manifest" href="assets/favicon/site.webmanifest">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;800&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="assets/css/styles.css">
  <link rel="stylesheet" href="assets/css/project.css">
</head>
<body>
  <a class="skip-link" href="#main">Skip to main content</a>

  <header class="site-header" aria-label="Site header">
    <nav class="nav container">
      <a class="brand" href="index.php" aria-label="FAYdev Labs home">FAYdev Labs</a>
      <?php if ($project !== null): ?>
        <a class="nav-back" href="projects.php">
          <i class="fa-solid fa-arrow-left"></i> Back to Projects
        </a>
      <?php endif; ?>
    </nav>
  </header>

  <main id="main">

    <?php if ($project === null): ?>
      <section class="container project-not-found">
        <a class="page-back" href="projects.php">
          <i class="fa-solid fa-arrow-left"></i> Back to Projects
        </a>
        <p class="eyebrow">404 — Not Found</p>
        <h1>This project doesn't exist.</h1>
        <p class="project-lead">The link may be broken, or the project has been removed. Try browsing the full list instead.</p>
        <div class="hero-actions">
          <a class="button primary" href="projects.php">Browse all projects</a>
          <a class="button secondary" href="index.php">Back to Home</a>
        </div>
      </section>

    <?php else: ?>

      <article class="project-detail">

        <header class="project-detail-hero container">
          <ul class="tags">
            <?php if ($project['label'] !== ''): ?><li><?= e($project['label']) ?></li><?php endif; ?>
            <?php if ($project['project_year'] !== ''): ?><li><?= e($project['project_year']) ?></li><?php endif; ?>
          </ul>

          <h1><?= e($project['title']) ?></h1>

          <?php if ($project['description'] !== ''): ?>
            <p class="project-lead"><?= e($project['description']) ?></p>
          <?php endif; ?>

          <div class="project-detail-actions">
            <?php if ($project['demo_url'] !== ''): ?>
              <a class="button primary" href="<?= e($project['demo_url']) ?>" target="_blank" rel="noopener noreferrer">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Live Demo
              </a>
            <?php endif; ?>
            <?php if ($project['github_url'] !== ''): ?>
              <a class="button secondary" href="<?= e($project['github_url']) ?>" target="_blank" rel="noopener noreferrer">
                <i class="fa-brands fa-github"></i> View Code
              </a>
            <?php endif; ?>
            <a class="button secondary" href="projects.php">
              <i class="fa-solid fa-grid-2"></i> All Projects
            </a>
          </div>
        </header>

        <?php if ($project['cover_image'] !== ''): ?>
          <div class="project-detail-cover container">
            <img src="<?= e($project['cover_image']) ?>" alt="<?= e($project['title']) ?> cover image" loading="eager" decoding="async">
          </div>
        <?php endif; ?>

        <div class="project-detail-body container">
          <div class="project-article">
            <?php if ($project['content'] !== ''): ?>
              <div class="prose">
                <?= markdownToHtml($project['content']) ?>
              </div>
            <?php else: ?>
              <p class="prose-empty">No write-up published yet. Check back soon — the story behind this project is on the way.</p>
            <?php endif; ?>
          </div>

          <aside class="project-sidebar" aria-label="Project details">
            <?php if (!empty($project['tech_stack'])): ?>
              <section class="sidebar-card">
                <h3>Tech Stack</h3>
                <ul class="tech-list">
                  <?php foreach ($project['tech_stack'] as $tech): ?>
                    <li><?= e($tech) ?></li>
                  <?php endforeach; ?>
                </ul>
              </section>
            <?php endif; ?>

            <section class="sidebar-card">
              <h3>Details</h3>
              <dl class="meta-list">
                <?php if ($project['label'] !== ''): ?>
                  <div><dt>Type</dt><dd><?= e($project['label']) ?></dd></div>
                <?php endif; ?>
                <?php if ($project['project_year'] !== ''): ?>
                  <div><dt>Year</dt><dd><?= e($project['project_year']) ?></dd></div>
                <?php endif; ?>
                <?php if ($project['published_label'] !== ''): ?>
                  <div><dt>Published</dt><dd><?= e($project['published_label']) ?></dd></div>
                <?php endif; ?>
                <div><dt>Views</dt><dd><?= number_format($project['views']) ?></dd></div>
              </dl>
            </section>

            <?php if ($project['demo_url'] !== '' || $project['github_url'] !== ''): ?>
              <section class="sidebar-card">
                <h3>Links</h3>
                <ul class="link-list">
                  <?php if ($project['demo_url'] !== ''): ?>
                    <li>
                      <a href="<?= e($project['demo_url']) ?>" target="_blank" rel="noopener noreferrer">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Live Demo
                      </a>
                    </li>
                  <?php endif; ?>
                  <?php if ($project['github_url'] !== ''): ?>
                    <li>
                      <a href="<?= e($project['github_url']) ?>" target="_blank" rel="noopener noreferrer">
                        <i class="fa-brands fa-github"></i> Source Code
                      </a>
                    </li>
                  <?php endif; ?>
                </ul>
              </section>
            <?php endif; ?>
          </aside>
        </div>

      </article>

    <?php endif; ?>

  </main>

  <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
