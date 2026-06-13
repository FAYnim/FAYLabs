<?php
require __DIR__ . '/includes/projects.php';

$projects = fetchPublishedProjects();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="All projects built by FAY — product builder and full-stack developer. Explore real products focused on outcomes, systems, and learning loops.">
  <meta name="keywords" content="projects, product builder, full stack developer, portfolio, web apps, ai, automation">
  <meta property="og:title" content="Projects | FAY — Product Builder & Full-Stack Developer">
  <meta property="og:description" content="All projects built by FAY — real products focused on outcomes, systems, and learning loops.">
  <meta property="og:type" content="website">
  <title>Projects | FAY — Product Builder &amp; Full-Stack Developer</title>
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
  <link rel="stylesheet" href="assets/css/projects.css">
</head>
<body>
  <a class="skip-link" href="#main">Skip to main content</a>

  <header class="site-header" aria-label="Site header">
    <nav class="nav container">
      <a class="brand" href="index.php" aria-label="FAYdev Labs home">FAYdev Labs</a>
      <a class="nav-back" href="index.php">
        <i class="fa-solid fa-arrow-left"></i> Back to Home
      </a>
    </nav>
  </header>

  <main id="main">

    <section class="projects-hero container reveal">
      <p class="eyebrow">ALL PROJECTS</p>
      <h1>Real products, built from <span>problems.</span></h1>
      <p>Every project here started with a real problem. Explore the full list — each one built to learn, ship, and improve.</p>
    </section>

    <section class="projects-grid-section container" id="projects">
      <div class="projects-grid" id="projects-grid">
        <?php if (!empty($projects)): ?>
          <?php foreach ($projects as $index => $project): ?>
            <article class="project-card reveal" style="transition-delay: <?= min($index, 5) * 80 ?>ms">
              <?php if ($project['cover_image'] !== ''): ?>
                <div class="project-shot-wrap">
                  <img class="project-shot-bg" src="<?= e($project['cover_image']) ?>" alt="" aria-hidden="true">
                  <img
                    class="project-shot"
                    src="<?= e($project['cover_image']) ?>"
                    alt="<?= e($project['title']) ?> project screenshot"
                    loading="<?= $index < 6 ? 'eager' : 'lazy' ?>"
                  >
                </div>
              <?php else: ?>
                <div class="project-shot-placeholder" aria-hidden="true">
                  <i class="fa-solid fa-rectangle-history"></i>
                </div>
              <?php endif; ?>
              <div class="project-content">
                <ul class="tags">
                  <?php if ($project['label'] !== ''): ?><li><?= e($project['label']) ?></li><?php endif; ?>
                  <?php if ($project['project_year'] !== ''): ?><li><?= e($project['project_year']) ?></li><?php endif; ?>
                </ul>
                <h2><?= e($project['title']) ?></h2>
                <?php if ($project['description'] !== ''): ?>
                  <p class="project-desc"><?= e($project['description']) ?></p>
                <?php endif; ?>
                <div class="project-meta">
                  <div class="project-links">
                    <a href="<?= e(projectDetailUrl($project)) ?>">
                      View Project <i class="fa-solid fa-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="projects-empty">
            <i class="fa-regular fa-folder-open"></i>
            <h2>Projects will be available soon.</h2>
            <p>Published projects from the dashboard will appear here once they are ready.</p>
            <a class="button secondary" href="index.php">Back to Home</a>
          </div>
        <?php endif; ?>
      </div>

      <?php if (!empty($projects)): ?>
        <div class="load-more-wrap" id="load-more-wrap">
          <button class="button" id="load-more-btn" type="button" data-offset="6" aria-label="Load more projects">
            <span class="btn-spinner" aria-hidden="true"></span>
            <span class="btn-label">Load More Projects</span>
          </button>
        </div>
      <?php endif; ?>
    </section>

  </main>

  <?php require __DIR__ . '/partials/footer.php'; ?>

  <script>
    // Reveal on scroll
    const revealEls = document.querySelectorAll('.reveal');
    const revealObs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.classList.add('visible');
          revealObs.unobserve(e.target);
        }
      });
    }, { threshold: 0.08 });
    revealEls.forEach(el => revealObs.observe(el));


    // Load more
    const loadMoreBtn  = document.getElementById('load-more-btn');
    const projectsGrid = document.getElementById('projects-grid');
    const loadMoreWrap = document.getElementById('load-more-wrap');

    if (loadMoreBtn && projectsGrid) {
      loadMoreBtn.addEventListener('click', async () => {
        const offset = parseInt(loadMoreBtn.dataset.offset, 10);

        loadMoreBtn.disabled = true;
        loadMoreBtn.classList.add('loading');

        try {
          const res  = await fetch(`<?= htmlspecialchars(PROJECTS_API_URL, ENT_QUOTES, 'UTF-8') ?>`.replace('offset=0', `offset=${offset}`));
          const json = await res.json();

          if (!json.success || !Array.isArray(json.data?.projects)) {
            throw new Error('Invalid response');
          }

          const newProjects = json.data.projects;
          const hasMore     = json.data.has_more ?? false;

          newProjects.forEach((project, i) => {
            if (!project.title || !project.slug) return;

            const delay     = Math.min(i, 5) * 80;
            const coverHtml = project.cover_image
              ? `<div class="project-shot-wrap"><img class="project-shot-bg" src="${escHtml(project.cover_image)}" alt="" aria-hidden="true"><img class="project-shot" src="${escHtml(project.cover_image)}" alt="${escHtml(project.title)} project screenshot" loading="lazy"></div>`
              : `<div class="project-shot-placeholder" aria-hidden="true"><i class="fa-solid fa-rectangle-history"></i></div>`;

            const labelHtml = project.label
              ? `<li>${escHtml(project.label)}</li>`
              : '';
            const yearHtml = project.project_year
              ? `<li>${escHtml(project.project_year)}</li>`
              : '';
            const descHtml = project.description
              ? `<p class="project-desc">${escHtml(project.description)}</p>`
              : '';

            const slug   = encodeURIComponent(project.slug);
            const detail = `project.php?slug=${slug}`;

            const article = document.createElement('article');
            article.className = 'project-card reveal';
            article.style.transitionDelay = `${delay}ms`;
            article.innerHTML = `
              ${coverHtml}
              <div class="project-content">
                <ul class="tags">${labelHtml}${yearHtml}</ul>
                <h2>${escHtml(project.title)}</h2>
                ${descHtml}
                <div class="project-meta">
                  <div class="project-links">
                    <a href="${detail}">View Project <i class="fa-solid fa-arrow-right"></i></a>
                  </div>
                </div>
              </div>
            `;

            projectsGrid.appendChild(article);

            // Trigger reveal after paint
            requestAnimationFrame(() => {
              requestAnimationFrame(() => {
                revealObs.observe(article);
              });
            });
          });

          // Update offset
          loadMoreBtn.dataset.offset = offset + newProjects.length;

          // Hide button if no more
          if (!hasMore || newProjects.length === 0) {
            loadMoreWrap.style.display = 'none';
          }

        } catch (err) {
          console.error('Load more failed:', err);
        } finally {
          loadMoreBtn.disabled = false;
          loadMoreBtn.classList.remove('loading');
        }
      });
    }

    function escHtml(str) {
      const d = document.createElement('div');
      d.textContent = str;
      return d.innerHTML;
    }
  </script>
</body>
</html>
