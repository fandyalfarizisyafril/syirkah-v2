import { createIcons, DraftingCompass, Cog, Zap, Gauge, ShieldCheck } from 'lucide';
import { initIndustrialPartner as initSectionReveal } from './industrial-partner';

const focusGrid = document.querySelector('.about-page .about-focus-grid');
if (focusGrid) {
    createIcons({ root: focusGrid, nameAttr: 'data-about-icon', icons: { DraftingCompass, Cog, Zap, Gauge, ShieldCheck } });
}

document.querySelectorAll('.about-page [data-about-reveal]').forEach(initSectionReveal);
