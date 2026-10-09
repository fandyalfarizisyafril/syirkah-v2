export function initIndustrialPartner(section) {
    if (section.dataset.revealInitialized) return;
    section.dataset.revealInitialized = 'true';
    // Only enhance entry: the server-rendered content stays visible without JavaScript or observer support.
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) return;
    const observer = new IntersectionObserver(entries => {
        if (!entries.some(entry => entry.isIntersecting)) return;
        section.classList.add('is-revealed');
        observer.disconnect();
    }, { threshold: .08 });
    observer.observe(section);
}
