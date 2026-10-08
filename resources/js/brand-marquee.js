export function initBrandMarquee(marquee) {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const resetScroll = () => {
        if (!reducedMotion.matches && !marquee.querySelector('a:focus-visible')) marquee.scrollLeft = 0;
    };
    marquee.addEventListener('focusin', event => {
        const link = event.target.closest('a');
        if (!link?.matches(':focus-visible')) return;
        // Wait for the CSS switch from the animated track to the static keyboard list.
        requestAnimationFrame(() => {
            if (document.activeElement === link) link.scrollIntoView({ block: 'nearest', inline: 'nearest', behavior: 'instant' });
        });
    });
    marquee.addEventListener('focusout', () => requestAnimationFrame(resetScroll));
    reducedMotion.addEventListener('change', resetScroll);
}
