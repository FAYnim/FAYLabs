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

function showToast({ type = 'info', message, duration = 5000 }) {
  if (!message) return null;

  const container = document.querySelector('.toast-region') || (() => {
    const region = document.createElement('div');
    region.className = 'toast-region';
    region.setAttribute('aria-live', 'polite');
    region.setAttribute('aria-atomic', 'true');
    document.body.appendChild(region);
    return region;
  })();

  const icons = {
    success: 'fa-circle-check',
    error: 'fa-circle-exclamation',
    warning: 'fa-triangle-exclamation',
    info: 'fa-circle-info',
  };

  const toast = document.createElement('div');
  toast.className = `toast toast--${type}`;
  toast.setAttribute('role', type === 'error' ? 'alert' : 'status');

  const icon = document.createElement('span');
  icon.className = 'toast-icon';
  icon.innerHTML = `<i class="fa-solid ${icons[type] || icons.info}" aria-hidden="true"></i>`;

  const text = document.createElement('span');
  text.className = 'toast-message';
  text.textContent = message;

  const close = document.createElement('button');
  close.type = 'button';
  close.className = 'toast-close';
  close.setAttribute('aria-label', 'Dismiss notification');
  close.innerHTML = '<i class="fa-solid fa-xmark" aria-hidden="true"></i>';

  toast.append(icon, text, close);
  container.appendChild(toast);

  requestAnimationFrame(() => toast.classList.add('toast--visible'));

  let timer;
  const dismiss = () => {
    clearTimeout(timer);
    toast.classList.remove('toast--visible');
    toast.classList.add('toast--leaving');
    toast.addEventListener('transitionend', () => toast.remove(), { once: true });
  };

  close.addEventListener('click', dismiss);
  timer = setTimeout(dismiss, duration);

  return dismiss;
}

if (contactForm) {
  const status = new URLSearchParams(window.location.hash.split('?')[1] || '').get('status');
  const messages = {
    sent: { type: 'success', text: 'Thanks — your message has been sent. Check your inbox for the confirmation.' },
    failed: { type: 'error', text: 'Sorry, your message could not be sent. Please try again later.' },
    invalid: { type: 'warning', text: 'Please complete the form with a valid email address.' },
  };

  if (status && messages[status]) {
    if (contactStatus) {
      contactStatus.textContent = messages[status].text;
    }
    showToast({ type: messages[status].type, message: messages[status].text });

    history.replaceState(null, '', window.location.pathname + window.location.hash.split('?')[0]);
  }
}
