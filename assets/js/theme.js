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
