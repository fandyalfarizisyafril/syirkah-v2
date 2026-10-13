import { createIcons, FileText, FileCheck2, Files } from 'lucide';

createIcons({ icons: { FileText, FileCheck2, Files } });

document.querySelectorAll('[data-publication-thumbnail]').forEach(image => {
    const hide = () => { image.hidden = true; };
    image.addEventListener('error', hide);
    if (image.complete && !image.naturalWidth) hide();
});

const form = document.querySelector('[data-publication-editor]');
if (form) {
    const category = form.querySelector('[name=category]');
    const status = form.querySelector('[name=status]');
    const approval = form.querySelector('[data-publication-approval]');
    const checkbox = approval.querySelector('input');
    const sync = () => {
        const required = category.value === 'legalitas' && status.value === 'published';
        approval.hidden = !required;
        checkbox.required = required;
        if (!required) checkbox.checked = false;
    };
    category.addEventListener('change', sync);
    status.addEventListener('change', sync);
    sync();
}
