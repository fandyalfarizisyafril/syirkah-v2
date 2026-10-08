import { createIcons, ArrowUpRight, ArrowRight, ArrowLeft, Search, Menu, MapPin, Check, MessageCircle, Phone, Mail, Download, ChevronLeft, ChevronRight, RotateCcw, PackageSearch, ExternalLink, LogOut, LayoutDashboard, Layers, Package, BadgeCheck, Factory, Inbox, Settings, Plus, Pencil, Trash2, Save, Eye, EyeOff } from 'lucide';
import { initHeroCarousel } from './hero-carousel';
import { initBrandMarquee } from './brand-marquee';

const icons = { ArrowUpRight, ArrowRight, ArrowLeft, Search, Menu, MapPin, Check, MessageCircle, Phone, Mail, Download, ChevronLeft, ChevronRight, RotateCcw, PackageSearch, ExternalLink, LogOut, LayoutDashboard, Layers, Package, BadgeCheck, Factory, Inbox, Settings, Plus, Pencil, Trash2, Save, Eye, EyeOff };
const renderIcons = () => createIcons({ icons });
renderIcons();

const passwordToggle = document.querySelector('.cms-login-page [data-password-toggle]');
if (passwordToggle) {
    const password = document.getElementById(passwordToggle.getAttribute('aria-controls'));
    passwordToggle.hidden = false;
    passwordToggle.addEventListener('click', () => {
        const visible = password.type === 'password';
        password.type = visible ? 'text' : 'password';
        const label = visible ? 'Sembunyikan password' : 'Tampilkan password';
        passwordToggle.setAttribute('aria-label', label);
        passwordToggle.setAttribute('aria-pressed', String(visible));
        passwordToggle.title = label;
        passwordToggle.querySelector('[data-password-show]').hidden = visible;
        passwordToggle.querySelector('[data-password-hide]').hidden = !visible;
    });
}
document.querySelectorAll('[data-hero-carousel]').forEach(initHeroCarousel);
document.querySelectorAll('.home-brand-marquee').forEach(initBrandMarquee);

const toggle = document.querySelector('.menu-toggle');
const nav = document.querySelector('#primary-nav');
const closeMenu = () => {
    nav?.classList.remove('is-open');
    toggle?.setAttribute('aria-expanded', 'false');
    toggle?.setAttribute('aria-label', 'Buka navigasi');
};
toggle?.addEventListener('click', () => {
    const open = nav.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Tutup navigasi' : 'Buka navigasi');
});
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && nav?.classList.contains('is-open')) { closeMenu(); toggle.focus(); }
});
document.addEventListener('click', (event) => {
    if (nav?.classList.contains('is-open') && !event.target.closest('.header-inner')) closeMenu();
});
document.addEventListener('focusin', (event) => {
    if (nav?.classList.contains('is-open') && !event.target.closest('.header-inner')) closeMenu();
});
window.matchMedia('(min-width:901px)').addEventListener('change', closeMenu);

document.querySelectorAll('[data-confirm]').forEach(form => form.addEventListener('submit', event => {
    if (!window.confirm(form.dataset.confirm)) event.preventDefault();
}));
document.querySelectorAll('[data-submit-form]').forEach(form => form.addEventListener('submit', () => {
    const button = form.querySelector('button[type=submit],button:not([type])');
    if (button) { button.disabled = true; button.setAttribute('aria-busy', 'true'); }
}));
window.addEventListener('pageshow', () => document.querySelectorAll('[aria-busy=true]').forEach(button => {
    button.disabled = false; button.removeAttribute('aria-busy');
}));
document.querySelector('[data-error-summary]')?.focus();

document.querySelectorAll('[data-gallery-src]').forEach(button => button.addEventListener('click', () => {
    const image = document.querySelector('#product-main-image');
    image.src = button.dataset.gallerySrc;
    image.alt = button.querySelector('img').alt;
}));
const source = document.querySelector('[data-slug-source]');
const target = document.querySelector('[data-slug-target]');
let autoSlug = target && !target.value;
target?.addEventListener('input', () => { autoSlug = !target.value; });
source?.addEventListener('input', () => {
    if (autoSlug) target.value = source.value.normalize('NFKD').replace(/[\u0300-\u036f]/g, '').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
});
const specs = document.querySelector('#specifications');
let specIndex = Math.max(-1, ...Array.from(specs?.querySelectorAll('input[name$="[label]"]') ?? [], input => Number(input.name.match(/\[(\d+)\]/)?.[1] ?? -1))) + 1;
document.querySelector('[data-add-spec]')?.addEventListener('click', () => {
    const row = document.createElement('div');
    row.className = 'spec-row';
    row.innerHTML = `<div class="field"><label for="spec-label-${specIndex}">Parameter</label><input id="spec-label-${specIndex}" name="specifications[${specIndex}][label]" maxlength="150"></div><div class="field"><label for="spec-value-${specIndex}">Nilai</label><input id="spec-value-${specIndex}" name="specifications[${specIndex}][value]" maxlength="500"></div><button type="button" class="icon-button danger" data-remove-spec title="Hapus spesifikasi" aria-label="Hapus spesifikasi"><i data-lucide="trash-2"></i></button>`;
    specs.append(row); specIndex++; renderIcons(); row.querySelector('input').focus();
});
specs?.addEventListener('click', (event) => {
    const button = event.target.closest('[data-remove-spec]');
    if (button) { button.closest('.spec-row').remove(); document.querySelector('[data-add-spec]').focus(); }
});
