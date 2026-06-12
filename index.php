<?php
require __DIR__ . '/includes/projects.php';

$projects = fetchPublishedProjects();

require __DIR__ . '/partials/header.php';
?>

  <main id="main">
    <section class="hero container section" id="top">
      <div class="hero-copy reveal">
        <p class="eyebrow">PRODUCT BUILDER</p>
        <h1>Problem Solver, Product Builder, <span>Lifelong Learner.</span></h1>
        <p class="hero-text">Building real products and sharing the journey publicly.</p>
        <div class="hero-actions">
          <a class="button primary" href="#work">Explore My Work</a>
          <a class="button secondary" href="#public">Follow My Journey</a>
        </div>

      </div>
      <div class="hero-visual reveal delay-1" aria-label="FAY portrait and project highlights">
        <div class="portrait-card">
          <img class="portrait-img" src="assets/images/faydev.my.id-profile-picture.webp" alt="FAY portrait photo">
        </div>
        <article class="float-card float-one">
          <strong>5</strong>
          <span>Project Selesai</span>
        </article>
        <article class="float-card float-two">
          <strong>3</strong>
          <span>Tahun Pengalaman</span>
        </article>
        <article class="float-card float-three">
          <strong>Fullstack</strong>
          <span>Developer</span>
        </article>
      </div>
    </section>

    <section class="section container" id="work">
      <div class="section-heading reveal">
        <p class="eyebrow">FEATURED WORK</p>
        <h2>Real products, built from problems.</h2>
        <p>Selected projects focused on outcomes, systems, and learning loops.</p>
      </div>
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
    </section>
      <div class="container">
        <div class="section-heading reveal">
          <p class="eyebrow">WHAT I BUILD</p>
          <h2>Not technology logos. Useful outcomes.</h2>
        </div>
        <div class="build-grid">
          <article class="build-card reveal"><h3>AI & Automation</h3><p>Smart workflows that reduce repetitive work, speed up decisions, and keep daily tasks moving with less effort.</p><ul class="tags"><li>AI Assistant</li><li>Workflow Automation</li><li>Content Helper</li></ul></article>
          <article class="build-card reveal"><h3>Business Solution</h3><p>Practical systems that organize operations, customer activity, and sales flow so teams can work with more clarity.</p><ul class="tags"><li>Company Profile</li><li>CRM</li><li>E-Commerce</li></ul></article>
          <article class="build-card reveal"><h3>Digital Products</h3><p>User-focused products that turn ideas into clear experiences people can understand, use, and benefit from quickly.</p><ul class="tags"><li>Member Portal</li><li>Booking Platform</li><li>Productivity Tool</li></ul></article>
          <article class="build-card reveal"><h3>Emerging Technologies</h3><p>Small experiments that test new possibilities and turn future-facing ideas into practical, useful outcomes.</p><ul class="tags"><li>Smart Search</li><li>Interactive Experience</li></ul></article>
        </div>
      </div>
    </section>

    <section class="section container about" id="about">
      <div class="section-heading reveal">
        <p class="eyebrow">ABOUT FAY</p>
        <h2>Technology should solve real problems.</h2>
      </div>
      <div class="about-grid">
        <article class="belief reveal">
          <div class="about-card-head">
            <span class="about-icon"><i class="fa-regular fa-lightbulb"></i></span>
            <div><h3>What I Believe</h3><span class="title-line"></span></div>
          </div>
          <p class="about-lead">Great products start by understanding real problems, not chasing trends. I believe good technology should feel simple, useful, and reliable, then keep improving through feedback, constraints, and real-world use.</p>
          <div class="belief-list">
            <div class="about-item"><span class="item-icon"><i class="fa-solid fa-bullseye"></i></span><div><strong>Solve Real Problems</strong></div></div>
            <div class="about-item"><span class="item-icon"><i class="fa-solid fa-arrow-trend-up"></i></span><div><strong>Create Real Value</strong></div></div>
            <div class="about-item"><span class="item-icon"><i class="fa-solid fa-code"></i></span><div><strong>Learn by Building</strong></div></div>
          </div>
        </article>
        <article class="process reveal">
          <div class="about-card-head">
            <span class="about-icon"><i class="fa-solid fa-gear"></i></span>
            <div><h3>How I Build</h3><span class="title-line"></span></div>
          </div>
          <ol>
            <li><span>Problem</span></li>
            <li><span>Research</span></li>
            <li><span>MVP</span></li>
            <li><span>Feedback</span></li>
            <li><span>Iteration</span></li>
          </ol>
        </article>
        <article class="focus reveal">
          <div class="about-card-head">
            <span class="about-icon"><i class="fa-solid fa-bullseye"></i></span>
            <div><h3>Current Focus</h3><span class="title-line"></span></div>
          </div>
          <div class="focus-list">
            <div class="about-item"><span class="item-icon"><i class="fa-solid fa-code"></i></span><div><strong>Product Development</strong></div></div>
            <div class="about-item"><span class="item-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></span><div><strong>AI & Automation</strong></div></div>
            <div class="about-item"><span class="item-icon"><i class="fa-solid fa-briefcase"></i></span><div><strong>Business Systems</strong></div></div>
            <div class="about-item"><span class="item-icon"><i class="fa-solid fa-bolt"></i></span><div><strong>Emerging Technologies</strong></div></div>
          </div>
        </article>
      </div>
    </section>

    <section class="section container" id="public">
      <div class="section-heading reveal">
        <p class="eyebrow">BUILDING IN PUBLIC</p>
        <h2>Follow the journey, not just the launch.</h2>
      </div>
      <div class="social-grid">
        <a class="social-card reveal" href="https://github.com/FAYnim" aria-label="See my GitHub"><span><i class="fa-brands fa-github"></i> GitHub</span><strong>See My GitHub</strong><p>Code, experiments, and product repositories.</p></a>
        <a class="social-card reveal" href="https://www.linkedin.com/in/faris-ay" aria-label="Follow on LinkedIn"><span><i class="fa-brands fa-linkedin"></i> LinkedIn</span><strong>Follow on LinkedIn</strong><p>Product lessons, build notes, and professional updates.</p></a>
        <a class="social-card reveal" href="https://www.instagram.com/fay.developer" aria-label="Follow on Instagram"><span><i class="fa-brands fa-instagram"></i> Instagram</span><strong>Follow on Instagram</strong><p>Behind-the-scenes snapshots from the building process.</p></a>
        <a class="social-card reveal" href="https://www.threads.com/@faris.a.y" aria-label="Read my Threads"><span><i class="fa-brands fa-threads"></i> Threads</span><strong>Read My Threads</strong><p>Short thoughts, experiments, and progress updates.</p></a>
      </div>
    </section>

  </main>

<?php require __DIR__ . '/partials/footer.php'; ?>
