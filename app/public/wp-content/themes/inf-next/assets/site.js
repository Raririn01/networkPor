(() => {
  'use strict';
  document.documentElement.classList.remove('no-js');
  const menu = document.querySelector('.menu-toggle');
  const nav = document.querySelector('#main-nav');
  const closeMenu = () => { nav?.classList.remove('is-open'); menu?.setAttribute('aria-expanded', 'false'); };
  menu?.addEventListener('click', () => {
    const open = menu.getAttribute('aria-expanded') !== 'true';
    menu.setAttribute('aria-expanded', String(open)); nav.classList.toggle('is-open', open);
  });
  nav?.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMenu));
  document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && nav?.classList.contains('is-open')) { closeMenu(); menu.focus(); }
  });
  document.querySelectorAll('[data-year-tabs]').forEach(group => {
    const tabs = [...group.querySelectorAll('[role="tab"]')];
    const panels = tabs.map(tab => document.getElementById(tab.getAttribute('aria-controls')));
    function activate(tab, focus = false) {
      tabs.forEach((item, i) => {
        const active = item === tab;
        item.setAttribute('aria-selected', String(active)); item.tabIndex = active ? 0 : -1;
        panels[i].hidden = !active;
      });
      if (focus) tab.focus();
    }
    tabs.forEach((tab, index) => {
      tab.addEventListener('click', () => activate(tab));
      tab.addEventListener('keydown', event => {
        const next = {ArrowRight:(index+1)%tabs.length,ArrowLeft:(index+tabs.length-1)%tabs.length,Home:0,End:tabs.length-1}[event.key];
        if (next !== undefined) { event.preventDefault(); activate(tabs[next], true); }
      });
    });
    if (tabs.length) activate(tabs[0]);
  });
  document.querySelectorAll('[data-award-carousel]').forEach(carousel => {
    const slides = [...carousel.querySelectorAll('[data-award-slide]')];
    const dots = [...carousel.querySelectorAll('[data-award-dot]')];
    const pauseButton = carousel.querySelector('[data-award-pause]');
    if (slides.length < 2) return;
    let current = 0, userPaused = false, hoverPaused = false, focusPaused = false;
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    function show(index) {
      current = (index + slides.length) % slides.length;
      slides.forEach((slide, i) => { slide.hidden = i !== current; slide.classList.toggle('is-active', i === current); });
      dots.forEach((dot, i) => { if (i === current) dot.setAttribute('aria-current', 'true'); else dot.removeAttribute('aria-current'); });
    }
    const timer = window.setInterval(() => {
      if (!userPaused && !hoverPaused && !focusPaused && !document.hidden && !reducedMotion) show(current + 1);
    }, 6500);
    carousel.querySelector('[data-award-prev]')?.addEventListener('click', () => show(current - 1));
    carousel.querySelector('[data-award-next]')?.addEventListener('click', () => show(current + 1));
    dots.forEach((dot, i) => dot.addEventListener('click', () => show(i)));
    pauseButton?.addEventListener('click', () => {
      userPaused = !userPaused;
      pauseButton.textContent = userPaused ? 'เล่นต่อ' : 'หยุด';
      pauseButton.setAttribute('aria-label', userPaused ? 'เล่นการเลื่อนข่าวอัตโนมัติ' : 'หยุดการเลื่อนข่าว');
    });
    carousel.addEventListener('mouseenter', () => { hoverPaused = true; });
    carousel.addEventListener('mouseleave', () => { hoverPaused = false; });
    carousel.addEventListener('focusin', () => { focusPaused = true; });
    carousel.addEventListener('focusout', event => { if (!carousel.contains(event.relatedTarget)) focusPaused = false; });
    show(0);
  });
  const links = [...document.querySelectorAll('.cs-subnav a')];
  if ('IntersectionObserver' in window && links.length) {
    const observer = new IntersectionObserver(entries => {
      const current = entries.filter(entry => entry.isIntersecting).sort((a,b) => a.boundingClientRect.top-b.boundingClientRect.top)[0];
      if (!current) return;
      links.forEach(link => {
        if (link.hash === `#${current.target.id}`) link.setAttribute('aria-current','location'); else link.removeAttribute('aria-current');
      });
    }, {rootMargin:'-80px 0px -55% 0px',threshold:0});
    links.forEach(link => { const section=document.querySelector(link.hash); if(section) observer.observe(section); });
  }
})();
