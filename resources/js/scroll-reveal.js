import '../css/scroll-reveal.css';

// Public-only allowlist: never observe moving tracks, PDF viewers or individual inputs.
const excluded = '[data-hero-carousel], [data-category-carousel], .home-brand-marquee, [data-industrial-partner], [data-about-reveal], .inquiry-band, .site-header, .site-footer, .publication-pdf, .gallery';
const groups = '.product-grid, .category-grid, .brand-portfolio-grid, .publications-grid, .brand-technology-grid, .directory-grid, .value-strip, .search-links';
const blocks = [
    '.section-heading', '.page-intro', '.catalog-results-heading', '.brand-portfolio-intro',
    '.brand-detail-intro', '.brand-detail-profile > div', '.brand-products-heading',
    '.brand-detail-technology > .container > h2', '.brand-detail-technology > .container > .brand-detail-eyebrow',
    '.publications-intro', '.publications-heading', '.publications-verification > div',
    '.publication-viewer-heading > div', '.publication-demo-notice',
    '.product-detail > div:last-child', '.detail-sections > div', '.table-wrap:has(.spec-table)',
    '.contact-info', '.contact-grid > div:last-child', '.reading > *',
    '.section > h2', '.section > .eyebrow', '.section > .container > h2', '.section > .container > .eyebrow',
    '.catalog-section > h2', '.about-legal > *',
].join(',');
const visuals = '.brand-detail-logo, .category-detail-image, .directory-image, #product-main-image';

export function initScrollReveal(root) {
    if (!root || root.dataset.scrollRevealInitialized) return;
    root.dataset.scrollRevealInitialized = 'true';
    const motion = window.matchMedia('(prefers-reduced-motion: reduce)');
    if (!('IntersectionObserver' in window) || motion.matches) return;

    const seen = new WeakSet();
    const active = new Map();
    let observer;
    let mutations;
    let frame;
    let stopped = false;

    const finish = element => {
        clearTimeout(active.get(element));
        active.delete(element);
        observer?.unobserve(element);
        element.dataset.scrollRevealState = 'visible';
        element.style.removeProperty('--reveal-delay');
        element.style.removeProperty('--reveal-base-transition');
    };
    const showAll = () => {
        for (const element of active.keys()) finish(element);
    };
    const stop = () => {
        stopped = true;
        cancelAnimationFrame(frame);
        mutations?.disconnect();
        showAll();
        observer?.disconnect();
    };

    const scan = () => {
        if (stopped) return;
        try {
            const candidates = new Map();
            root.querySelectorAll(blocks).forEach(element => candidates.set(element, ['up', 0]));
            root.querySelectorAll(visuals).forEach(element => candidates.set(element, ['fade', 0]));
            root.querySelectorAll(groups).forEach(group => {
                [...group.children].forEach((element, index) => {
                    if (!element.matches('.empty-state')) candidates.set(element, ['stagger', Math.min(index * 90, 360)]);
                });
            });
            // The photograph is not part of About's existing text entrance animation.
            root.querySelectorAll('.about-photograph').forEach(element => candidates.set(element, ['fade', 0]));
            const eligible = [...candidates].filter(([element]) =>
                !element.closest(excluded) || element.matches('.about-photograph'));
            const targets = new Set(eligible.map(([element]) => element));
            const measurements = eligible.filter(([element]) => {
                if (seen.has(element)) return false;
                for (let parent = element.parentElement; parent && parent !== root; parent = parent.parentElement) {
                    if (targets.has(parent) || parent.hasAttribute('data-scroll-reveal')) return false;
                }
                return true;
            }).map(([element, [type, delay]]) => ({ element, type, delay,
                box: element.getBoundingClientRect(), transition: getComputedStyle(element).transition,
            }));
            for (const { element, type, delay, box, transition } of measurements) {
                seen.add(element);
                element.dataset.scrollReveal = type;
                // Initial viewport, restored scroll positions and hidden panels never wait for animation.
                if (!box.height || box.top < innerHeight || element.contains(document.activeElement)) {
                    element.dataset.scrollRevealState = 'visible';
                    continue;
                }
                observer.observe(element);
                element.style.setProperty('--reveal-delay', `${delay}ms`);
                element.style.setProperty('--reveal-base-transition', transition);
                active.set(element, null);
                element.dataset.scrollRevealState = 'pending';
            }
            for (const element of active.keys()) if (!element.isConnected) finish(element);
        } catch {
            stop(); // A failed enhancement must never leave content hidden.
        }
    };

    try {
        observer = new IntersectionObserver(entries => {
            try {
                for (const { target, isIntersecting } of entries) {
                    if (!isIntersecting) continue;
                    observer.unobserve(target);
                    target.dataset.scrollRevealState = 'revealing';
                    const delay = parseInt(target.style.getPropertyValue('--reveal-delay'), 10) || 0;
                    active.set(target, setTimeout(() => finish(target), 700 + delay));
                }
            } catch { stop(); }
        }, { threshold: 0, rootMargin: '0px 0px -12px 0px' });
        scan();
        if (stopped) return;
        mutations = new MutationObserver(() => {
            cancelAnimationFrame(frame);
            frame = requestAnimationFrame(scan);
        });
        mutations.observe(root, { childList: true, subtree: true });
    } catch { stop(); return; }

    root.addEventListener('focusin', event => {
        for (let element = event.target; element && element !== root; element = element.parentElement) {
            if (active.has(element)) finish(element);
        }
    });
    motion.addEventListener('change', event => { if (event.matches) stop(); });
    window.addEventListener('pageshow', event => { if (event.persisted) stop(); });
    window.addEventListener('beforeprint', showAll);
    window.addEventListener('pagehide', stop, { once: true });
}

initScrollReveal(document.querySelector('[data-public-reveal-root]'));
