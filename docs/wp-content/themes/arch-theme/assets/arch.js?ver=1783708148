// ARCH site — vanilla JS interactivity (no React, no build step)

document.addEventListener('DOMContentLoaded', function () {

  // ---- Mobile menu toggle ----
  var menuBtn = document.getElementById('menu-toggle');
  var mobileNav = document.getElementById('mobile-nav');
  var iconMenu = document.getElementById('icon-menu');
  var iconClose = document.getElementById('icon-close');

  if (menuBtn && mobileNav) {
    menuBtn.addEventListener('click', function () {
      var isOpen = mobileNav.classList.toggle('open');
      iconMenu.style.display = isOpen ? 'none' : 'block';
      iconClose.style.display = isOpen ? 'block' : 'none';
    });
    mobileNav.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        mobileNav.classList.remove('open');
        iconMenu.style.display = 'block';
        iconClose.style.display = 'none';
      });
    });
  }

  // ---- Search dropdown toggle ----
  var searchBtn = document.getElementById('search-toggle');
  var searchPanel = document.getElementById('search-panel');
  var searchInput = document.getElementById('search-panel-input');

  if (searchBtn && searchPanel) {
    searchBtn.addEventListener('click', function () {
      var isOpen = searchPanel.classList.toggle('open');
      searchBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      if (isOpen && searchInput) {
        searchInput.focus();
      }
    });
    // Close if someone clicks outside the panel/button
    document.addEventListener('click', function (e) {
      if (!searchPanel.contains(e.target) && e.target !== searchBtn && !searchBtn.contains(e.target)) {
        searchPanel.classList.remove('open');
        searchBtn.setAttribute('aria-expanded', 'false');
      }
    });
    // Close on Escape
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        searchPanel.classList.remove('open');
        searchBtn.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // ---- Scroll reveal ----
  var revealEls = document.querySelectorAll('[data-reveal]');
  if ('IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.15 });
    revealEls.forEach(function (el) { observer.observe(el); });
  } else {
    // No IntersectionObserver support — just show everything
    revealEls.forEach(function (el) { el.classList.add('is-visible'); });
  }

  // ---- Newsletter form (demo — no backend) ----
  var newsletterForm = document.getElementById('newsletter-form');
  var newsletterNote = document.getElementById('newsletter-note');
  if (newsletterForm && newsletterNote) {
    newsletterForm.addEventListener('submit', function (e) {
      e.preventDefault();
      newsletterNote.classList.add('visible');
    });
  }

});
