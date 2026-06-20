const navToggle = document.querySelector('.nav-toggle');
const navMenu = document.querySelector('.nav-menu');

if (navToggle && navMenu) {
  navToggle.addEventListener('click', () => {
    const isOpen = navMenu.classList.toggle('open');
    navToggle.setAttribute('aria-expanded', String(isOpen));
  });

  navMenu.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => {
      navMenu.classList.remove('open');
      navToggle.setAttribute('aria-expanded', 'false');
    });
  });
}

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (!prefersReducedMotion) {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.14 });

  document.querySelectorAll('.reveal').forEach((element) => observer.observe(element));
} else {
  document.querySelectorAll('.reveal').forEach((element) => element.classList.add('visible'));
}

const contactForm = document.querySelector('#contact-form');
const contactStatus = document.querySelector('#contact-status');

if (contactForm && contactStatus) {
  const status = new URLSearchParams(window.location.hash.split('?')[1] || '').get('status');
  const messages = {
    sent: 'Thanks — your message has been sent.',
    failed: 'Sorry, your message could not be sent. Please try again later.',
    invalid: 'Please complete the form with a valid email address.',
  };

  if (messages[status]) {
    contactStatus.textContent = messages[status];
  }
}
