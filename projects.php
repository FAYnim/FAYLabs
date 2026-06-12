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
  <style>
    /* ── Page-specific additions ── */
    .projects-hero {
      padding: 80px 0 56px;
      border-bottom: 1px solid var(--border);
    }

    .projects-hero .page-back {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 32px;
      padding: 10px 18px;
      border: 1px solid var(--border);
      border-radius: 999px;
      background: rgba(255,255,255,0.72);
      color: var(--muted);
      font-size: 0.88rem;
      font-weight: 700;
      transition: transform 200ms ease, background 200ms ease, color 200ms ease;
    }

    .projects-hero .page-back:hover {
      transform: translateY(-2px);
      background: white;
      color: var(--text);
    }

    .projects-hero h1 {
      margin-bottom: 16px;
      font-size: clamp(2.6rem, 6vw, 4.8rem);
    }

    .projects-hero p {
      max-width: 560px;
      color: var(--muted);
      font-size: 1.08rem;
    }

    .projects-grid-section {
      padding: 72px 0 104px;
    }

    /* Override showcase to always 3-col on wide, responsive below */
    .projects-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 22px;
    }

    /* Project card — compact card style (no alternating layout) */
    .project-card {
      display: flex;
      flex-direction: column;
      padding: 18px;
      border: 1px solid var(--border);
      border-radius: 30px;
      background: rgba(255, 255, 255, 0.64);
      box-shadow: var(--shadow-soft);
      transition: transform 260ms ease, box-shadow 260ms ease, border-color 260ms ease;
    }

    .project-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 28px 60px rgba(31, 41, 55, 0.11);
      border-color: rgba(96, 165, 250, 0.34);
    }

    .project-card .project-shot {
      width: 100%;
      display: block;
      aspect-ratio: 16 / 10;
      border: 1px solid rgba(255, 255, 255, 0.72);
      border-radius: 22px;
      object-fit: cover;
      margin-bottom: 16px;
    }

    .project-card .project-shot-placeholder {
      width: 100%;
      aspect-ratio: 16 / 10;
      border-radius: 22px;
      margin-bottom: 16px;
      background: linear-gradient(135deg, #dbeafe, #f7f6f3 45%, #93c5fd);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .project-card .project-shot-placeholder i {
      font-size: 2.4rem;
      color: rgba(96, 165, 250, 0.5);
    }

    .project-card .project-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      padding: 4px 8px 8px;
    }

    .project-card h2 {
      margin: 8px 0 10px;
      font-size: clamp(1.25rem, 2.5vw, 1.65rem);
      line-height: 1.05;
      letter-spacing: -0.045em;
    }

    .project-card .project-desc {
      flex: 1;
      color: var(--muted);
      font-size: 0.92rem;
      display: -webkit-box;
      overflow: hidden;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 3;
      line-clamp: 3;
      margin-bottom: 14px;
    }

    .project-card .project-meta {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: auto;
    }

    .project-card .project-links {
      margin-top: 0;
    }

    .project-card .project-links a {
      font-size: 0.88rem;
      padding: 0 16px;
      min-height: 42px;
      border: 1px solid var(--border);
      background: white;
      border-radius: 999px;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: transform 200ms ease, background 200ms ease;
    }

    .project-card .project-links a:hover {
      transform: translateY(-2px);
      background: var(--accent-soft);
      border-color: rgba(96, 165, 250, 0.4);
    }

    /* Empty state */
    .projects-empty {
      grid-column: 1 / -1;
      padding: 80px 24px;
      text-align: center;
      border: 1px dashed var(--border);
      border-radius: 30px;
      background: rgba(255, 255, 255, 0.48);
    }

    .projects-empty i {
      font-size: 3rem;
      color: var(--accent);
      margin-bottom: 20px;
      display: block;
    }

    .projects-empty h2 {
      font-size: 1.75rem;
      letter-spacing: -0.04em;
      margin-bottom: 10px;
    }

    .projects-empty p {
      color: var(--muted);
      max-width: 380px;
      margin: 0 auto 28px;
    }

    /* Load more */
    .load-more-wrap {
      display: flex;
      justify-content: center;
      margin-top: 48px;
    }

    #load-more-btn {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 0 28px;
      min-height: 52px;
      border: 1px solid var(--border);
      border-radius: 999px;
      background: rgba(255,255,255,0.8);
      font-family: "Geist", sans-serif;
      font-size: 0.96rem;
      font-weight: 700;
      color: var(--text);
      cursor: pointer;
      transition: transform 220ms ease, box-shadow 220ms ease, background 220ms ease;
    }

    #load-more-btn:hover:not(:disabled) {
      transform: translateY(-3px);
      box-shadow: var(--shadow-soft);
      background: white;
    }

    #load-more-btn:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    #load-more-btn .btn-spinner {
      display: none;
      width: 18px;
      height: 18px;
      border: 2px solid var(--border);
      border-top-color: var(--accent-strong);
      border-radius: 50%;
      animation: spin 0.65s linear infinite;
    }

    #load-more-btn.loading .btn-spinner {
      display: block;
    }

    #load-more-btn.loading .btn-label {
      opacity: 0.6;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    @media (max-width: 980px) {
      .projects-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
      }
    }

    @media (max-width: 600px) {
      .projects-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <a class="skip-link" href="#main">Skip to main content</a>

  <header class="site-header" aria-label="Site header">
    <nav class="nav container">
      <a class="brand" href="index.php" aria-label="FAYdev Labs home">FAYdev Labs</a>
    </nav>
  </header>

  <main id="main">

    <section class="projects-hero container reveal">
      <a class="page-back" href="index.php">
        <i class="fa-solid fa-arrow-left"></i> Back to Home
      </a>
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
                <img
                  class="project-shot"
                  src="<?= e($project['cover_image']) ?>"
                  alt="<?= e($project['title']) ?> project screenshot"
                  loading="<?= $index < 6 ? 'eager' : 'lazy' ?>"
                >
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
              ? `<img class="project-shot" src="${escHtml(project.cover_image)}" alt="${escHtml(project.title)} project screenshot" loading="lazy">`
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
