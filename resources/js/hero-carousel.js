export function initHeroCarousel(hero) {
    const slides = [...hero.querySelectorAll('[data-slide]')];
    const motion = window.matchMedia('(prefers-reduced-motion: reduce)');
    if (slides.length < 2) return;

    let active = 0;
    let visible = false;
    let timer;
    let request = 0;
    const loads = new Map();
    const failed = new Set();
    const canRotate = () => !motion.matches && visible && !document.hidden;

    const load = index => {
        if (!loads.has(index)) {
            const image = slides[index];
            if (image.dataset.src) image.src = image.dataset.src;
            loads.set(index, image.decode().then(() => true).catch(() => {
                failed.add(index);
                return false;
            }));
        }
        return loads.get(index);
    };

    const schedule = () => {
        window.clearTimeout(timer);
        if (canRotate() && failed.size < slides.length - 1) {
            timer = window.setTimeout(step, 6000);
        }
    };

    const sync = () => {
        hero.dataset.activeSlide = String(active);
        schedule();
    };

    const show = async index => {
        const token = ++request;
        window.clearTimeout(timer);
        // Keep the current image visible until the replacement is decoded.
        const ready = await load(index);
        if (token !== request) return;
        if (ready && canRotate()) {
            active = index;
            slides.forEach((slide, i) => {
                slide.classList.toggle('is-active', i === active);
                slide.setAttribute('aria-hidden', String(i !== active));
            });
        }
        sync();
    };

    const step = () => {
        for (let offset = 1; offset < slides.length; offset++) {
            const index = (active + offset) % slides.length;
            if (!failed.has(index)) { show(index); return; }
        }
    };

    document.addEventListener('visibilitychange', schedule);
    motion.addEventListener('change', schedule);
    new IntersectionObserver(entries => {
        visible = entries[0].isIntersecting;
        schedule();
    }, { threshold: 0 }).observe(hero);
    window.addEventListener('pagehide', () => { request++; window.clearTimeout(timer); });
    window.addEventListener('pageshow', schedule);

    sync();
    // Secondary images must not compete with the first hero image at page load.
    const preload = () => slides.forEach((_, index) => { if (index) load(index); });
    if (document.readyState === 'complete') preload();
    else window.addEventListener('load', preload, { once: true });
}
