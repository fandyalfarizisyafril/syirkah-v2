const INTERVAL = 5000;

export function initHeroCarousel(hero) {
    if (hero.dataset.initialized) return;
    const slides = [...hero.querySelectorAll('[data-slide]')];
    const copies = [...hero.querySelectorAll('[data-copy]')];
    const nav = hero.querySelector('.hero-focus-nav');
    const buttons = [...hero.querySelectorAll('[data-focus-slide]')];
    if (slides.length < 2 || buttons.length !== slides.length) return;
    hero.dataset.initialized = 'true';

    const motion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const events = new AbortController();
    const loads = new Map();
    const failed = new Set();
    let active = 0;
    let visible = false;
    let disposed = false;
    let pageLoaded = document.readyState === 'complete';
    let timer;
    let transitionTimer;
    let request = 0;
    const canRotate = () => !disposed && !motion.matches && visible && !document.hidden;
    const listen = (target, event, fn) => target.addEventListener(event, fn, { signal: events.signal });

    const load = index => {
        if (!loads.has(index)) {
            const image = slides[index];
            if (image.dataset.src) image.src = image.dataset.src;
            loads.set(index, image.decode().then(() => true).catch(() => {
                failed.add(index);
                buttons[index].disabled = true;
                return false;
            }));
        }
        return loads.get(index);
    };

    const nextAvailable = (from, direction = 1) => {
        for (let offset = 1; offset < slides.length; offset++) {
            const index = (from + direction * offset + slides.length) % slides.length;
            if (!failed.has(index)) return index;
        }
        return from;
    };

    // Warm only the next slide, never all full-size images at page load.
    const preloadNext = () => {
        if (pageLoaded && !disposed) load(nextAvailable(active));
    };

    const schedule = () => {
        window.clearTimeout(timer);
        if (canRotate() && failed.size < slides.length - 1) {
            timer = window.setTimeout(() => show(nextAvailable(active)), INTERVAL);
        }
    };

    const revealNavigation = () => {
        const button = buttons[active];
        const bounds = nav.getBoundingClientRect();
        const item = button.getBoundingClientRect();
        if (item.left < bounds.left || item.right > bounds.right) {
            nav.scrollTo({
                left: nav.scrollLeft + item.left - bounds.left - (nav.clientWidth - item.width) / 2,
                behavior: motion.matches ? 'instant' : 'smooth',
            });
        }
    };

    const show = async (index, manual = false) => {
        const token = ++request;
        window.clearTimeout(timer);
        const ready = await load(index);
        if (disposed || token !== request) return;
        if (ready && (manual || canRotate())) {
            window.clearTimeout(transitionTimer);
            const previous = active;
            active = index;
            slides.forEach((slide, i) => {
                slide.classList.toggle('is-previous', i === previous && i !== active);
                slide.classList.toggle('is-active', i === active);
                buttons[i].setAttribute('aria-current', String(i === active));
            });
            copies.forEach(copy => {
                const index = Number(copy.dataset.copy);
                copy.classList.toggle('is-leaving', index === previous && index !== active);
                copy.classList.toggle('is-active', index === active);
                copy.setAttribute('aria-hidden', String(index !== active));
            });
            hero.dataset.activeSlide = String(active);
            revealNavigation();
            transitionTimer = window.setTimeout(() => {
                slides.forEach(slide => slide.classList.remove('is-previous'));
                copies.forEach(copy => copy.classList.remove('is-leaving'));
            }, motion.matches ? 0 : 950);
            preloadNext();
        }
        schedule();
    };

    buttons.forEach((button, index) => listen(button, 'click', () => show(index, true)));
    listen(nav, 'keydown', event => {
        const current = buttons.indexOf(document.activeElement);
        if (current < 0 || !['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) return;
        event.preventDefault();
        const enabled = buttons.map((button, index) => button.disabled ? -1 : index).filter(index => index >= 0);
        const target = event.key === 'Home' ? enabled[0] : event.key === 'End' ? enabled.at(-1)
            : nextAvailable(current, event.key === 'ArrowLeft' ? -1 : 1);
        buttons[target].focus({ preventScroll: true });
        show(target, true);
    });
    listen(document, 'visibilitychange', schedule);
    listen(motion, 'change', schedule);

    const visibility = new IntersectionObserver(entries => {
        visible = entries[0].isIntersecting;
        schedule();
    });
    visibility.observe(hero);

    const header = document.querySelector('.site-header');
    const utility = document.querySelector('.utility');
    const updateHeaderHeight = () => {
        const height = (header?.getBoundingClientRect().height ?? 0) + (utility?.getBoundingClientRect().height ?? 0);
        hero.style.setProperty('--hero-header-height', height + 'px');
    };
    const sizing = new ResizeObserver(updateHeaderHeight);
    if (header) sizing.observe(header);
    if (utility) sizing.observe(utility);
    updateHeaderHeight();

    const cleanup = () => {
        disposed = true;
        request++;
        window.clearTimeout(timer);
        window.clearTimeout(transitionTimer);
        visibility.disconnect();
        sizing.disconnect();
        events.abort();
        delete hero.dataset.initialized;
    };
    listen(window, 'pagehide', event => {
        request++;
        window.clearTimeout(timer);
        if (!event.persisted) cleanup();
    });
    listen(window, 'pageshow', schedule);
    listen(window, 'load', () => { pageLoaded = true; preloadNext(); });
    nav.hidden = false;
    hero.dataset.activeSlide = '0';
    preloadNext();
    schedule();
    return cleanup;
}
