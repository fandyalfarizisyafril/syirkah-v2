import '../css/product-details.css';

// The existing gallery handler owns the image swap; this only tracks selection.
const gallery = document.querySelector('.product-showcase-page .gallery');
gallery?.addEventListener('click', (event) => {
    const selected = event.target.closest('[data-gallery-src]');
    if (!selected) return;
    gallery.querySelectorAll('[data-gallery-src]').forEach(button => {
        button.setAttribute('aria-pressed', String(button === selected));
    });
});
